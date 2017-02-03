<?php

namespace Appsolute\Backend\Controllers\Users;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Users;
 
Class GetController extends Controllers\Controller { 

	public function getList(){
		$users = new Users($this->configManager('Database'));
		$folderName = FOLDER_NAME;
		$folderName = (!empty($folderName)) ? SERVER_URL.$folderName."/" : "";
		$this->app->lang_url != "" ? $folderName_lang = $folderName.$this->app->lang_url."/" : $folderName_lang = $folderName;
		/*$limit = 20;
		$start = 0;
		if(isset($this->args['limit'])) {
			$limit = (int) $this->args['limit'];
		}
		if(is_numeric($this->args['page'])) {
			$start = ($this->args['page'] - 1) * $limit;
		}*/

		$filter = array();		
		if(isset($this->args['type']) && isset($this->args['keyword'])) {
			if(!empty($this->args['type']) && $this->args['keyword']!='') {
				$filter['filterby'] = $this->args['type'];
				$filter['keyword'] = $this->args['keyword'];
			}
		}

		$this->data['users'] = $users->getAll($filter);
		//$this->data['pagination'] = $users->doGetPagination($this->args['page'],$start,$limit,$filter,$folderName_lang);
		$this->data['base_url'] = $folderName_lang;
		$this->data['filter'] = $filter;
		//$this->data['page'] = $this->args['page'];
		//$this->data['limit'] = $limit;
	}

	public function updateView() {
		$users = new Users($this->configManager('Database'));
		$this->data['users'] = $users->findOneById($this->args['id']);
	}

	public function add() {
		//
	}
}