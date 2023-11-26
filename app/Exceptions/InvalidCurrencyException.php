<?php

namespace App\Exceptions;

use Exception;

class InvalidCurrencyException extends Exception {
	public function __construct(string $currency, int $accountid_to, $code = 201, Exception $previous = null) {
		$message = "Invalid 'currency': '{$currency}' or 'accountid_to': '{$accountid_to}' parameters.";
		parent::__construct($message, $code, $previous);
	}
}
