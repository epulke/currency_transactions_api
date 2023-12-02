<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
			'created_at' => Carbon::parse('2023-11-30 21:05:50')->format('Y-m-d H:i:s'),
			'updated_at' => Carbon::parse('2023-11-30 21:05:50')->format('Y-m-d H:i:s'),
		]);

		$rows = 1;

		while ($rows <= 5) {
			$time = '2023-11-30 21:05:5' . $rows;
			DB::table('transactions')->insert([
				'accountid_from' => 5,
				'accountid_to' => 6,
				'amount_from' => 10,
				'amount_to' => 10.9,
				'exchange_rate' => 1.09,
				'created_at' => Carbon::parse($time)->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::parse($time)->format('Y-m-d H:i:s'),
			]);

			$rows++;
		}
	}
}
