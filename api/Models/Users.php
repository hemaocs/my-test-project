<?php

namespace Appsolute\Api\Models;

use R;
use Appsolute\Api\Models\Resource\Resource;
use Appsolute\Api\Models\Validation\ValidationException;

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

    // Get users count
    public function count() {
		return R::count($this->config->getTable('users'), ' `deleted_at` IS NULL ');
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
		if (isset($entry->users[0]->language)) {
			if (!empty($entry->users[0]->language)) {
		        $user->language = $entry->users[0]->language;
		    } else {
		    	$user->language = NULL;
		    }
	    } else {
	    	$user->language = NULL;
	    }
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
			if (isset($entry->users[0]->language)) {
				if (!empty($entry->users[0]->language)) {
			        $userUpdate->language = $entry->users[0]->language;
			    } else {
			    	$userUpdate->language = NULL;
			    }
		    } else {
		    	$userUpdate->language = NULL;
		    }
			$userUpdate->updated_at = date('Y-m-d H:i:s', strtotime('now'));
			R::store( $userUpdate );
			return $userUpdate->toArray();
		} else {
		    throw new ValidationException("This user doesn't exist", 10, 400);
		}
    }

	// Google Login
    public function loginUserGoogle($googleId, $email) {
		$loginGoogle = R::findOne($this->config->getTable('users'), 'google_id = ? AND `deleted_at` IS NULL', [ $googleId ] );
		$loginEmail = R::findOne($this->config->getTable('users'), 'email = ? AND `deleted_at` IS NULL', [ $email ] );
		if(!empty($loginGoogle)){
			return $loginGoogle->toArray();
		} elseif(!empty($loginEmail)) {
			$loginEmail->google_id = $googleId;
			$loginEmail->updated_at = date('Y-m-d H:i:s', strtotime('now'));
			R::store( $loginEmail );
			return $loginEmail->toArray();
		} else {
			throw new ValidationException("Invalid Google ID.", 10, 400);
		}
    }
	
	// FB Login
	public function loginUserFB($fb_id, $email) {
		$loginDetailsFB = R::findOne($this->config->getTable('users'), 'facebook_id = ? AND `deleted_at` IS NULL', [ $fb_id ] );
		$loginEmail = R::findOne($this->config->getTable('users'), 'email = ? AND `deleted_at` IS NULL', [ $email ] );
		if(!empty($loginDetailsFB)){
			return $loginDetailsFB->toArray();
		} elseif(!empty($loginEmail)) {
			$loginEmail->facebook_id = $fb_id;
			$loginEmail->updated_at = date('Y-m-d H:i:s', strtotime('now'));
			R::store( $loginEmail );
			return $loginEmail->toArray();
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
		//print"<pre>";print_r($pushToken);exit();
		R::store( $pushToken );
		return $pushToken->toArray();
    }

   /* // Get search users
    public function getSearchUsers( $userId, $key, $l1, $l2 ) {
        $user = R::findOne( $this->config->getTable('users'), 'id = ? AND `is_active` = 1 AND `deleted_at` IS NULL', [ $userId ] );
        if (!empty($user)) {
        	$res = $result = array();
	        $searchUsers = R::getAll( "SELECT `id`, `avatar`, `firstname`, `lastname`, `email`, `is_active`
	        	                FROM `".$this->config->getTable('users')."`
	        	                WHERE `id` != :userId AND `is_active` = 1 AND (`firstname` LIKE '%".$key."%' OR `lastname` LIKE '%".$key."%' OR `email` LIKE '%".$key."%')
	        	                AND `deleted_at` IS NULL
	        	                ORDER BY `id` ASC LIMIT :l1, :l2", array(":userId" => $userId, ":l1" => $l1, ":l2" => $l2) );
	        if (!empty($searchUsers)) {
	        	foreach ($searchUsers as $users) {
	        		$isFriend = 'no';
                    $contactUser = R::findOne( $this->config->getTable('usersContacts'), 
                    	                       'users_id = ? AND contact_id = ? AND `accepted_at` IS NOT NULL AND `refused_at` IS NULL AND `deleted_at` IS NULL', 
                    	                       [ $userId, $users['id'] ] );
                    if (!empty($contactUser)) {
                        $isFriend = 'yes';
                    }
                    $res['id'] = $users['id'];
                    $res['avatar'] = $users['avatar'];
                    $res['firstname'] = $users['firstname'];
                    $res['lastname'] = $users['lastname'];
                    $res['email'] = $users['email'];
                    $res['is_active'] = $users['is_active'];
                    $res['is_friend'] = $isFriend;
                    $result[] = $res;
	        	}
	        	$usersDetails = R::convertToBeans( 'users', $result );
				if (!empty($usersDetails)) {
				    return (new Resource($usersDetails, 'Users'))->toArray(1);
		        }
	        } else {
	        	throw new ValidationException("No records found.", 10, 400);
	        }
        } else {
        	throw new ValidationException("This user doesn't exist.", 10, 400);
        }
    }*/

    /*public function searchUsersCount( $userId, $key ) {
        $result = R::getAll( "SELECT `id`
        	                  FROM `".$this->config->getTable('users')."`
        	                  WHERE `id` != :userId AND `is_active` = 1 AND (`firstname` LIKE '%".$key."%' OR `lastname` LIKE '%".$key."%' OR `email` LIKE '%".$key."%')
        	                  AND `deleted_at` IS NULL
        	                  ORDER BY `id` ASC", array(":userId" => $userId) );
        if (!empty($result)) {
        	$count = count($result);
			return $count;
        } else {
        	throw new ValidationException("No records found.", 10, 400);
        }
    }*/
	
}