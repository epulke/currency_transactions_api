<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Currency;
use App\Services\ExchangeRateService;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
	public function run() {
		// Populate 'currency' table.
		$currencies = (new ExchangeRateService())->getCurrencies();

		foreach ($currencies as $currency) {
			DB::table('currencies')->insert([
				'currency_name' => $currency,
			]);
		}

		// Populate 'accounts' table.
		$faker = Factory::create();
		$currencyids = Currency::pluck('currencyid');

		foreach (range(1, 100) as $index) {
			DB::table('accounts')->insert([
				'account_number' => $faker->unique()->bankAccountNumber,
				'currencyid' => $faker->randomElement($currencyids),
				'balance' => $faker->randomFloat(2, 0, 10000),
				'created_at' => now(),
				'updated_at' => now(),
			]);
		}

		// Populate 'clients_accounts' table.
		$clientids = range(1, 50);
		$accountids = Account::pluck('accountid')->toArray();

		foreach ($accountids as $accountid) {
			DB::table('clients_accounts')->insert([
				'clientid' => $clientids[array_rand($clientids)],
				'accountid' => $accountid
			]);
		}
	}
}
