<?php

namespace Appsolute\Api\Controllers\Reserves;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Api\Controllers;
use Appsolute\Api\Models\Reserves;
use Appsolute\Api\Models\Users;

Class GetController extends Controllers\Controller {

	public function getCategories() {
		$categories = new Reserves($this->configManager('Database'));

		$l1 = ($this->args['page'] > 0) ? ($this->args['page']-1)*$this->args['limit'] : 1;
		$l2 = ($this->args['limit'] > 0) ? $this->args['limit'] : 25;

		$nbCategories = $categories->categoriesCount();
		$first = 1;
		$last = ceil($nbCategories/$l2);
		$next = ($this->args['page']<$last) ? $this->args['page']+1 : null;
		$prev = ($this->args['page']>1) ? $this->args['page']-1 : null;

        $result = $categories->getCategories($this->args['userId'], $l1, $l2);
	    $this->data['category'] = $result['data'];
        //print"<pre>";print_r($this->data['category']);exit();
        $this->meta = array(
			'first'	=> BASE_URL."api/categories/page/{$first}/limit/{$l2}",
			'last'	=> BASE_URL."api/categories/page/{$last}/limit/{$l2}",
			'next'	=> (!empty($next)) ? BASE_URL."api/categories/page/{$next}/limit/{$l2}" : null,
			'prev'	=> (!empty($prev)) ? BASE_URL."api/categories/page/{$prev}/limit/{$l2}" : null
		);

        $this->message = "Successfully retrieved the categories list.";
	}
    
    public function getProducts() {
		$products = new Reserves($this->configManager('Database'));

		$l1 = ($this->args['page'] > 0) ? ($this->args['page']-1)*$this->args['limit'] : 1;
		$l2 = ($this->args['limit'] > 0) ? $this->args['limit'] : 25;

		$nbProducts = $products->productsCount($this->args['catId']);
		$first = 1;
		$last = ceil($nbProducts/$l2);
		$next = ($this->args['page']<$last) ? $this->args['page']+1 : null;
		$prev = ($this->args['page']>1) ? $this->args['page']-1 : null;

        $result = $products->getProducts($this->args['userId'], $this->args['catId'], $l1, $l2);
	    $this->data['products'] = $result['data'];
        
        $this->meta = array(
			'first'	=> BASE_URL."api/categories/".$this->args['catId']."/products/page/{$first}/limit/{$l2}",
			'last'	=> BASE_URL."api/categories/".$this->args['catId']."/products/page/{$last}/limit/{$l2}",
			'next'	=> (!empty($next)) ? BASE_URL."api/categories/".$this->args['catId']."/products/page/{$next}/limit/{$l2}" : null,
			'prev'	=> (!empty($prev)) ? BASE_URL."api/categories/".$this->args['catId']."/products/page/{$prev}/limit/{$l2}" : null
		);

        $this->message = "Successfully retrieved the category products list.";
	}

	public function getAllProducts() {
		$products = new Reserves($this->configManager('Database'));

		$l1 = ($this->args['page'] > 0) ? ($this->args['page']-1)*$this->args['limit'] : 1;
		$l2 = ($this->args['limit'] > 0) ? $this->args['limit'] : 25;

		$nbAllProducts = $products->getproductCount();
		$first = 1;
		$last = ceil($nbProducts/$l2);
		$next = ($this->args['page']<$last) ? $this->args['page']+1 : null;
		$prev = ($this->args['page']>1) ? $this->args['page']-1 : null;

        $result = $products->getAllProducts($this->args['userId'],$l1, $l2);
	    $this->data['products'] = $result['data'];
        
        $this->meta = array(
			'first'	=> BASE_URL."api/categories/".$this->args['catId']."/products/page/{$first}/limit/{$l2}",
			'last'	=> BASE_URL."api/categories/".$this->args['catId']."/products/page/{$last}/limit/{$l2}",
			'next'	=> (!empty($next)) ? BASE_URL."api/categories/".$this->args['catId']."/products/page/{$next}/limit/{$l2}" : null,
			'prev'	=> (!empty($prev)) ? BASE_URL."api/categories/".$this->args['catId']."/products/page/{$prev}/limit/{$l2}" : null
		);
        $this->message = "Successfully retrieved the category products list.";
	}

}