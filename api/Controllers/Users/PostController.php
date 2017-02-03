<?php

namespace Appsolute\Api\Controllers\Users;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Upload\Upload;
use Appsolute\Api\Controllers;
use Appsolute\Api\Models\Users;
use Appsolute\Api\Models\Reserves;
use Appsolute\Api\Models\Validation\ValidationException;

Class PostController extends Controllers\Controller {

	// Add New User
	public function newUser(){ 
		$user_new = new Users($this->configManager('Database'));
		$values = $this->app->request->getBody(); 
		$created_user = $user_new->insert( $values );
		$this->data['users'] = $user_new->findOneById( $created_user['id'] );
		$this->message = "The user has been successfully created.";
		$this->statusCode = 201;
	}
	
	public function login() {
		$users = new Users($this->configManager('Database'));
		$reserves = new Reserves($this->configManager('Database'));
		$user_data = $this->app->request->getBody();
		
		if (isset($user_data->users[0]->facebook_id) && isset($user_data->users[0]->facebook_email)) {
		    if (empty($user_data->users[0]->facebook_id)) {
				throw new ValidationException( 'You have to provide facebook id.', 10, 400 );
			} elseif(empty($user_data->users[0]->facebook_email)) {
			    throw new ValidationException( 'You have to provide email.', 10, 400 );
			} else {
			    $userInfo = $users->loginUserFB($user_data->users[0]->facebook_id, $user_data->users[0]->facebook_email);
			    //$reservesInfo = $reserves->getReservesByCreator($userInfo['id']);
			}
		} elseif (isset($user_data->users[0]->google_id) && isset($user_data->users[0]->google_email)) {
		    if (empty($user_data->users[0]->google_id)) {
				throw new ValidationException( 'You have to provide google id.', 10, 400 );
			} elseif(empty($user_data->users[0]->google_email)) {
			    throw new ValidationException( 'You have to provide email.', 10, 400 );
			} else {
			    $userInfo = $users->loginUserGoogle($user_data->users[0]->google_id, $user_data->users[0]->google_email);
			   // $reservesInfo = $reserves->getReservesByCreator($userInfo['id']);	
			}	
		} elseif (isset($user_data->users[0]->email) && isset($user_data->users[0]->password)) {
			if (empty($user_data->users[0]->email)) {
				throw new ValidationException( 'You have to provide email.', 10, 400 );
			} elseif (empty($user_data->users[0]->password)) {
				throw new ValidationException( 'You have to provide password.', 10, 400 ); 
			} else {
				$userInfo = $users->loginUser($user_data->users[0]->email, $user_data->users[0]->password);
				//$reservesInfo = $reserves->getReservesByCreator($userInfo['id']);
			}
		} else {
			$this->message = "Invalid credentials.";
			$this->statusCode = 400;
			$this->responseCode = 10; //Error code
		}
		
		if(!empty($userInfo)) {
			$this->data['user'] = $userInfo;
			//$this->data['reserves'] = $reservesInfo;
			$this->message = "Successfully logged in.";
			$this->statusCode = 201;	
		} else {
			$this->message = "Invalid type of connection.";
		    $this->statusCode = 400;
		    $this->responseCode = 10; //Error code
		}
	}

	public function newToken(){
		$users = new Users($this->configManager('Database'));
		$values = $this->app->request->getBody();
		$this->data[] = $users->insertToken( $this->args['id'], $values );
		$this->message = "The device token has been successfully inserted.";
		$this->statusCode = 201;
	}

}