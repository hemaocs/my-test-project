<?php

namespace Appsolute\Backend\Controllers\Products;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Products;

Class DeleteController extends Controllers\Controller {

	public function deleteProduct() {
		$products = new Products($this->configManager('Database'));
		$products->delete($this->args['id']);
		$this->app->errors = $products->getErrors();
	}

}