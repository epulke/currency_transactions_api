<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestAccountsSeeder extends Seeder {
	public function run(int $number_of_accounts) {
		foreach (range(1, $number_of_accounts) as $index) {
			DB::table('accounts')->insert([
				'account_number' => '123456789'.$index,
				// In case more than 2 currencies are passed for the test, this logic needs to be changed.
				'currencyid' => $index % 2 ? 1 : 2,
				'balance' => 1000,
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}
	}
}
