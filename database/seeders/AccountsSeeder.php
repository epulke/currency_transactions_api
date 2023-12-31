<?php

namespace Database\Seeders;

use \App\Models\Currency;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsSeeder extends Seeder {
	public function run(int $number_of_accounts) {
		$faker = Factory::create();

		$currencyids = Currency::pluck('currencyid');

		foreach (range(1, $number_of_accounts) as $index) {
			DB::table('accounts')->insert([
				'account_number' => $faker->unique()->bankAccountNumber,
				'currencyid' => $faker->randomElement($currencyids),
				'balance' => $faker->randomFloat(2, 0, 10000),
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}
	}
}
