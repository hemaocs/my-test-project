<?php

namespace Appsolute\Backend\Controllers\Users;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Users;

Class PostController extends Controllers\Controller {

	public function add() {
		$users = new Users($this->configManager('Database'));
		$this->data['retry'] = $this->app->request->post();
		$password = trim($this->app->request->post('password'));
		$passwordConfirm = trim($this->app->request->post('password-confirm'));
		if((!empty($password)) && (!empty($passwordConfirm))) {
			if($password != $passwordConfirm){
				$this->app->errors += ["Password and the confirmation are different."];
				return;
			}
		}
		
		$users->insert($this->app->request->post());
		$this->app->errors += $users->getErrors();
	}

}