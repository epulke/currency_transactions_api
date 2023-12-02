<?php

namespace Tests\Feature;

class TransactionReportControllerTest extends GeneralTestCase {

	public string $endpoint = '/api/account_transactions/';

	public function testValidAccountidForGetTransactionsByAccountid() {
		$accountid = 1;

		$response = $this->get($this->endpoint . $accountid);

		$response->assertStatus(200);
		$response->assertJsonStructure([
			'transactions' => [
				'*' => [
					'transactionsid',
					'accountid_from',
					'accountid_to',
					'amount_from',
					'amount_to',
					'exchange_rate',
					'created_at',
					'updated_at'
				]
			]
		]);

		$response->assertExactJson([
			'transactions' => [
				[
					'transactionsid' => 1,
					'accountid_from' => 1,
					'accountid_to' => 2,
					'amount_from' => '10.0000000000000000',
					'amount_to' => '10.9000000000000000',
					'exchange_rate' => '1.0900000000000000',
					'created_at' => '2023-11-30T21:05:50.000000Z',
					'updated_at' => '2023-11-30T21:05:50.000000Z'
				]
			]
		]);
	}

	public function testValidTransactionsRequestForGetTransactionsByAccountid() {
		$valid_requests = [
			'offset_limit_not_specified' => [
				'accountid' => 5,
				'body' => []
			],
			'offset_limit_empty' => [
				'accountid' => 5,
				'body' => [
					'offset' => ' ',
					'limit' => ' '
				]
			],
			'offset_not_specified' => [
				'accountid' => 5,
				'body' => [
					'limit' => 2
				]
			],
			'offset_empty' => [
				'accountid' => 5,
				'body' => [
					'offset' => ' ',
					'limit' => 2
				]
			],
			'limit_not_specified' => [
				'accountid' => 5,
				'body' => [
					'offset' => 4
				]
			],
			'limit_empty' => [
				'accountid' => 5,
				'body' => [
					'offset' => 4,
					'limit' => ' '
				]
			],
			'full_valid_request' => [
				'accountid' => 5,
				'body' => [
					'offset' => 2,
					'limit' => 2
				]
			]
		];

		foreach ($valid_requests as $valid_request) {
			$response = $this->get($this->endpoint . $valid_request['accountid'], $valid_request['body']);

			$response->assertStatus(200);
			$response->assertJsonStructure([
				'transactions' => [
					'*' => [
						'transactionsid',
						'accountid_from',
						'accountid_to',
						'amount_from',
						'amount_to',
						'exchange_rate',
						'created_at',
						'updated_at'
					]
				]
			]);

			$offset = (!array_key_exists('offset', $valid_request) || $valid_request['offset'] === ' ')
				? 0
				: $valid_request['offset'];
			$limit = (!array_key_exists('limit', $valid_request) || $valid_request['limit'] === ' ')
				? null
				: $valid_request['limit'];

			$response->assertExactJson($this->getValidTransactionsByAccountResponse($offset, $limit));
		}
	}

	public function testInvalidAccountidForGetTransactionsByAccountid() {
		$invalid_accountids = [
			'accountid_does_not_exist' => [
				'accountid' => 1000000000,
				'expected_error' => ['error' => ['accountid' => ['The selected accountid is invalid.']]]
			],
			'accountid_is_not_integer' => [
				'accountid' => 'abc',
				'expected_error' => ['error' => ['accountid' => ['The accountid must be an integer.']]]
			],
			'accountid_is_missing' => [
				'accountid' => ' ',
				'expected_error' => ['error' => ['accountid' => ['The accountid field is required.']]]
			]
		];

		foreach ($invalid_accountids as $invalid_accountid) {
			$response = $this->get($this->endpoint . $invalid_accountid['accountid']);

			$response->assertStatus(422);
			$response->assertExactJson($invalid_accountid['expected_error']);
		}
	}

	public function testInvalidOffsetForGetTransactionsByAccountid() {
		$invalid_offset_requests = [
			'offset_is_not_integer' => [
				'accountid' => 5,
				'body' => [
					'offset' => 'abc'
				],
				'expected_error' => ['error' => ['offset' => ['The offset must be an integer.']]]
			],
			'offset_is_below_min' => [
				'accountid' => 1,
				'body' => [
					'offset' => -2
				],
				'expected_error' => ['error' => ['offset' => ['The offset must be at least 0.']]]
			]
		];

		foreach ($invalid_offset_requests as $invalid_offset_request) {
			$response = $this->get($this->endpoint . $invalid_offset_request['accountid'] . '?'
				. http_build_query($invalid_offset_request['body'])
			);

			$response->assertStatus(422);
			$response->assertExactJson($invalid_offset_request['expected_error']);
		}
	}

	public function testInvalidLimitForGetTransactionsByAccountid() {
		$invalid_limit_requests = [
			'limit_is_not_integer' => [
				'accountid' => 5,
				'body' => [
					'limit' => 'abc'
				],
				'expected_error' => ['error' => ['limit' => ['The limit must be an integer.']]]
			],
			'limit_is_below_min' => [
				'accountid' => 1,
				'body' => [
					'limit' => 0
				],
				'expected_error' => ['error' => ['limit' => [
					'The limit must be at least 1.', 'The limit field must not be null.'
				]]]
			],
			'limit_is_above_max' => [
				'accountid' => 1,
				'body' => [
					'limit' => 500
				],
				'expected_error' => ['error' => ['limit' => ['The limit must not be greater than 100.']]]
			]
		];

		foreach ($invalid_limit_requests as $invalid_limit_request) {
			$response = $this->get($this->endpoint . $invalid_limit_request['accountid'] . '?'
				. http_build_query($invalid_limit_request['body'])
			);

			$response->assertStatus(422);
			$response->assertExactJson($invalid_limit_request['expected_error']);
		}
	}

	private function getValidTransactionsByAccountResponse(int $offset = 0, int $limit = null): array {
		$all_transactions = [
			[
				'transactionsid' => 6,
				'accountid_from' => 5,
				'accountid_to' => 6,
				'amount_from' => '10.0000000000000000',
				'amount_to' => '10.9000000000000000',
				'exchange_rate' => '1.0900000000000000',
				'created_at' => '2023-11-30T21:05:55.000000Z',
				'updated_at' => '2023-11-30T21:05:55.000000Z'
			],
			[
				'transactionsid' => 5,
				'accountid_from' => 5,
				'accountid_to' => 6,
				'amount_from' => '10.0000000000000000',
				'amount_to' => '10.9000000000000000',
				'exchange_rate' => '1.0900000000000000',
				'created_at' => '2023-11-30T21:05:54.000000Z',
				'updated_at' => '2023-11-30T21:05:54.000000Z'
			],
			[
				'transactionsid' => 4,
				'accountid_from' => 5,
				'accountid_to' => 6,
				'amount_from' => '10.0000000000000000',
				'amount_to' => '10.9000000000000000',
				'exchange_rate' => '1.0900000000000000',
				'created_at' => '2023-11-30T21:05:53.000000Z',
				'updated_at' => '2023-11-30T21:05:53.000000Z'
			],
			[
				'transactionsid' => 3,
				'accountid_from' => 5,
				'accountid_to' => 6,
				'amount_from' => '10.0000000000000000',
				'amount_to' => '10.9000000000000000',
				'exchange_rate' => '1.0900000000000000',
				'created_at' => '2023-11-30T21:05:52.000000Z',
				'updated_at' => '2023-11-30T21:05:52.000000Z'
			],
			[
				'transactionsid' => 2,
				'accountid_from' => 5,
				'accountid_to' => 6,
				'amount_from' => '10.0000000000000000',
				'amount_to' => '10.9000000000000000',
				'exchange_rate' => '1.0900000000000000',
				'created_at' => '2023-11-30T21:05:51.000000Z',
				'updated_at' => '2023-11-30T21:05:51.000000Z'
			]
		];

		$filtered_transactions = array_slice($all_transactions, $offset, $limit);

		return ['transactions' => $filtered_transactions];
	}
}
