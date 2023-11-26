<?php

namespace App\Exceptions;

use Exception;

class NotEnoughFundsException extends Exception {
	public function __construct(int $accountid_from, $code = 201, Exception $previous = null) {
		$message = "There are not enough funds in the account with 'accountid': '{$accountid_from}'.";
		parent::__construct($message, $code, $previous);
	}
}
