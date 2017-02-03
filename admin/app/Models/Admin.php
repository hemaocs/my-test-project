<?php

namespace Appsolute\Backend\Models;

use R;
use Appsolute\Backend\Classes\Auth\Auth as AuthClass;
use Appsolute\Backend\Models\Validation\ValidationException;

Class Admin extends Database {

	public function getAll() {
		try {
			$users = R::findAll( $this->config->getTable('admin') );
			return R::exportAll($users);
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function findOneById( $id ) {
		try {
			$user = R::load( $this->config->getTable('admin'), $id );
			return $user->export();
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function findOneByEmail( $email ) {
		try {
			$user = R::findOne( $this->config->getTable('admin'), 'email = ?', [ $email ] );
			if(!empty($user)){
				$data = $user->export();
				unset($data['roles_id']);
				$data['role'] = $user->roles->role;
				return $data;
			} else {
				return FALSE;
			}
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function getUserPendingCount() {
		$result =  R::count( $this->config->getTable('app_users'));
		return $result;
	}	

}