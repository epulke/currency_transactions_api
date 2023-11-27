<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest {
	public function rules(): array {
		return [
			'clientid' => 'required|integer|exists:clients_accounts,clientid'
		];
	}

	public function validationData(): array {
		return array_merge($this->all(), [
			'clientid' => $this->route('clientid'),
		]);
	}
}
