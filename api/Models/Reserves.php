<?php

namespace Appsolute\Api\Models;

use R;
use Appsolute\Api\Models\Resource\Resource;
use Appsolute\Api\Models\Validation\ValidationException;

Class Reserves extends Database {

	private $tables;
  
    // Get All Categories
	public function getCategories($userId, $l1, $l2) {
	    $user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );
		//print"<pre>";print_r($user);exit();
		if(!empty($user)){
			$rows = R::getAll( "SELECT * FROM `".$this->config->getTable('categories')."` WHERE `deleted_at` IS NULL LIMIT :l1,:l2", array(":l1" => $l1, ":l2" => $l2) );		
			$categories = R::convertToBeans( 'categories', $rows );
			if (!empty($categories)) {
				return (new Resource($categories, 'Categories'))->toArray(1);
			} else {
				throw new ValidationException("No records found.", 10, 400);
			}
		} else {
        	throw new ValidationException("This user doesn't exist", 10, 400);
        }
	}

	public function categoriesCount() {
		$result = R::getAll( "SELECT * FROM `".$this->config->getTable('categories')."` WHERE `deleted_at` IS NULL" );
		return count($result);
	}

	// Get All Products
	public function getProducts($userId, $catId, $l1, $l2) {
	    $user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );
		if(!empty($user)){
			$rows = R::getAll( "SELECT *, ".$userId." AS user_id
				                FROM `".$this->config->getTable('products')."`
				                WHERE id IN (SELECT `product_id` FROM ".$this->config->getTable('productCategory')." WHERE `category_id` = :catId AND `deleted_at` IS NULL)
				                AND `is_default` = 0
				                AND `deleted_at` IS NULL 
				                LIMIT :l1,:l2", array(":catId" => $catId, ":l1" => $l1, ":l2" => $l2) );
			
			$products = R::convertToBeans( 'products', $rows );
			//print"<pre>";print_r($products);exit();
			if (!empty($products)) {
				return (new Resource($products, 'Products'))->toArray(1);
			} else {
				throw new ValidationException("No records found.", 10, 400);
			}
		} else {
        	throw new ValidationException("This user doesn't exist", 10, 400);
        }
	}


	public function productsCount($catId) {
		$result = R::getAll( "SELECT * FROM `".$this->config->getTable('products')."` 
			                  WHERE id IN (SELECT `product_id` FROM ".$this->config->getTable('productCategory')." WHERE `category_id` = :catId AND `deleted_at` IS NULL) 
			                  AND `deleted_at` IS NULL", array(":catId" => $catId) );
		return count($result);
	}


	public function getAllProducts($userId,$l1, $l2) {
	    $user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );
		if(!empty($user)){
			$products  = R::getAll("SELECT * FROM `products` WHERE `deleted_at` IS NULL");
			$products_res = R::convertToBeans('products',$products);
			if(!empty($products_res)) {
				return (new Resource($products_res, 'Products'))->toArray(1);
			} else {
				throw new ValidationException("No records found", 10, 400);
			}
		} else {
    		throw new ValidationException("This user doesn't exist", 10, 400);
    	}
	}

	public function getproductCount() {
		$result = R::getAll( "SELECT * FROM `".$this->config->getTable('products')."` WHERE `deleted_at` IS NULL" );
		return count($result);
	}

}