<?php

namespace Appsolute\Backend\Controllers\Products;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Products;
use Appsolute\Backend\Models\Categories;
use Appsolute\Backend\Models\Season;
use Appsolute\Backend\Classes\Utility;

Class GetController extends Controllers\Controller {

	public function getList(){	
		$products = new Products($this->configManager('Database'));
		$categories = new Categories($this->configManager('Database'));
		$folderName = FOLDER_NAME;
		$folderName = (!empty($folderName)) ? SERVER_URL.$folderName."/" : "";
		$this->app->lang_url != "" ? $folderName_lang = $folderName.$this->app->lang_url."/" : $folderName_lang = $folderName;
		$filter = array();		
		if(isset($this->args['type'])) {
			if(!empty($this->args['type'])) {
				$filter['filterby'] = $this->args['type'];
			}
		}
		$this->args['type']   = $filter;
		$this->data['products'] = $products->getAll($filter);
		$this->data['filter'] = $filter; 
		$this->data['categories'] = $categories->getAll();
		$this->data['base_url'] = $folderName_lang;
		
	}
	
	public function add(){
		$res = $result = array();
		$categories = new Categories($this->configManager('Database'));
		$utility = new Utility\Utility();
		$months = $utility->doGetMonths();
		if (!empty($months)) {
			$i = 1;
            foreach ($months as $key => $value) {
            	$res['month_no'] = $i;
            	$res['month_en'] = $key;
            	$res['month_fr'] = $value;
            	$i++;
            	$result[] = $res;
            }
		}
		$this->data['months'] = $result;
		$this->data['categories'] = $categories->getAll();
		$this->data['parentChildCats'] = $categories->getParentChildCats();
	}

	public function updateView() {
		$res = $result = array();
		$product = new Products($this->configManager('Database'));
		$categories = new Categories($this->configManager('Database'));
		$utility = new Utility\Utility();
		$months = $utility->doGetMonths();
		if (!empty($months)) {
            $i = 1;
            foreach ($months as $key => $value) {
            	$res['month_no'] = $i;
            	$res['month_en'] = $key;
            	$res['month_fr'] = $value;
            	$i++;
            	$result[] = $res;
            }
		}
		$this->data['months'] = $result;
		$this->data['products'] = $product->findOneById($this->args['id']);
		$this->data['category'] = $product->getAllProCats($this->args['id']);
		$this->data['categories'] = $categories->getAll();
		$this->data['parentChildCats'] = $categories->getParentChildCats();
	}
}