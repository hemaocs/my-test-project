<?php

namespace Appsolute\Api\Classes\Authorization;

Abstract Class Authorization implements AuthorizationInterface {

	protected $routeIdentifier;
	protected $connectionType;
	protected $isValid;

	public function __construct(){
		$this->connectionType();
	}

	abstract public function process();

	public function isValid(){
		return $this->isValid;
	}

	private function connectionType(){
		$getallheaders = getallheaders();
        if(isset($getallheaders['Authorization']) && !empty($getallheaders['Authorization'])){
            $authorization = explode(' ', $getallheaders['Authorization']);
            $this->connectionType = trim($authorization[0]);
        }
        return $this->connectionType;
	}

	public function getConnectionType(){
		return $this->connectionType;
	}

	public function setRouteIdentifier( $routeIdentifier ){
		$this->routeIdentifier = $routeIdentifier;
	}

	public function getRouteIdentifier(){
		return $this->routeIdentifier;
	}

}