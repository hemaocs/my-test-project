<?php

namespace Appsolute\Cron\Controllers;

use Slim\Slim;
use ReflectionClass;
use Appsolute\Cron\Classes;

Abstract Class Controller {

	protected $app;
	protected $message;
	protected $data 	  	= array();
	protected $meta			= array();
	protected $statusCode 	= 200;
	protected $responseCode = 1;
	protected $args;

	public function __construct( $method, $args = null ) {
		//Check if the called method exists
		if(!method_exists($this, $method)){
			throw new \Exception("The method {$method} doesn't exist.");
		}

		//Set arguments
		if(!empty($args) && is_array($args)) {
			$this->args = $args;
		} elseif(!empty($args) && !is_array($args)){
			throw new \Exception("Arguments must be in a array.");
		}

		//Access to the Slim app instance
		$this->app = Slim::getInstance();

		//Register the folder name in the app
		$this->app->view->set('dirName', FOLDER_NAME);

		//Define errors variable
		$this->app->errors = array();

		//Call the method
		$this->$method();
	}

	protected function configManager($name) {
		$name = ucfirst($name);
		$namespacedClass = '\\Appsolute\\Config\\'.$name;
		if(class_exists($namespacedClass)){
			return (new ReflectionClass($namespacedClass))->newInstance();
		} else {
			throw new \Exception("Class {$name} doesn't exist.");
		}
	}

	public function send() {
		echo json_encode(array(
			'response_code' => $this->responseCode,
			'message' => $this->message,
			'meta' => $this->meta,
			'data' => $this->data
		));
		$this->app->response->setStatus($this->statusCode);
		return $this->statusCode;
	}

}