<?php

namespace Appsolute\Backend\Controllers\Categories;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Categories;

Class DeleteController extends Controllers\Controller {

	public function deleteCategory() {
		$categories = new Categories($this->configManager('Database'));
		$categories->delete($this->args['id']);
		$this->app->errors = $categories->getErrors();
	}

}