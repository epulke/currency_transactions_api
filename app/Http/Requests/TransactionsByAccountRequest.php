<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionsByAccountRequest extends FormRequest {
	public function rules(): array {
		return [
			'accountid' => 'required|integer|exists:accounts,accountid',
			'offset' => 'nullable|integer|min:0',
			'limit' => ['sometimes', 'integer', 'min:1', 'max:100', function ($attribute, $value, $fail) {
				if (is_null($value) || empty($value)) {
					$fail("The $attribute field must not be null.");
				}
			}]
		];
	}

	public function validationData(): array {
		return array_merge($this->all(), [
			'accountid' => $this->route('accountid'),
		]);
	}
}
