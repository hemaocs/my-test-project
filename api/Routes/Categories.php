<?php

use Appsolute\Api\Controllers\Reserves;

//Define a group
$this->app->group('/users', function () {
    
    //Get all categories
	$this->app->get('/:userId/categories(/page/:page(/limit/:limit))', function ($userId, $page = 1, $limit = 25) {
		$data = new Reserves\GetController( 'getCategories', ['userId' => $userId, 'page' => (int)$page, 'limit' => (int)$limit] );
		$data->send();
	});

	//Get products by categories
	$this->app->get('/:userId/categories/:catId/products(/page/:page(/limit/:limit))', function ($userId, $catId, $page = 1, $limit = 25) { 
		$data = new Reserves\GetController( 'getProducts', ['userId' => $userId, 'catId' => $catId, 'page' => (int)$page, 'limit' => (int)$limit] );
		$data->send();
	});

	//Get All products
	$this->app->get('/:userId/products(/page/:page(/limit/:limit))', function ($userId,$page = 1, $limit = 25) { 
		$data = new Reserves\GetController( 'getAllProducts', ['userId' => $userId,'page' => (int)$page, 'limit' => (int)$limit] );
		$data->send();
	});
	
});