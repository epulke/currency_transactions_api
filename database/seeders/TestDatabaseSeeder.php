<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder {

	public function run() {
		$currencies = ['EUR', 'USD'];
		$number_of_accounts = 20;
		$number_of_clients = 5;

		(new CurrenciesSeeder())->run($currencies);
		(new TestAccountsSeeder())->run($number_of_accounts);
		(new TestClientsAccountsSeeder())->run($number_of_clients);
		(new TestTransactionsSeeder())->run();
	}
}
