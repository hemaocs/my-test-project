<?php

namespace Appsolute\Backend\Controllers\Users;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Users;

Class PutController extends Controllers\Controller {

	public function updateUser() { //print"<pre>";print_r($this->app->request->post()); exit();
		$users = new Users($this->configManager('Database'));		
		$this->data['users'] = $this->app->request->post();
		$this->data['users'] = $users->findOneById($this->args['id']);
		$password = trim($this->app->request->post('password'));
		$passwordConfirm = trim($this->app->request->post('password-confirm'));
		if((!empty($password))) {
			if (!empty($passwordConfirm)) {
                if($password != $passwordConfirm) {
					$this->app->errors += ["Password and the confirmation are different"];
					return;
				}
			} else {
                $this->app->errors += ["Please fill the confirm password."];
			    return;
			}
		}
		$users->update($this->args['id'], $this->app->request->post());
		$this->app->errors += $users->getErrors();
	}

}