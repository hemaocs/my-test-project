<?php

use Appsolute\Backend\Controllers\Admin;

/* ---
// Redirect to the home if the user try to access root
-- */
$this->app->map('/', function () {
	$this->app->redirect('home');
})->via('GET')->name('public.root')->role('admin');

/* ---
// Homepage
-- */
$this->app->map('/home', function () {
	$data = new Admin\GetController( 'home' );
	$data->send("Home/home.template.html");
})->via('GET')->name('public.home')->role('admin');

/* ---
// Unauthorized
-- */
$this->app->map('/forbidden', function () {
	$this->app->render('Errors/unauthorized.template.html');
	$this->app->response->setStatus(403);
})->via('GET')->name('unauthorized');