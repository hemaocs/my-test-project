<?php

namespace Appsolute\Backend\Controllers\Categories;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Categories;
use Appsolute\Backend\Models\Products;

Class GetController extends Controllers\Controller {

	public function getList(){	
		$categories = new Categories($this->configManager('Database'));
		$folderName = FOLDER_NAME;
		$folderName = (!empty($folderName)) ? SERVER_URL.$folderName."/" : "";
		$this->app->lang_url != "" ? $folderName_lang = $folderName.$this->app->lang_url."/" : $folderName_lang = $folderName;
		$this->data['categories'] = $categories->getAll();
		$this->data['base_url'] = $folderName_lang;
	}

	public function add() {
		$categories = new Categories($this->configManager('Database'));
		$this->data['cats'] = $categories->getParentCategories();
	}

	public function updateView() {
		$category = new Categories($this->configManager('Database'));
		$this->data['categories'] = $category->findOneById($this->args['id']);
		$this->data['cats'] = $category->getParentCategories();
	}

	public function getCategoryDetails(){
		$categories = new Categories($this->configManager('Database'));
		$products 	= new Products($this->configManager('Database'));
		$folderName = FOLDER_NAME;
		$folderName = (!empty($folderName)) ? SERVER_URL.$folderName."/" : "";
		$this->app->lang_url != "" ? $folderName_lang = $folderName.$this->app->lang_url."/" : $folderName_lang = $folderName;
		$this->data['categories'] = $categories->findOneById($this->args['id']);
		$this->data['products'] = $products->getProdByCat($this->args['id']);
		$this->data['base_url'] = $folderName_lang;
	}
	
}