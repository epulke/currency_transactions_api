<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestClientsAccountsSeeder extends Seeder {
	public function run($number_of_clients) {
		$accountids = Account::pluck('accountid')->sortBy(function ($accountid) {
			return $accountid;
		})->toArray();
		$clientids = range(1, $number_of_clients);
		$clientCount = count($clientids);
		$clientid_key = 0;

		foreach ($accountids as $accountid) {
			DB::table('clients_accounts')->insert([
				'clientid' => $clientids[$clientid_key],
				'accountid' => $accountid
			]);

			// Set $clientid_key to next one, or start from zero again.
			$clientid_key = ($clientid_key + 1) % $clientCount;
		}
	}
}
