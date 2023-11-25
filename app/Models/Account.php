<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

	protected $primaryKey = 'accountid';

	protected $fillable = ['clientid', 'accountid'];

	public function clients() {
		return $this->hasMany(ClientsAccount::class, 'accountid', 'accountid');
	}
}
