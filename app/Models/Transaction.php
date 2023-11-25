<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

	use HasFactory;

	protected $table = 'transactions';

	protected $primaryKey = 'transactionsid';

	protected $fillable = ['accountid_from', 'accountid_to', 'amount_from', 'amount_to', 'exchange_rate'];

	public function accountidFrom() {
		return $this->belongsTo(Account::class, 'accountid_from', 'accountid');
	}

	public function accountidTo() {
		return $this->belongsTo(Account::class, 'accountid_to', 'accountid');
	}
}
