<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\ClientsAccount;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccountController extends Controller
{
	public function getAccountsByClient(Request $request, $clientId): JsonResponse {
//		$accounts = ClientsAccount::where('clientid', $clientId)->with('account')->get();
//
//		return response()->json($accounts, 200);

//		$accounts = Account::with(['currency' => function ($query) {
//			$query->select('currencyid', 'currency_name');
//		}])
//			->select('accountid', 'balance', 'currencyid')
//			->find(2);
//
//		var_dump($accounts->balance);

		$accounts = Account::with('currency')->find(1);
	var_dump($accounts['balance']);
		return response()->json($accounts, 200);

//		try {
//			$accounts = ClientsAccount::where('clientid', $clientId)->with('account')->get();
//			if (count($accounts) > 1) {
//				throw new HttpException(601, 'There are not enough funds in the account');
//			}
//			return response()->json($accounts, 200);
//		} catch (HttpException $e) {
//			return response()->json(['error' => [
//				'code' => $e->getStatusCode(),
//				'message' => $e->getMessage(),
//			]], $e->getStatusCode());
//		}


	}
}
