<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestTransactionsSeeder extends Seeder {
	public function run() {
		DB::table('transactions')->insert([
			'accountid_from' => 1,
			'accountid_to' => 2,
			'amount_from' => 10,
			'amount_to' => 10.9,
			'exchange_rate' => 1.09,
			'created_at' => now(),
			'updated_at' => now(),
		]);
	}
}
