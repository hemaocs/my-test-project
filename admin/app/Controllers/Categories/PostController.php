<?php

namespace Appsolute\Backend\Controllers\Categories;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Categories;

Class PostController extends Controllers\Controller {

	public function add() {
		$categories = new Categories($this->configManager('Database'));
		$this->data['retry'] = $this->app->request->post();
		$this->data['cats'] = $categories->getParentCategories();
		$categories->insert($this->app->request->post());
		$this->app->errors += $categories->getErrors();
	}

	public function uploadImage() {
		$categories = new Categories($this->configManager('Database'));
		$this->data['upload_image'] = $categories->doUploadImage();
	}

	public function cropImage() {
		$categories = new Categories($this->configManager('Database'));
		$this->data['upload_image'] = $categories->doUploadCropImage($this->app->request->post());	
	}

}