<?php

use Appsolute\Backend\Controllers\Calender;

$this->app->group('/calender', function () {
	/* ---
	// Display the calender
	-- */
	$this->app->map('/', function () { 
		$data = new Calender\GetController( 'getList' );
		$data->send("Calender/list.template.html");
	})->via('GET')->name('public.calender.root')->role('admin');
		
});	
