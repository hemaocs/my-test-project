<?php

namespace Appsolute\Api\Models\Validation;

use R;
use Appsolute\Api\Models\Resource\Resource;

class Reserves extends Resource {	
    
    /*public function checkProprerties( $data ){
		
		if(!isset($data->name)){
			throw new ValidationException( 'Name attribute is missing.', 500, 400 );
		}
	}*/

	public function update() {
		
        /*if(isset($this->bean->name)){
            $title = trim($this->bean->name);
            if(empty($title)) {
                throw new ValidationException( 'You have to provide a name.', 507, 400 );
            }
        }*/
        
    }

}