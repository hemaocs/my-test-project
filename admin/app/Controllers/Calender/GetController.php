<?php

namespace Appsolute\Backend\Controllers\Calender;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Calender;
use Appsolute\Backend\Classes\Utility;

Class GetController extends Controllers\Controller {
	public function getList(){	
		$calender = new Calender($this->configManager('Database'));
		$folderName = FOLDER_NAME;
		$folderName = (!empty($folderName)) ? SERVER_URL.$folderName."/" : "";
		$this->app->lang_url != "" ? $folderName_lang = $folderName.$this->app->lang_url."/" : $folderName_lang = $folderName;	
		$this->data['base_url'] = $folderName_lang;	
	}	
}