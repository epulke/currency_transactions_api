<?php

namespace App\Http\Controllers;

use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\ClientsAccount;

class AccountController extends Controller
{
	public function getAccountsByClient(Request $request, $clientId): JsonResponse {
		$accounts = ClientsAccount::where('clientid', $clientId)->with('account')->get();

		return response()->json($accounts, 200);
	}
}
