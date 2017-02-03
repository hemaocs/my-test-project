<?php

namespace Appsolute\Api\Controllers\Reserves;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Api\Controllers;
use Appsolute\Api\Models\Reserves;
use Appsolute\Api\Models\Validation\ValidationException;

Class DeleteController extends Controllers\Controller {

	// Delete Reserve Products
	public function deleteResProduct(){
		$reserve = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$this->data = $reserve->deleteResProduct( $this->args['userId'], $this->args['reserveId'], $this->args['type'], $values );
		$this->message = "Successfully removed the products.";
	}

	// Remove shopping item from cart
	public function removeShopItem(){ 
		$need = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$result = $need->removeShopItem( $this->args['userId'], $this->args['reserveId'], $this->args['prodId'], $this->args['type'], $values );
		$this->data = $result['data'];
		$this->message = "Successfully removed the product from cart.";
		$this->statusCode = 201;
	}

}