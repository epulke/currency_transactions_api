<?php

namespace Database\Seeders;

use \App\Models\Currency;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
	public function run() {
		$faker = Factory::create();

		$currencyIds = Currency::pluck('currencyid');

		foreach (range(1, 100) as $index) {
			DB::table('accounts')->insert([
				'account_number' => $faker->unique()->bankAccountNumber,
				'currencyid' => $faker->randomElement($currencyIds),
				'balance' => $faker->randomFloat(2, 0, 10000),
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}
	}
}
