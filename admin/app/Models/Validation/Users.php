<?php

namespace Appsolute\Backend\Models\Validation;

use R;
use RedBeanPHP\SimpleModel;

class Users extends SimpleModel {

	public function checkProprerties( $data ){ //print"<pre>";print_r($_POST); exit();

		$firstname = trim($this->bean->firstname);
		$lastname = trim($this->bean->lastname);
		$email = trim($this->bean->email);
		
		if(isset($this->bean->firstname)){
			$firstname = trim($this->bean->firstname);
			if(empty($firstname)) {
				throw new ValidationException( 'You have to provide a firstname.' );
			}
		}

		if(isset($this->bean->email)){
			$email = trim($this->bean->email);
			if(empty($email)) {
				throw new ValidationException( 'You have to provide an email.' );
			}
		}

		if(isset($this->bean->password)){ 
			$password = $this->bean->password;
			if(empty($password)) {
				throw new ValidationException( 'You have to provide a password.');
			}
		} else {
			throw new ValidationException( 'You have to provide a password.');
		}

		if(isset($_POST['password-confirm'])){ 
			$password = $_POST['password-confirm'];
			if(empty($password)) {
				throw new ValidationException( 'Please fill the confirm password.');
			}
		} else {
			throw new ValidationException( 'Please fill the confirm password.');
		}
	}
	
	public function checkUpdateProprerties( $data ){
		
		$firstname = trim($this->bean->firstname);
		$lastname = trim($this->bean->lastname);
		$email = trim($this->bean->email);

		if(isset($this->bean->firstname)){
			$firstname = trim($this->bean->firstname);
			if(empty($firstname)) {
				throw new ValidationException( 'You have to provide a firstname.' );
			}
		}

		if(isset($this->bean->email)){
			$email = trim($this->bean->email);
			if(empty($email)) {
				throw new ValidationException( 'You have to provide an email.' );
			}
		}

	}


	public function update() {
		$email = trim($this->bean->email);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    throw new ValidationException( 'Not a valid email.' );
		}
		$searchUser  = R::findOne( 'users', ' email = ? && id != ?', [ $email, $this->bean->id ] );
		if(!empty($searchUser)){
			$equal = $this->bean->equals($searchUser);
		} else {
			$equal = FALSE;
		}
		
		if(!empty($searchUser) && !$equal) {
			throw new ValidationException( 'This email already exists.' );
		}	
	}

}