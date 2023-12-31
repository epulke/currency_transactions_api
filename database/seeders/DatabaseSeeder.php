<?php

namespace Database\Seeders;

use App\Services\ExchangeRateService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

	public function run() {
		$currencies = (new ExchangeRateService())->getCurrencies();
		$number_of_accounts = 100;
		$number_of_clients = 50;

		(new CurrenciesSeeder())->run($currencies);
		(new AccountsSeeder())->run($number_of_accounts);
		(new ClientsAccountsSeeder())->run($number_of_clients);
	}
}
