<?php

use Appsolute\Backend\Controllers\Userchat;

$this->app->group('/userchats', function () { 

	
//$this->app->group('/', function () {

		// Display the page for adding a new user
		$this->app->map('/', function () {
			$data = new Userchat\GetController( 'add' );
			$data->send("Userchat/add.template.html");
		})->via('GET')->name('public.userchats.add')->role('admin');

		// Post request to add a new user
		$this->app->map('/', function () {
			$data = new Userchat\PostController( 'add' );
			if(!empty($this->app->errors)) {
				$this->app->flashNow("errors", $this->app->errors);
				$data->send("Userchat/add.template.html");
			} else {
				$this->app->flash("success", "Successfully added the userchats.");
				//$this->app->redirect('userchats');
				$data->send("Userchat/add.template.html");
			}
		})->via('POST')->role('admin');
	
	//});

});	
