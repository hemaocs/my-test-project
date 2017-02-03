<?php

use Appsolute\Backend\Controllers\Users;

$this->app->group('/users', function () { 

	/* ---
	// Display the list of users
	-- */
	$this->app->map('/', function () { 
		$data = new Users\GetController( 'getList' );
		$data->send("Users/list.template.html");
	})->via('GET')->name('public.users.root')->role('admin');

	/* ---
	// Pagination
	-- */

	/*$this->app->map('/page/:page/limit/:limit', function ($page, $limit) { 
		$data = new Users\GetController( 'getList', ['page' => $page, 'limit' => $limit] );
		$data->send("Users/list.template.html");
	})->via('GET')->role('admin');*/
	
	$this->app->map('/filter/:type/:keyword', function ($type,$keyword) { 
		$data = new Users\GetController( 'getList', ['type' => $type, 'keyword' => $keyword] );
		$data->send("Users/list.template.html");
	})->via('GET')->role('admin');

	$this->app->group('/add', function () {

		// Display the page for adding a new user
		$this->app->map('/', function () {
			$data = new Users\GetController( 'add' );
			$data->send("Users/add.template.html");
		})->via('GET')->name('public.users.add')->role('admin');

		// Post request to add a new user
		$this->app->map('/', function () {
			$data = new Users\PostController( 'add' );
			if(!empty($this->app->errors)) {
				$this->app->flashNow("errors", $this->app->errors);
				$data->send("Users/add.template.html");
			} else {
				$this->app->flash("success", "Successfully added the User.");
				$this->app->redirect('../users');
			}
		})->via('POST')->role('admin');
	
	});




	/* ---
	// Update a user
	-- */
	$this->app->group('/update/:id', function () {

		// Display the page to update a user
		$this->app->map('/', function ($id) {
			$data = new Users\GetController( 'updateView', ['id' => $id] );
			$data->send("Users/update.template.html");
		})->via('GET')->name('public.users.update')->role('admin');

		// Post request to update a user
		$this->app->map('/', function ($id) {
			$data = new Users\PutController( 'updateUser', ['id' => $id] );
			if(!empty($this->app->errors)) {
				$this->app->flashNow("errors", $this->app->errors);
				$data->send("Users/update.template.html");
			} else {
				$this->app->flash("success", "Successfully updated the User.");
				$this->app->redirect('../../users');
			}
		})->via('POST', 'PUT')->role('admin');

	});


	/* ---
	// Delete a user
	-- */
	$this->app->map('/delete/:id', function ($id) {
		$data = new Users\DeleteController( 'deleteUser', ['id' => $id] );
		if(!empty($this->app->errors)) {
			$this->app->flash("errors", $this->app->errors);
		} else {
			$this->app->flash("success", "Successfully deleted the User.");
		}
		$this->app->redirect('../../users');
	})->via('DELETE','GET')->role('admin');



	
});	
