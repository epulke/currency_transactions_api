<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('accounts', function (Blueprint $table) {
			$table->id('accountid');
			$table->string('account_number')->unique();
			$table->unsignedBigInteger('currencyid');
			$table->decimal('balance', 25, 16);
			$table->timestamps();

			$table->foreign('currencyid')->references('currencyid')->on('currencies');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('accounts');
	}
}
