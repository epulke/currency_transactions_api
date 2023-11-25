<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
	use HasFactory;

	protected $primaryKey = 'accountid';

	protected $fillable = ['accountid', 'account_number', 'currencyid', 'balance'];

	public function clients(): HasMany {
		return $this->hasMany(ClientsAccount::class, 'accountid', 'accountid');
	}

	public function currency(): BelongsTo {
		return $this->belongsTo(Currency::class, 'currencyid', 'currencyid');
	}
}
