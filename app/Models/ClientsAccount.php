<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientsAccount extends Model
{
    use HasFactory;

	protected $table = 'clients_accounts';
	protected $primaryKey = null; // Assuming this is a pivot table with composite primary key
	public $incrementing = false; // Assuming this is a pivot table with composite primary key
	public $timestamps = false; // If the pivot table does not have timestamps

	protected $fillable = ['clientid', 'accountid'];

	public function account() {
		return $this->belongsTo(Account::class, 'accountid', 'accountid');
	}
}
