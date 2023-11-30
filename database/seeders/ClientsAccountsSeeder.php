<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientsAccountsSeeder extends Seeder {
	public function run($number_of_clients) {
		$clientids = range(1, $number_of_clients);
		$accountids = Account::pluck('accountid')->toArray();

		foreach ($accountids as $accountid) {
			DB::table('clients_accounts')->insert([
				'clientid' => $clientids[array_rand($clientids)],
				'accountid' => $accountid
			]);
		}
	}
}
