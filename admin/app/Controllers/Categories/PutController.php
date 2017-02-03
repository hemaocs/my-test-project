<?php

namespace Appsolute\Backend\Controllers\Categories;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Categories;

Class PutController extends Controllers\Controller {

	public function updateCategory() {
		$categories = new Categories($this->configManager('Database'));		
		$this->data['categories'] = $categories->findOneById($this->args['id']);
		$this->data['cats'] = $categories->getParentCategories();
		$categories->update($this->args['id'], $this->app->request->post());
		$this->app->errors += $categories->getErrors();
	}

}