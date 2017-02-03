<?php

namespace Appsolute\Backend\Controllers\Users;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Users;

Class DeleteController extends Controllers\Controller {

	public function deleteUser() {
		$users = new Users($this->configManager('Database'));
		$users->delete($this->args['id']);
		$this->app->errors = $users->getErrors();
		if($this->args['id'] == $_SESSION['users']['id']){
			unset($_SESSION['users']);
			$this->app->deleteCookie('isAdmin');
		}
	}

}