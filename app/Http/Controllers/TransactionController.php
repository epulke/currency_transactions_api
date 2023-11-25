<?php

namespace App\Http\Controllers;

use App\Services\ExchangeRateService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TransactionController extends Controller {

	private ExchangeRateService $exchange_rate_service;

	public function __construct(ExchangeRateService $exchange_rate_service) {
		$this->exchange_rate_service = $exchange_rate_service;
	}

	public function makeTransaction(Request $request): JsonResponse {
		try {
			$request->validate([
				'accountid_from' => 'required|exists:accounts,accountid',
				'accountid_to' => 'required|exists:accounts,accountid',
				'amount' => 'required|numeric',
				'currency' => 'required|exists:currencies,currency_name'
			]);
		} catch (ValidationException $e) {
			return response()->json(['error' => $e->validator->errors()], 422);
		}

		$account_from = Account::with(['currency' => function ($query) {
			$query->select('currencyid', 'currency_name');
		}])
			->select('accountid', 'balance', 'currencyid')
			->find($request->input('accountid_from'));

		$account_to = Account::with(['currency' => function ($query) {
			$query->select('currencyid', 'currency_name');
		}])
			->select('accountid', 'currencyid')
			->find($request->input('accountid_to'));

		if ($account_to['currency']['currency_name'] !== $request->input('currency')) {
			$accountid_to = $request->input('accountid_to');
			$currency = $request->input('currency');

			return response()->json([
				'message' => "Invalid 'currency': '{$currency}' or 'accountid_to': '{$accountid_to}' parameters."
			], 201);
		}

		if ($account_from['balance'] < $request->input('amount')) {
			$accountid_from = $request->input('accountid_from');

			return response()->json([
				'message' => "There are not enough funds in the account with 'accountid': '{$accountid_from}'."
			], 201);
		}

		$exchange_rate = $this->exchange_rate_service->getExchangeRate(
			$account_from['currency']['currency_name'], $account_to['currency']['currency_name']
		);

		try {
			DB::transaction(function () use ($request, $exchange_rate) {
				$transaction = $this->create($request, $exchange_rate);
				$this->updateAccountBalance($transaction);
			});
		} catch (Exception $e) {
			return response()->json(['error' => [
				'code' => $e->getCode(),
				'message' => $e->getMessage(),
			]], 500);
		}

		return response()->json(['message' => 'Funds transferred successfully.'], 201);
	}

	private function validateRequest(Request $request) {
		try {
			$request->validate([
				'accountid_from' => 'required|exists:accounts,accountid',
				'accountid_to' => 'required|exists:accounts,accountid',
				'amount' => 'required|numeric',
				'currency' => 'required|exists:currencies,currency_name'
			]);
		} catch (ValidationException $e) {
			return response()->json(['error' => $e->validator->errors()], 422);
		}
	}

//	private function checkAccountCurrency(Account $account_to, Request $request) {
//		if ($account_to['currency']['currency_name'] !== $request->input('currency')) {
//			$accountid_to = $request->input('accountid_to');
//			$currency = $request->input('currency');
//
//			return response()->json([
//				'message' => "Invalid 'currency': '{$currency}' or 'accountid_to': '{$accountid_to}' parameters."
//			], 201);
//		}
//	}
//
//	private function checkAccountBalance(Account $account_from, Request $request) {
//		if ($account_from['balance'] < $request->input('amount')) {
//			$accountid_from = $request->input('accountid_from');
//
//			return response()->json([
//				'message' => "There are not enough funds in the account with 'accountid': '{$accountid_from}'."
//			], 201);
//		}
//	}

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
