<?php

use Appsolute\Api\Controllers\ImageUpload;

//Define a group
$this->app->group('/image', function () {
    
    //Create a new idea
	$this->app->post('/type/:type/:id', function ($type, $id) {
		$data = new ImageUpload\PostController( 'newImage', ['type' => $type, 'id' => $id] );
		$data->send();
	});
	
});