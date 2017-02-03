<?php

namespace Appsolute\Api\Models\Validation;

use R;
use Appsolute\Api\Models\Resource\Resource;

class Users extends Resource {	

	public function checkProprerties( $data ){ 
		
		if(!isset($data->users[0]->firstname)){
			throw new ValidationException( 'Firstname attribute is missing.', 500, 400 );
		}

		if(!isset($data->users[0]->lastname)){
			throw new ValidationException( 'Lastname attribute is missing.', 501, 400 );
		}

		if(!isset($data->users[0]->email)){
			throw new ValidationException( 'Email attribute is missing.', 502, 400 );
		}
		
		if(!isset($data->users[0]->password)){
			throw new ValidationException( 'Password attribute is missing.', 503, 400 );
		}
	}
	
	public function checkUpdateProprerties( $data ){
		
		if(!isset($data->users[0]->firstname)){
			throw new ValidationException( 'Firstname attribute is missing.', 504, 400 );
		}

		if(!isset($data->users[0]->lastname)){
			throw new ValidationException( 'Lastname attribute is missing.', 505, 400 );
		}

		if(!isset($data->users[0]->email)){
			throw new ValidationException( 'Email attribute is missing.', 506, 400 );
		}
		
	}

    public function update() {
		
        if(isset($this->bean->firstname)){
            $firstname = trim($this->bean->firstname);
            if(empty($firstname)) {
                throw new ValidationException( 'You have to provide a firstname.', 507, 400 );
            }
        }

        if(isset($this->bean->lastname)){
            $lastname = trim($this->bean->lastname);
            if(empty($lastname)) {
                throw new ValidationException( 'You have to provide a lastname.', 508, 400 );
            }
        }

        if(isset($this->bean->email)){
            $email = trim($this->bean->email);
            if(empty($email)) {
                throw new ValidationException( 'You have to provide an email.', 509, 400 );
            }
        }
		
		if(isset($this->bean->password)){
			$password = trim($this->bean->password);
			if(empty($password)) {
				throw new ValidationException( 'You have to provide a password.', 510, 400 );
			}
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException( 'Not a valid email.', 511, 400 );
        }
        $searchUser  = R::findOne( 'users', ' email = ? AND `deleted_at` IS NULL ', [ $email ] );
        if(!empty($searchUser)){
            $equal = $this->bean->equals($searchUser);
        } else {
            $equal = FALSE;
        }
        
        if(!empty($searchUser) && !$equal) {
            throw new ValidationException( 'This email already exists.', 512, 400 );
        }
    }

}