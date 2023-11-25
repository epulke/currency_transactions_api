<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller {

	public function makeTransaction(Request $request): JsonResponse {
		$request->validate([
			'accountid_from' => 'required|exists:accounts,accountid',
			'accountid_to' => 'required|exists:accounts,accountid',
			'amount' => 'required|numeric',
			'currency' => 'required|exists:currencies,currency_name'
		]);

		// Create a new transaction
		$transaction = new Transaction();
		$transaction->accountid_from = $request->input('accountid_from');
		$transaction->accountid_to = $request->input('accountid_to');
		$transaction->amount_from = $request->input('amount_from');

		// Calculate other details and set them here
		$transaction->amount_to = $request->input('amount_from'); // Example, you might need to calculate this based on your business logic
		$transaction->exchange_rate = 1.0; // Example, you might need to calculate this based on your business logic

		// Save the transaction to the database
		$transaction->save();

		return response()->json(['message' => 'Funds transferred successfully successfully'], 201);
	}
}
