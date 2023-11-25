<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class ClientsAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
	public function run() {
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
