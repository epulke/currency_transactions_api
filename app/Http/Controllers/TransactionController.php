<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCurrencyException;
use App\Exceptions\NotEnoughFundsException;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\Account;
use App\Services\ExchangeRateService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller {

	private ExchangeRateService $exchange_rate_service;

	public function __construct(ExchangeRateService $exchange_rate_service) {
		$this->exchange_rate_service = $exchange_rate_service;
	}

	public function makeTransaction(TransactionRequest $request): JsonResponse {
		try {
			$account_from = Account::with(['currency' => function ($query) {
				$query->select('currencyid', 'currency_name');
			}])
				->select('accountid', 'balance', 'currencyid')
				->findOrFail($request->input('accountid_from'));

			$account_to = Account::with(['currency' => function ($query) {
				$query->select('currencyid', 'currency_name');
			}])
				->select('accountid', 'currencyid')
				->findOrFail($request->input('accountid_to'));

			$this->checkAccountCurrency($account_to, $request);
			$this->checkAccountBalance($account_from, $request);

			$exchange_rate = $this->exchange_rate_service->getExchangeRate(
				$account_from['currency']['currency_name'], $account_to['currency']['currency_name']
			);

			DB::transaction(function () use ($request, $exchange_rate) {
				$transaction = $this->create($request, $exchange_rate);
				$this->updateAccountBalance($transaction);
			});
		} catch (InvalidCurrencyException | NotEnoughFundsException $e) {
			return response()->json(['error' => $e->getMessage()], $e->getCode());
		} catch (Exception $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}

		return response()->json(['message' => 'Funds transferred successfully.'], 201);
	}

	private function checkAccountCurrency(Account $account_to, Request $request): void {
		if ($account_to['currency']['currency_name'] !== $request->input('currency')) {
			$accountid_to = $request->input('accountid_to');
			$currency = $request->input('currency');

			throw new InvalidCurrencyException($currency, $accountid_to);
		}
	}

	private function checkAccountBalance(Account $account_from, Request $request) {
		if ($account_from['balance'] < $request->input('amount')) {
			$accountid_from = $request->input('accountid_from');

			throw new NotEnoughFundsException($accountid_from);
		}
	}

	private function create(Request $request, float $exchange_rate): Transaction {
		$transaction = new Transaction();
		$transaction->accountid_from = $request->input('accountid_from');
		$transaction->accountid_to = $request->input('accountid_to');
		$transaction->amount_from = round($request->input('amount')/$exchange_rate, 16);
		$transaction->amount_to = $request->input('amount');
		$transaction->exchange_rate = $exchange_rate;

		$transaction->save();

		return $transaction;
	}

	private function updateAccountBalance(Transaction $transaction): void {
		$account_from = Account::findOrFail($transaction->accountid_from);
		$account_from->balance -= $transaction->amount_from;
		$account_from->save();

		$account_to = Account::findOrFail($transaction->accountid_to);
		$account_to->balance += $transaction->amount_to;
		$account_to->save();
	}
}
