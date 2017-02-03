<?php

namespace Appsolute\Backend\Controllers;

use Slim\Slim;
use ReflectionClass;
use Appsolute\Backend\Classes;

Abstract Class Controller {

	public 	  $result		= array();
	protected $app;
	protected $data 	  	= array();
	protected $statusCode 	= 200;
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

	public function send($template) {
		if(!is_array($this->data)) {
			throw new \Exception("You must pass a array of data to your view.");
		}
		$this->data['flash'] = $this->app->flashData();
		
		$folderName = FOLDER_NAME;
		$folderName = (!empty($folderName)) ? SERVER_URL.$folderName : "";
		$this->app->lang_url != "" ? $folderName_lang = $folderName."/".$this->app->lang_url : $folderName_lang = $folderName;
		
		$data = array_merge(
				array("session" => $_SESSION),
				$this->data,
				array("dirName" 	 => $folderName),
				array("dirName_lang" => $folderName_lang)
		);		
		$this->app->render($template, $data);
		return $this->statusCode;
	}

}