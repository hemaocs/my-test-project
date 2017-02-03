<?php

use Appsolute\Backend\Controllers\Categories;

$this->app->group('/categories', function () {

	/* ---
	// Display the list of category
	-- */
	$this->app->map('/', function () { 
		$data = new Categories\GetController( 'getList' );
		$data->send("Categories/list.template.html");
	})->via('GET')->name('public.categories.root')->role('admin');
	


	$this->app->group('/add', function () {

		// Display the page for adding a new category
		$this->app->map('/', function () {
			$data = new Categories\GetController( 'add' );
			$data->send("Categories/add.template.html");
		})->via('GET')->name('public.categories.add')->role('admin');

		// Post request to add a new category
		$this->app->map('/', function () {
			$data = new Categories\PostController( 'add' );
			if(!empty($this->app->errors)) {
				$this->app->flashNow("errors", $this->app->errors);
				$data->send("Categories/add.template.html");
			} else {
				$this->app->flash("success", "Successfully added the Category.");
				$this->app->redirect('../categories');
			}
		})->via('POST')->role('admin');
	
	});


	/* ---
	// Update a category
	-- */
	$this->app->group('/update/:id', function () {

		// Display the page to update a Category
		$this->app->map('/', function ($id) {
			$data = new Categories\GetController( 'updateView', ['id' => $id] );
			$data->send("Categories/update.template.html");
		})->via('GET')->name('public.categories.update')->role('admin');

		// Post request to update a Category
		$this->app->map('/', function ($id) {
			$data = new Categories\PutController( 'updateCategory', ['id' => $id] );
			if(!empty($this->app->errors)) {
				$this->app->flashNow("errors", $this->app->errors);
				$data->send("Categories/update.template.html");
			} else {
				$this->app->flash("success", "Successfully updated the Category.");
				$this->app->redirect('../../categories');
			}
		})->via('POST', 'PUT')->role('admin');

	});


	/* ---
	// Delete a category
	-- */
	$this->app->map('/delete/:id', function ($id) {
		$data = new Categories\DeleteController( 'deleteCategory', ['id' => $id] );
		if(!empty($this->app->errors)) {
			$this->app->flash("errors", $this->app->errors);
		} else {
			$this->app->flash("success", "Successfully deleted the Category.");
		}
		$this->app->redirect('../../categories');
	})->via('DELETE','GET')->role('admin');
	
	
	//to view category
	$this->app->map('/view/:id', function ($id) { 
		$data = new Categories\GetController( 'getCategoryDetails', ['id' => $id]  );
		$data->send("Categories/view.template.html");
	})->via('GET')->name('public.categories.view')->role('admin');

	// Post a image
	$this->app->map('/upload', function () {
		$data = new Categories\PostController( 'uploadImage' );
		$data->send("Categories/crop.template.html");
	})->via('POST');

	// Crop a image
	$this->app->map('/crop', function () {
		$data = new Categories\PostController( 'cropImage' );
	})->via('POST');

});	
