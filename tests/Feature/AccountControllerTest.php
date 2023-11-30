<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccountControllerTest extends GeneralTestCase {

	public function testValidClientidForGetAccountsByClientid() {
		$clientid = 1;

		$response = $this->get("/api/accounts/{$clientid}");

		$response->assertStatus(200);
		$response->assertJsonStructure([
			'*' => [
				'accountid',
				'account_number',
				'currency_name',
				'balance',
			],
		]);
	}

	public function testInvalidClientidForGetAccountsByClientid() {
		$invalid_clientids = [
			'clientid_does_not_exist' => [
				'clientid' => 999,
				'expected_error' => ['error' => ['clientid' => ['The selected clientid is invalid.']]]
			],
			'clientid_is_not_integer' => [
				'clientid' => 'abc',
				'expected_error' => ['error' => ['clientid' => ['The clientid must be an integer.']]]
			],
			'clientid_is_missing' => [
				'clientid' => ' ',
				'expected_error' => ['error' => ['clientid' => ['The clientid field is required.']]]
			]
		];

		foreach ($invalid_clientids as $invalid_clientid) {
			$response = $this->get("/api/accounts/{$invalid_clientid['clientid']}");

			$response->assertStatus(422);
			$response->assertExactJson($invalid_clientid['expected_error']);
		}
	}
}