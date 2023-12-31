<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('transactions', function (Blueprint $table) {
			$table->id('transactionsid');
			$table->unsignedBigInteger('accountid_from');
			$table->unsignedBigInteger('accountid_to');
			$table->decimal('amount_from', 25, 16);
			$table->decimal('amount_to', 25, 16);
			$table->decimal('exchange_rate', 17, 16);
			$table->timestamps();

			$table->foreign('accountid_from')->references('accountid')->on('accounts');
			$table->foreign('accountid_to')->references('accountid')->on('accounts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('transactions');
	}
}
