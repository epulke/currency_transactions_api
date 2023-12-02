<?php

use App\Http\Controllers\TransactionReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/accounts/{clientid}', [AccountController::class, 'getAccountsByClientid']);

Route::post('/transactions', [TransactionController::class, 'makeTransaction']);

Route::get('/account_transactions/{accountid}', [TransactionReportController::class, 'getTransactionsByAccountid']);