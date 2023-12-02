<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest {
	public function rules(): array {
		return [
			'accountid_from' => 'required|integer|exists:accounts,accountid',
			'accountid_to' => 'required|integer|exists:accounts,accountid',
			'amount' => 'required|numeric',
			'currency' => 'required|string|exists:currencies,currency_name'
		];
	}
}
