<?php

namespace Appsolute\Api\Models;

use R;
use Appsolute\Config\ConfigManagerInterface;

Abstract Class Database {

	protected $errors = array();
	protected $config;

	public function __construct(ConfigManagerInterface $config = null) {
		try {
			$this->config = $config;
			if(!R::testConnection()){
				R::setup('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
				R::freeze(true);
				R::ext('xdispense', function( $type ){ 
			        return R::getRedBean()->dispense( $type ); 
			    });
			}
		} catch(\Exception $e){
			throw new \Exception("Cannot access database : '{$e->getMessage()}'", 500);
		}
		
	}

	public function __destruct() {
		R::close();
	}

	public function setConfig($config){
		$this->config = $config;
	}

	public function getErrors(){
		return $this->errors;
	}

}