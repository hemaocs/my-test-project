<?php

namespace Appsolute\Api\Classes\Logs;

use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Formatter\JsonFormatter;

Class Exceptions extends Logs {

	protected function __construct(){
		$this->formatter = new JsonFormatter();
		$this->handler = new StreamHandler( API_EXCEPTION_FILE, Logger::CRITICAL);
		$this->handler->setFormatter($this->formatter);
		$this->logger = new Logger('exceptions', array($this->handler));
		$this->processor = new WebProcessor();
		$this->logger->pushProcessor($this->processor);
		ErrorHandler::register($this->logger, array(), Logger::CRITICAL);
	}

}