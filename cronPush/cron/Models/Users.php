<?php

namespace Appsolute\Cron\Models;

use R;
use Appsolute\Cron\Models\Resource\Resource;
use Appsolute\Cron\Models\Validation\ValidationException;

Class Users extends Database {

	private $tables;
    
    // Get All Users
	public function getAll($l1, $l2) {
		$collection = R::findAll( $this->config->getTable('users'), '`deleted_at` IS NULL LIMIT ?,?', [$l1, $l2] );
		if (!empty($collection)) {
		    return (new Resource($collection, 'Users'))->toArray(1);
        } else {
		    throw new ValidationException("No records found.", 10, 400);
		}
	}
    
	// Get User by Id
    public function findOneById( $id ) {
		$user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $id ] );		
		if(!empty($user)){
			return $user->toArray();
		} else {
			throw new ValidationException("This user doesn't exist", 10, 400);
		}
    }		 
    
	// Get User by Email
    public function findOneByEmail( $email ) {
		$user = R::findOne($this->config->getTable('users'), 'email = ? AND `deleted_at` IS NULL', [ $email ]);
		if(!empty($user)){
			return $user->toArray();
		} else {
			return FALSE;
		}
    }
    
	// Insert User
	public function insert( $entry ) {
		$user = R::xdispense($this->config->getTable('users'));
		$user->checkProprerties($entry);
		$facebookId = (!empty($entry->users[0]->facebook_id)) ? trim($entry->users[0]->facebook_id) : NULL;
		$googleId = (!empty($entry->users[0]->google_id)) ? trim($entry->users[0]->google_id) : NULL;
		$user->firstname = $entry->users[0]->firstname;
		$user->lastname = $entry->users[0]->lastname;
		$user->email = $entry->users[0]->email;
		$user->password = $entry->users[0]->password;
		$user->facebook_id = $facebookId;
		$user->google_id = $googleId;
		$user->is_active = 1;
		$user->created_at = date('Y-m-d H:i:s', strtotime('now'));
		R::store( $user );
		return $user->toArray();
    }

    // Update User
    public function update( $id, $entry ) {
	    
		$userUpdate = R::findOne($this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $id ] );
		if(!empty($userUpdate)){
			$userUpdate->checkUpdateProprerties($entry);
			
			$userUpdate->firstname = $entry->users[0]->firstname; 
			$userUpdate->lastname = $entry->users[0]->lastname;
			$userUpdate->email = $entry->users[0]->email;
			if(isset($entry->users[0]->password)){
				$userUpdate->password = $entry->users[0]->password;
			}
			$userUpdate->updated_at = date('Y-m-d H:i:s', strtotime('now'));
			R::store( $userUpdate );
			return $userUpdate->toArray();
		} else {
		    throw new ValidationException("This user doesn't exist", 10, 400);
		}
    }

    public function count() {
		return R::count($this->config->getTable('users'), ' `deleted_at` IS NULL ');
    }
    
	// Google Login
    public function loginUserGoogle($googleId) {
		$loginGoogle = R::findOne($this->config->getTable('users'), 'google_id = ? AND `deleted_at` IS NULL', [ $googleId ] );
		if(!empty($loginGoogle)){
			return $loginGoogle->toArray();
		} else {
			throw new ValidationException("Invalid Google ID.", 10, 400);
		}
    }
	
	// FB Login
	public function loginUserFB($fb_id) {
		$loginDetailsFB = R::findOne($this->config->getTable('users'), 'facebook_id = ? AND `deleted_at` IS NULL', [ $fb_id ] );
		if(!empty($loginDetailsFB)){
			return $loginDetailsFB->toArray();
		} else {
			throw new ValidationException("Invalid FB ID.", 10, 400);
		}
    }

	// User Login
	public function loginUser($email,$pwd) {
	    $loginDetails = R::findOne($this->config->getTable('users'), 'email = ? AND password = ? AND `deleted_at` IS NULL', [ $email, $pwd ] );
		if(!empty($loginDetails)){
			return $loginDetails->toArray();
		} else {
			throw new ValidationException("Invalid credentials.", 10, 400);
		}
    }

    // Add User Avatar Image
	public function insertAvatar( $id, $entry ) {
		$user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $id ] );
		if ($user->id != 0) {
		    $user->avatar = $entry;
			$user->updated_at = date('Y-m-d H:i:s', strtotime('now'));
		    R::store( $user );
		    return $user->toArray();
		} else {
		    throw new ValidationException("This user doesn't exist.", 10, 400);
		}
    }

    // Delete User
    public function delete( $id ) {
	    $userDelete = R::findOne($this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $id ]);
		$userContactDel = R::getAll( "SELECT `id`, `users_id`, `contact_id` FROM `".$this->config->getTable('usersContacts')."`
			   WHERE (`users_id` = :id OR `contact_id` = :id) AND `deleted_at` IS NULL", array(":id" => $id) );
		/*$userEventDel = R::getAll( "SELECT `id`, `event_id`, `user_id` FROM `".$this->config->getTable('usersEvents')."`
			   WHERE `user_id` = :id AND `deleted_at` IS NULL", array(":id" => $id) );*/
		if(!empty($userDelete)){
			$userDelete->deleted_at = date('Y-m-d H:i:s', strtotime('now'));
		    R::store( $userDelete );
			
			if (!empty($userContactDel)) {
                foreach ($userContactDel as $userContacts) {
                	$contactDel = R::load( $this->config->getTable('usersContacts'), $userContacts['id'] );
                    $contactDel->deleted_at = date('Y-m-d H:i:s', strtotime('now'));
		            R::store( $contactDel );
                }
		    }
			
			/*if (!empty($userEventDel)) {
                foreach ($userEventDel as $userEvents) {
                	$eventDel = R::load( $this->config->getTable('usersEvents'), $userEvents['id'] );
                    $eventDel->deleted_at = date('Y-m-d H:i:s', strtotime('now'));
		            R::store( $eventDel );
                }
		    }*/
		} else {
			throw new ValidationException("This user doesn't exist.", 10, 400);
		}
    }

    public function insertToken( $id, $entry ) {
		$pushToken = R::xdispense($this->config->getTable('pushToken'));		
		$pushToken->checkProperties($entry);
		
		$pushToken = R::findOrCreate( $this->config->getTable('pushToken'), ['mode' => $entry->mode, 'platform' => $entry->platform, 'token' => $entry->token]);
		$pushToken->user_id = $id;
		$pushToken->app_id = (!empty($entry->app_id)) ? $entry->app_id : NULL;
		$pushToken->devicename = $entry->devicename;
		$pushToken->app_version = $entry->app_version;
		$pushToken->created_at = date('Y-m-d H:i:s', strtotime('now'));
		$pushToken->updated_at = date('Y-m-d H:i:s', strtotime('now'));
		R::store( $pushToken );
		return $pushToken->toArray();
    }
	
}