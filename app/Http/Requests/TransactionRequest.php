<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
	public function rules(): array {
		return [
			'accountid_from' => 'required|exists:accounts,accountid',
			'accountid_to' => 'required|exists:accounts,accountid',
			'amount' => 'required|numeric',
			'currency' => 'required|exists:currencies,currency_name'
		];
	}
}
