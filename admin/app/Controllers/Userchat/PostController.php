<?php

namespace Appsolute\Backend\Controllers\Userchat;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Userchat;

Class PostController extends Controllers\Controller {

	public function add() {
		$users = new Userchat();
		$this->data['retry'] = $this->app->request->post();	
		//$users->insert($this->app->request->post());
		//$this->app->errors += $users->getErrors();
	}

}