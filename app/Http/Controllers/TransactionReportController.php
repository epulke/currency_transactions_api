<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionsByAccountRequest;
use App\Models\Transaction;
use Exception;

class TransactionReportController extends Controller {
	public function getTransactionsByAccountid(TransactionsByAccountRequest $request, $accountid) {
		try {
			$transactions = Transaction::where('accountid_from', $accountid)
				->orWhere('accountid_to', $accountid)
				->orderByDesc('created_at')
				->offset($request->input('offset', 0))
				->limit($request->input('limit', 10))
				->get();
		} catch (Exception $e) {
			return response()->json(['error' => $e->getMessage()], 500);
		}

		return response()->json(['transactions' => $transactions], 200);
	}
}
