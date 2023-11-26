<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use Illuminate\Http\JsonResponse;
use App\Models\ClientsAccount;

class AccountController extends Controller
{
	public function getAccountsByClientid(AccountRequest $request, $clientid): JsonResponse {
		$accounts = ClientsAccount::where('clientid', $clientid)
			->join('accounts', 'clients_accounts.accountid', '=', 'accounts.accountid')
			->join('currencies', 'accounts.currencyid', '=', 'currencies.currencyid')
			->select('accounts.accountid', 'accounts.account_number', 'currencies.currency_name', 'accounts.balance')
			->get();

		return response()->json($accounts, 200);
	}
}
