<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$currencies = ['GBP', 'USD', 'EUR'];

		foreach ($currencies as $currency) {
			DB::table('currencies')->insert([
				'currency_name' => $currency,
			]);
		}
    }
}
