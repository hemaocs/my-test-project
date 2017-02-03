<?php

namespace Appsolute\Api\Controllers\ImageUpload;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Upload\Upload;
use Appsolute\Api\Controllers;
use Appsolute\Api\Models\ImageUpload;
use Appsolute\Api\Models\Users;
use Appsolute\Api\Models\Recipes;
use Appsolute\Api\Models\Validation\ValidationException;

Class PostController extends Controllers\Controller {

	// Add Image
	public function newImage(){
		if (isset($this->args['id']) && !empty($this->args['id'])) {
			$id = $this->args['id'];
		}
		if (isset($this->args['type']) && !empty($this->args['type'])) {
			$type = $this->args['type'];
		}
        if ($type == 'user') {
            $user = new Users($this->configManager('Database'));
			$userDetails = $user->findOneById( $id );
	        if (isset($userDetails['avatar'])) {
	        	if(!empty($userDetails['avatar'])){
					$arrayAvatar = explode('/', $userDetails['avatar']);
					$oldAvatar = end($arrayAvatar);
					if(!empty($oldAvatar) && file_exists(UPLOAD_FOLDER.'user/'.$oldAvatar)) {
						unlink(UPLOAD_FOLDER.'user/'.$oldAvatar);
					}
				}
	        }
			
			$extension = explode('.', $_FILES['image']['name']);
			$new_file_name = uniqid($id).'.'.end($extension);

			$file = new Upload(UPLOAD_FOLDER.'user/');
	        $file->save($_FILES['image']['tmp_name'], $new_file_name);
			$this->data = $user->insertAvatar( $id, $new_file_name );

			$this->message = "The avatar has been successfully updated.";
			$this->statusCode = 201;
        } elseif ($type == 'recipe') {
            $recipe = new Recipes($this->configManager('Database'));
			$recipeDetails = $recipe->getSingleRecipe( $id );

	        if (isset($recipeDetails['cover_image'])) {
	        	if(!empty($recipeDetails['cover_image'])){
					$arrayAvatar = explode('/', $recipeDetails['cover_image']);
					$oldAvatar = end($arrayAvatar);
					if(!empty($oldAvatar) && file_exists(UPLOAD_FOLDER.'recipe/'.$oldAvatar)) {
						unlink(UPLOAD_FOLDER.'recipe/'.$oldAvatar);
					}
				}
	        }
			
			$extension = explode('.', $_FILES['image']['name']);
			$new_file_name = uniqid($id).'.'.end($extension);

			$file = new Upload(UPLOAD_FOLDER.'recipe/');
	        $file->save($_FILES['image']['tmp_name'], $new_file_name);
			$this->data = $recipe->insertImage( $id, $new_file_name );

			$this->message = "The recipe image has been successfully updated.";
			$this->statusCode = 201;
        } else {
        	throw new ValidationException("Invalid type.", 10, 400);
        }
	}

}