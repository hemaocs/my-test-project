<?php

namespace Appsolute\Api\Controllers\Reserves;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Api\Controllers;
use Appsolute\Api\Models\Reserves;
use Appsolute\Api\Models\Validation\ValidationException;

Class PostController extends Controllers\Controller {

	// Add New Reserve
	public function newReserve(){ 
		$event = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$this->data = $event->insert( $this->args['userId'], $values );
		$this->message = "Successfully created the reserve.";
		$this->statusCode = 201;
	}

	// Accept Reserve
	public function acceptReserve(){ 
		$event = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$result = $event->acceptReserve( $this->args['userId'], $this->args['reserveId'] );
		$this->data = $result['data'];
		$this->message = "Successfully accepted the reserve invitation.";
		$this->statusCode = 201;
	}

	// Refuse Reserve
	public function refuseReserve(){ 
		$event = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$result = $event->refuseReserve( $this->args['userId'], $this->args['reserveId'] );
		$this->data = $result['data'];
		$this->message = "Successfully refused the reserve invitation.";
		$this->statusCode = 201;
	}
	
	// Add Reserve Products
	public function newResProduct(){ 
		$reserve = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$this->data = $reserve->newResProduct( $this->args['userId'], $this->args['reserveId'], $this->args['type'], $values );
		$this->message = "Successfully added the products.";
		$this->statusCode = 201;
	}

	// Add Reserve Recipe
	public function newResRecipe(){ 
		$reserve = new Reserves($this->configManager('Database'));
		$this->data = $reserve->newResRecipe( $this->args['userId'], $this->args['reserveId'], $this->args['recipeId'] );
		$this->message = "Successfully added the recipe.";
		$this->statusCode = 201;
	}

	// Add/Remove Mandatory
	public function changeMandatory(){ 
		$reserve = new Reserves($this->configManager('Database'));
		$this->data = $reserve->changeMandatory( $this->args['userId'], $this->args['reserveId'], $this->args['prodId'], $this->args['type'] );
		if ($this->args['type'] == 'needed') {
		    $this->message = "Successfully removed from mandatory.";
		} else {
            $this->message = "Successfully added to mandatory.";
		}
		$this->statusCode = 201;
	}

	// Add to cart
	public function addShopItem(){ 
		$need = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$result = $need->addShopItem( $this->args['userId'], $this->args['reserveId'], $this->args['needId'] );
		$this->data = $result['data'];
		$this->message = "Successfully added to the cart.";
		$this->statusCode = 201;
	}

	// Delete Reserve Products
	public function removeShopItem(){ 
		$need = new Reserves($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$result = $need->removeShopItem( $this->args['userId'], $this->args['reserveId'], $this->args['prodId'], $this->args['type'], $values );
		$this->data = $result['data'];
		$this->message = "Successfully removed the product from cart.";
		$this->statusCode = 201;
	}

}