<?php

namespace Appsolute\Cron\Classes\Logs;

use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Formatter\JsonFormatter;

Class Debug extends Logs {

	protected function __construct(){
		$this->formatter = new JsonFormatter();
		$this->handler = new StreamHandler( API_DEBUG_FILE, Logger::DEBUG);
		$this->handler->setFormatter($this->formatter);
		$this->logger = new Logger('debug', array($this->handler));
		$this->processor = new WebProcessor();
		$this->logger->pushProcessor($this->processor);
	}

}