<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Currency;
use App\Services\ExchangeRateService;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDatabaseSeeder extends Seeder {

	public function run() {
		$currencies = ['EUR', 'USD', 'GBP'];
		$number_of_accounts = 20;
		$number_of_clients = 5;

		(new CurrenciesSeeder())->run($currencies);
		(new AccountsSeeder())->run($number_of_accounts);
		(new ClientsAccountsSeeder())->run($number_of_clients);
	}
}
