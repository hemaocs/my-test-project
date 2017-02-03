<?php

use Appsolute\Api\Controllers\Users;

//Define a group
$this->app->group('/users', function () {

    //Get all users
    $this->app->get('(/page/:page(/limit/:limit))', function ($page = 1, $limit = 10) {
        $data = new Users\GetController( 'getAllUsers', ['page' => (int)$page, 'limit' => (int)$limit] );
        $data->send();
    });
    
    // User Login
	$this->app->post('/login', function () {
		$data = new Users\PostController( 'login' );
		$data->send();
	});
	
	//Create a new user
	$this->app->post('/', function () {
		$data = new Users\PostController( 'newUser' );
		$data->send();
	});

	//Update the user
    $this->app->put('/:id', function ($id) {
        $data = new Users\PutController( 'updateUser', ['id' => $id] );
        $data->send();
    });

	//Get single user
    $this->app->get('/:id', function ($id) {
        $data = new Users\GetController( 'getSingleUser', ['id' => $id] );
        $data->send();
    });

   /* // Update User Avatar Image
	$this->app->post('/:id/avatar', function ($id) {
        $data = new Users\PostController( 'newAvatar', ['id' => $id] );
        $data->send();
    });

    //Delete the user
    $this->app->delete('/:id', function ($id) {
        $data = new Users\DeleteController( 'deleteUser', ['id' => $id] );
        $data->send();
    });*/
	
	$this->app->post('/:id/push/tokens', function ($id) {
        $data = new Users\PostController( 'newToken', ['id' => $id] );
        $data->send();
    });

    //Get search users
    $this->app->get('/:id/search/:key(/page/:page(/limit/:limit))', function ($id, $key, $page = 1, $limit = 10) {
        $data = new Users\GetController( 'getSearchUsers', ['id' => $id, 'key' => $key, 'page' => (int)$page, 'limit' => (int)$limit] );
        $data->send();
    });
	
	
});