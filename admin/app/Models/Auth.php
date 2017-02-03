<?php

namespace Appsolute\Backend\Models;

use R;
use Appsolute\Backend\Classes\Auth\DatabaseInterface;
use Appsolute\Backend\Models\Validation\ValidationException;

Class Auth extends Database implements DatabaseInterface {

	public function insertSession( Array $data ) {
		try {
			$session = R::xdispense( $this->config->getTable('users_sessions') );
			$session->admin_id = $data['admin_id'];
			$session->key_selector = $data['key'];
			$session->session = $data['token'];
			$session->expires = $data['expires'];
			$session->created_at = date('Y-m-d H:i:s', time());
			$session->updated_at = date('Y-m-d H:i:s', time());
			R::store( $session );
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}

	public function getSession( $key ) {
		try {
			$session = R::findOne( $this->config->getTable('users_sessions'), 'key_selector = ?', [ $key ] );
			if(!empty($session)){
				return $session->export();
			} else {
				return null;
			}
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}

	public function removeSession( $key ) {
		try {
			$session = R::findOne( $this->config->getTable('users_sessions'), "key_selector = ?", array($key) );
			if(empty($session)){
				throw new ValidationException("This session doesn't exist.");
			}
			R::trash( $session );
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}

	public function getUserByKey( $key ) {
		try {
			$session = R::findOne( $this->config->getTable('users_sessions'), "key_selector = ?", array($key) );
			if(empty($session)){
				throw new ValidationException("This session doesn't exist.");
			}			
			return $session->admin->export();
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
	}

}