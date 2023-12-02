<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder {
	public function run(array $currencies) {
		foreach ($currencies as $currency) {
			DB::table('currencies')->insert([
				'currency_name' => $currency,
			]);
		}
	}
}
