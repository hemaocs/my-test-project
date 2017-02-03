<?php

namespace Appsolute\Backend\Classes\Slim\Http;

class Request extends \Slim\Http\Request
{

    public function getPathInfo()
    {
    	$scriptName = "";
    	$folderName = FOLDER_NAME;
    	if(!empty($folderName)) {
    		$scriptName = $this->env['PATH_INFO'];
    	} else {
    		$scriptName = $this->env['SCRIPT_NAME'];
    	}
        return $scriptName;
    }

}