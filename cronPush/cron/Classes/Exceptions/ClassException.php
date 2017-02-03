<?php

namespace Appsolute\Cron\Classes\Exceptions;

use Exception;

Class ClassException extends Exception {

	public function __construct($message = "", $code = 0, $statusCode = 400, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->statusCode = $statusCode;
	}

	public function getStatusCode() {
		return $this->statusCode;
	}

}