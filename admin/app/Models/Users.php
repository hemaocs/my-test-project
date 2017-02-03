<?php

namespace Appsolute\Backend\Models;

use R;
use Appsolute\Backend\Classes\Auth\Auth as AuthClass;
use Appsolute\Backend\Models\Validation\ValidationException;
use Appsolute\Backend\Classes\Utility;

Class Users extends Database {
	
	
    public function getAll($filter=null) { 
		try {
			$keyword = (isset($filter['keyword']))? $filter['keyword']: '';
			if(!empty($filter) && $keyword!='') { //echo $filter['filterby'].' - '.$keyword; exit();
				switch($filter['filterby']) {
					case 'email':
						$users = R::findAll( $this->config->getTable('users'), ' email LIKE :email AND deleted_at IS NULL ORDER BY id ASC',
										  array(':email'=>'%'.$keyword.'%' ) );
						break;
					case 'name':						
						$users = R::findAll( $this->config->getTable('users'), ' firstname LIKE :firstname OR lastname LIKE :lastname AND deleted_at IS NULL ORDER BY id ASC',
											array(':firstname'=>'%'.$keyword.'%', ':lastname'=>'%'.$keyword.'%' ) );
						break;
					case 'is_active':						
						$users = R::findAll( $this->config->getTable('users'), ' is_active=:is_active AND deleted_at IS NULL ORDER BY id ASC',
											array(':is_active'=>$keyword ) );
						break;
					default:
						$users = R::findAll( $this->config->getTable('users'), 'deleted_at IS NULL ORDER BY id ASC');
						break;
				}		
			} else {
				$users = R::findAll( $this->config->getTable('users'), 'deleted_at IS NULL ORDER BY id ASC');
			}			
			//$res_users = null;
			$res_users = $res = array();
			if(!empty($users)) {
				foreach($users as $user) {
					$res['id'] = $user->id;
					$res['firstname'] = $user->firstname;
					$res['lastname'] = $user->lastname;
					$res['email'] = $user->email;
					$res['created_at'] = date('d/m/Y', strtotime($user->created_at));
					$res_users[] = $res;
				}
			}			
			return $res_users;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

	public function doGetPagination($page,$start, $limit,$filter=null, $base_url){		
		$utility = new Utility\Utility();
		$keyword = (isset($filter['keyword']))? $filter['keyword']: '';
		if(!empty($filter) && $keyword!='') {
				switch($filter['filterby']) {
					case 'email':
						$total_pages = R::count( $this->config->getTable('users'),' email LIKE :email AND deleted_at IS NULL ', array(':email'=>'%'.$keyword.'%'));
						break;
					case 'name':
						$total_pages = R::count( $this->config->getTable('users'),' firstname LIKE :firstname OR lastname LIKE :lastname AND deleted_at IS NULL ', array(':firstname'=>'%'.$keyword.'%', ':lastname'=>'%'.$keyword.'%'));
						break;
					case 'is_active':
						$total_pages = R::count( $this->config->getTable('users'),' is_active = :is_active AND deleted_at IS NULL', array(':is_active'=>$keyword));
						break;									
					default:
						$total_pages = R::count( $this->config->getTable('users'), 'deleted_at IS NULL');
						break;
				}		
		} else {
			$users = R::findAll( $this->config->getTable('users'), 'deleted_at IS NULL' );
			$total_pages = count($users);
		}
		
		if(!empty($filter) && $keyword!='') {
			return $utility->pagination($total_pages, $page, $start, $limit,$base_url.'users/filter/'.$filter['filterby'].'/'.$keyword.'/page');
		} else {
			return $utility->pagination($total_pages, $page, $start, $limit,$base_url.'users/page');
		}
	}

	public function findOneById( $id ) {
		try {
			$userupdate = $result = array();
			$user = R::load( $this->config->getTable('users'), $id );
			if(!empty($user)) {
				$userupdate['id'] = $user->id;
				$userupdate['firstname'] = $user->firstname;
				$userupdate['lastname'] = $user->lastname;
				$userupdate['email'] = $user->email;
				$userupdate['is_active'] = $user->is_active;
				if(!empty($user->avatar) && file_exists(UPLOAD_FOLDER.'user/'.$user->avatar)) {
					$userupdate['avatar'] = UPLOAD_URL.'user/'.$user->avatar;
					$userupdate['imgName'] = $user->avatar;
				} else {
					$userupdate['avatar'] = null;
				}
				$result = $userupdate;
			}
			//print"<pre>";print_r($result);exit();
			return $result;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function insert( $entry ) { 
		try {
			$utility = new Utility\Utility();
			$users = R::xdispense( $this->config->getTable('users') );
			$users->firstname = $entry['firstname'];
			$users->lastname = $entry['lastname'];
			$users->email = $entry['email'];
			if(isset($entry['password']) && !empty($entry['password'])) {
				$users->password = sha1($entry['password'].SHA1KEY);
			}
			if(isset($entry['is_active'])) {
				$users->is_active = "1";
			} else {
				$users->is_active = "0";
			}
			$users->created_at = date('Y-m-d H:i:s', strtotime('now'));
            if(!empty($_FILES['picture']['name']) && $_FILES['picture']['error']==0) {
				$extension = explode('.', $_FILES['picture']['name']);
				$file_name = uniqid().'.'.end($extension);
				$users->avatar = $file_name;
				move_uploaded_file($_FILES['picture']['tmp_name'], UPLOAD_FOLDER.'user/'.$file_name);
			} else {
				throw new ValidationException( 'You have to provide avatar.' );
			}
			$users->checkProprerties($entry);
			R::store( $users );
            
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }


	public function update( $id, $entry ) {
		try {
			$usersUpdate = R::load( $this->config->getTable('users'), $id );
			$usersUpdate->firstname = $entry['firstname'];	
			$usersUpdate->lastname = $entry['lastname'];
			$usersUpdate->email = $entry['email'];	
			if(!empty($entry['password'])) {
				$usersUpdate->password = sha1($entry['password'].SHA1KEY);
			} 
			if(!empty($_FILES['picture']['name']) && $_FILES['picture']['error']==0) {
				if (file_exists(UPLOAD_FOLDER.'user/'.$usersUpdate->avatar) && !empty($usersUpdate->avatar)) {
			    	@unlink(UPLOAD_FOLDER.'user/'.$usersUpdate->avatar);
				}
				$extension = explode('.', $_FILES['picture']['name']);
				$file_name = uniqid($usersUpdate->id).'.'.end($extension);
				$usersUpdate->avatar = $file_name;
				move_uploaded_file($_FILES['picture']['tmp_name'], UPLOAD_FOLDER.'user/'.$file_name);
			} elseif(!empty($entry['frmHiddImg'])) {
				$usersUpdate->avatar = $entry['frmHiddImg'];
			} else {
				throw new ValidationException( 'You have to provide avatar.' );
			}
			if(isset($entry['is_active'])) {
				$usersUpdate->is_active = "1";
			} else {
				$usersUpdate->is_active = "0";
			}
			$usersUpdate->updated_at = date('Y-m-d H:i:s', strtotime('now'));
			$usersUpdate->checkUpdateProprerties($entry);
			R::store( $usersUpdate );
		} catch(ValidationException $e) {
			
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

	public function delete( $id ) {
		try {
			$userdelete = R::findOne( $this->config->getTable('users'), 'id = ? AND deleted_at IS NULL ', [$id] );
			if(!empty($userdelete)){
				$userdelete->deleted_at =  date('Y-m-d H:i:s', strtotime('now'));
				if (!empty($userdelete->avatar)) {
			    	@unlink(UPLOAD_FOLDER.'user/'.$userdelete->avatar);
				}
				R::store( $userdelete );
			} else {
				throw new ValidationException("This user doesn't exist.");
			}
			
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function getAllDeviceTokens() {
        $apnsDeviceToken = R::getAll("SELECT * FROM `".$this->config->getTable('push_token')."`
                                      WHERE `deleted_at` IS NULL");
        return $apnsDeviceToken;
    }

    public function getuserName($userId) {
        $username = R::findOne('users', 'id = ? AND `deleted_at` IS NULL', [ $userId ] );
        return $username;
    }

}