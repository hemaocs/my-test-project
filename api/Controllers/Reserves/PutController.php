<?php

namespace Appsolute\Api\Controllers\Reserves;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Api\Controllers;
use Appsolute\Api\Models\Reserves;
use Appsolute\Api\Models\Validation\ValidationException;

Class PutController extends Controllers\Controller {

	// Update shopping item details
	public function updateProdDetails(){ 
		$reserve = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$this->data = $reserve->updateProdDetails( $this->args['userId'], $this->args['reserveId'], $this->args['itemId'], $this->args['type'], $values );
		$this->message = "Successfully updated the quantity of the product.";
		$this->statusCode = 201;
	}
	
	// Modify Reserve Users
	public function updateResUsers(){ 
		$reserve = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$this->data = $reserve->updateResUsers( $this->args['userId'], $this->args['reserveId'], $values );
		$this->message = "Successfully modified the users in the reserve.";
		$this->statusCode = 201;
	}

}