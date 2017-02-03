<?php

use Appsolute\Backend\Controllers\Admin;

$this->app->group('/logout', function () {

	/* ---
	// Logout the user
	-- */
	$this->app->map('/', function () {
		$data = new Admin\PostController( 'logout' );
		$this->app->redirect('./login');
	})->via('POST', 'GET')->name('public.logout')->role('admin');

});	
