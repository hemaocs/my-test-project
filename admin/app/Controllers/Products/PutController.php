<?php

namespace Appsolute\Backend\Controllers\Products;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Products;
use Appsolute\Backend\Models\Categories;
use Appsolute\Backend\Models\Season;
use Appsolute\Backend\Classes\Utility;

Class PutController extends Controllers\Controller {

	public function updateProduct() {
		$res = $result = array();
		$products = new Products($this->configManager('Database'));
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
		$this->data['category'] = $products->getCatByProduct($this->args['id']);
		$this->data['products'] = $products->findOneById($this->args['id']);
		$products->update($this->args['id'], $this->app->request->post());
		$this->app->errors += $products->getErrors();
	}

}