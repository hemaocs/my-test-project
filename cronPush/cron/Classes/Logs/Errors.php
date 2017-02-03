<?php

namespace Appsolute\Cron\Classes\Logs;

use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Formatter\JsonFormatter;

Class Errors extends Logs {

	protected function __construct(){
		$this->formatter = new JsonFormatter();
		$this->handler = new StreamHandler( API_ERROR_FILE, Logger::ERROR);
		$this->handler->setFormatter($this->formatter);
		$this->logger = new Logger('error', array($this->handler));
		$this->processor = new WebProcessor();
		$this->logger->pushProcessor($this->processor);
	}

}