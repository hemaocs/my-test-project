<?php

use Appsolute\Backend\Controllers\Admin;

$this->app->group('/login', function () {
	/* ---
	// Display the page to login
	-- */
	$this->app->map('/', function () {
		$data = new Admin\GetController( 'loginView' );
		$data->send("Admin/login.template.html");
	})->via('GET')->name('public.login')->role('admin');

	/* ---
	// Request to login
	-- */
	$this->app->map('/', function () {
		$data = new Admin\PostController( 'login' );
		if(!empty($this->app->errors)) {
			$this->app->flash("errors", $this->app->errors);
			$this->app->redirect('./login');
		} else {			
			$this->app->flash("success", "connected_message");
			$this->app->redirect($this->app->urlFor('public.home'));
		}
	})->via('POST')->role('admin');

});	
