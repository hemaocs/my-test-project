<?php

namespace Appsolute\Api\Classes\Authorization\Types;

use R;
use Appsolute\Config;
use Appsolute\Api\Classes\Authorization\Authorization;
use Appsolute\Api\Models\Clients;

Class Basic extends Authorization {

	public function process(){
		if($this->connectionType == "Basic"){
			$getallheaders = getallheaders();
			$auth = explode(':', base64_decode(explode(' ', $getallheaders['Authorization'])[1]));
			$user = $auth[0];
	    	$password = $auth[1];

	    	$basicAuth = (new Clients(new Config\Database()))->find($user, $password);
	    	if($basicAuth){
	    		$this->isValid = TRUE;
	    	} else {
	    		$this->isValid = FALSE;
	    	}
		}
		
	}

}