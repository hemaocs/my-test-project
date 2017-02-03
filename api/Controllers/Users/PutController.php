<?php

namespace Appsolute\Api\Controllers\Users;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Api\Controllers;
use Appsolute\Api\Models\Users;
use Appsolute\Api\Models\Validation\ValidationException;

Class PutController extends Controllers\Controller {

	// Update User
	public function updateUser(){
		$user = new Users($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$this->data['user'] = $user->update( $this->args['id'], $values );
		$this->message = "The user has been successfully updated.";
	}

}