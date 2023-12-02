<?php

namespace App\Exceptions;

use Exception;

class ExchangeRateServiceException extends Exception {
	public function __construct(string $message, $code = 503, Exception $previous = null) {
		$message = 'Error with exchangerate.host: ' . $message;
		parent::__construct($message, $code, $previous);
	}
}
