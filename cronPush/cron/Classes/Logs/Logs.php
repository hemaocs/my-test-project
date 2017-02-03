<?php

namespace Appsolute\Cron\Classes\Logs;

use Monolog\Handler\SwiftMailerHandler;
use Monolog\Formatter\HtmlFormatter;

Abstract Class Logs {

	protected static $instance;
	protected $logger;
	protected $handler;
	protected $formatter;

	protected function __construct() {}

	final private function __clone() {}

	final public static function register() {
		if(!(self::$instance instanceof self)) {
			$trueSelf = get_called_class(); 
			self::$instance = new $trueSelf();
		}
		return self::$instance;
	}

	final public static function getInstance(){
		$class = get_called_class();
		return new $class;
	}

	public function exceptionFormatter( \Exception $e ){
		return array('code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine());
	}

	public function getLogger(){
		return $this->logger;
	}

	public function getHandler(){
		return $this->handler;
	}

	public function getProcessor(){
		return $this->processor;
	}

	public function getFormatter(){
		return $this->formatter;
	}

	public function setLogger( $logger ){
		$this->logger = $logger;
	}

	public function setHandler( $handler ){
		$this->handler = $handler;
	}

	public function setProcessor( $processor ){
		$this->processor = $processor;
	}

	public function setFormatter( $formatter ){
		$this->formatter = $formatter;
	}

}