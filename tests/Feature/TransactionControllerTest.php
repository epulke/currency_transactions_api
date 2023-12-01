<?php

namespace Tests\Feature;

use App\Http\Controllers\TransactionController;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Services\ExchangeRateService;

class TransactionControllerTest extends GeneralTestCase {

	public array $valid_transaction_request = [
		'accountid_from' => 1,
		'accountid_to' => 2,
		'amount' => 10,
		'currency' => 'USD'
	];
	public string $endpoint = '/api/transactions/';

	public function testValidTransactionForMakeTransaction() {
		$exchange_rate_service_mock = $this->createMock(ExchangeRateService::class);

		$exchange_rate_service_mock->method('getExchangeRate')->willReturn(1.1);

		$transactions_controller = new TransactionController($exchange_rate_service_mock);
		$transaction_request = new TransactionRequest($this->valid_transaction_request);

		$this->app->call([$transactions_controller, 'makeTransaction'],
			['request' => $transaction_request]
		);

		bcscale(16);

		$expected_transaction = [
			'transactionsid' => 2,
			'accountid_from' => 1,
			'accountid_to' => 2,
			'amount_from' => bcdiv('10', '1.1'),
			'amount_to' => round(10, 16),
			'exchange_rate' => round(1.1,16)
		];

		$trasaction = Transaction::where('transactionsid', $expected_transaction['transactionsid'])
			->select('transactionsid', 'accountid_from', 'accountid_to', 'amount_from', 'amount_to', 'exchange_rate')
			->get()
			->toArray();

		$this->assertEquals($expected_transaction, $trasaction[0]);
	}

	public function testInvalidAccountidFromForMakeTransaction() {
		$invalid_accountids_from = [
			'accountid_from_does_not_exist' => [
				'accountid_from' => 1000000000,
				'expected_error' => ['error' => ['accountid_from' => ['The selected accountid from is invalid.']]]
			],
			'accountid_from_is_not_integer' => [
				'accountid_from' => 'abc',
				'expected_error' => ['error' => ['accountid_from' => ['The accountid from must be an integer.']]]
			],
			'accountid_from_is_missing' => [
				'accountid_from' => ' ',
				'expected_error' => ['error' => ['accountid_from' => ['The accountid from field is required.']]]
			]
		];

		$request = $this->valid_transaction_request;

		foreach ($invalid_accountids_from as $invalid_accountid_from) {
			$request['accountid_from'] = $invalid_accountid_from['accountid_from'];
			$response = $this->post($this->endpoint, $request);

			$response->assertStatus(422);
			$response->assertExactJson($invalid_accountid_from['expected_error']);
		}
	}

	public function testInvalidAccountidToForMakeTransaction() {
		$invalid_accountids_to = [
			'accountid_to_does_not_exist' => [
				'accountid_to' => 1000000000,
				'expected_error' => ['error' => ['accountid_to' => ['The selected accountid to is invalid.']]]
			],
			'accountid_to_is_not_integer' => [
				'accountid_to' => 'abc',
				'expected_error' => ['error' => ['accountid_to' => ['The accountid to must be an integer.']]]
			],
			'accountid_to_is_missing' => [
				'accountid_to' => ' ',
				'expected_error' => ['error' => ['accountid_to' => ['The accountid to field is required.']]]
			],
			'accountid_to_differs_from_currency_parameter' => [
				'accountid_to' => 3,
				'expected_error' => ['error' =>
					"Invalid 'currency': 'USD' or 'accountid_to': '3' parameters."
				]
			]
		];

		$request = $this->valid_transaction_request;

		foreach ($invalid_accountids_to as $invalid_accountid_to) {
			$request['accountid_to'] = $invalid_accountid_to['accountid_to'];
			$response = $this->post($this->endpoint, $request);

			$response->assertStatus(422);
			$response->assertExactJson($invalid_accountid_to['expected_error']);
		}
	}

	public function testInvalidAmountForMakeTransaction() {
		$invalid_amounts = [
			'amount_is_not_numberic' => [
				'amount' => 'abc',
				'expected_error' => ['error' => ['amount' => ['The amount must be a number.']]]
			],
			'amount_is_missing' => [
				'amount' => ' ',
				'expected_error' => ['error' => ['amount' => ['The amount field is required.']]]
			],
			'amount_is_bigger_than_account_from_balance' => [
				'amount' => 100000000,
				'expected_error' => ['error' =>
					"There are not enough funds in the account with 'accountid': '1'."
				]
			]
		];

		$request = $this->valid_transaction_request;

		foreach ($invalid_amounts as $invalid_amount) {
			$request['amount'] = $invalid_amount['amount'];
			$response = $this->post($this->endpoint, $request);

			$response->assertStatus(422);
			$response->assertExactJson($invalid_amount['expected_error']);
		}
	}

	public function testInvalidCurrencyToForMakeTransaction() {
		$invalid_currencies = [
			'currency_does_not_exist' => [
				'currency' => 'XYZ',
				'expected_error' => ['error' => ['currency' => ['The selected currency is invalid.']]]
			],
			'currency_is_not_string' => [
				'currency' => 1111,
				'expected_error' => ['error' => ['currency' => ['The currency must be a string.']]]
			],
			'currency_is_missing' => [
				'currency' => ' ',
				'expected_error' => ['error' => ['currency' => ['The currency field is required.']]]
			],
			'currency_differs_from_account_to_currency' => [
				'currency' => 'EUR',
				'expected_error' => ['error' =>
					"Invalid 'currency': 'EUR' or 'accountid_to': '2' parameters."
				]
			]
		];

		$request = $this->valid_transaction_request;

		foreach ($invalid_currencies as $invalid_currency) {
			$request['currency'] = $invalid_currency['currency'];
			$response = $this->post($this->endpoint, $request);

			$response->assertStatus(422);
			$response->assertExactJson($invalid_currency['expected_error']);
		}
	}
}
