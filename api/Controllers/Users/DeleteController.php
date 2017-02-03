<?php

namespace Appsolute\Api\Controllers\Users;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Api\Controllers;
use Appsolute\Api\Models\Users;
use Appsolute\Api\Models\Validation\ValidationException;

Class DeleteController extends Controllers\Controller {

	// Delete User
	public function deleteUser(){
		$user = new Users($this->configManager('Database'));
		$this->data['user'] = $user->delete( $this->args['id'] );
		$this->data = array();
		$this->message = "The user has been successfully deleted.";
	}

}