<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientsAccount extends Model {
	protected $table = 'clients_accounts';
	protected $primaryKey = null;
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = ['clientid', 'accountid'];

	public function account(): BelongsTo {
		return $this->belongsTo(Account::class, 'accountid', 'accountid');
	}
}
