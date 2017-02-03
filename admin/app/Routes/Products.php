<?php

use Appsolute\Backend\Controllers\Products;

$this->app->group('/products', function () {

	/* ---
	// Display the list of Products
	-- */
	$this->app->map('/', function () { 
		$data = new Products\GetController( 'getList' );
		$data->send("Products/list.template.html");
	})->via('GET')->name('public.products.root')->role('admin');


	//add a product
	$this->app->group('/add', function () {

		// Display the page for adding a new category
		$this->app->map('/', function () {
			$data = new Products\GetController( 'add' );
			$data->send("Products/add.template.html");
		})->via('GET')->name('public.products.add')->role('admin');

		// Post request to add a new category
		$this->app->map('/', function () {
			$data = new Products\PostController( 'add' );
			if(!empty($this->app->errors)) {
				$this->app->flashNow("errors", $this->app->errors);
				$data->send("Products/add.template.html");
			} else {
				$this->app->flash("success", "Successfully added the Product.");
				$this->app->redirect('../products');
			}
		})->via('POST')->role('admin');
	
	});

	/* ---
	// Update a product
	-- */
	$this->app->group('/update/:id', function () {

		// Display the page to update a product
		$this->app->map('/', function ($id) {
			$data = new Products\GetController( 'updateView', ['id' => $id] );
			$data->send("Products/update.template.html");
		})->via('GET')->name('public.products.update')->role('admin');

		// Post request to update a product
		$this->app->map('/', function ($id) {
			$data = new Products\PutController( 'updateProduct', ['id' => $id] );
			if(!empty($this->app->errors)) {
				$this->app->flashNow("errors", $this->app->errors);
				$data->send("Products/update.template.html");
			} else {
				$this->app->flash("success", "Successfully updated the Product.");
				$this->app->redirect('../../products');
			}
		})->via('POST', 'PUT')->role('admin');

	});


	/* ---
	// Delete a product
	-- */
	$this->app->map('/delete/:id', function ($id) {
		$data = new Products\DeleteController( 'deleteProduct', ['id' => $id] );
		if(!empty($this->app->errors)) {
			$this->app->flash("errors", $this->app->errors);
		} else {
			$this->app->flash("success", "Successfully deleted the Product.");
		}
		$this->app->redirect('../../products');
	})->via('DELETE','GET')->role('admin');


	$this->app->map('/filter/:type', function ($type) { //print"here";exit();
		$data = new Products\GetController( 'getList', ['type' => $type] );
		$data->send("Products/list.template.html");
	})->via('GET')->role('admin');

    $this->app->map('/changeStatus/:default/:id', function ($default, $id) { //print"here";exit();
		$data = new Products\PostController( 'changeStatus', ['default' => $default, 'id' => $id ] );
	})->via('POST')->role('admin');

    
	// Post a image
	$this->app->map('/upload', function () {
		$data = new Products\PostController( 'uploadImage' );
		$data->send("Products/crop.template.html");
	})->via('POST');


	// Crop a image
	$this->app->map('/crop', function () {
		$data = new Products\PostController( 'cropImage' );
	})->via('POST');
	
		
});	
