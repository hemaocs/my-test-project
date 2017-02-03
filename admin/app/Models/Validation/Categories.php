<?php

namespace Appsolute\Backend\Models\Validation;

use R;
use RedBeanPHP\SimpleModel;

class Categories extends SimpleModel {

	public function update() {
		$name = trim($this->bean->firstname);
		

		if(isset($this->bean->name)){
			$name = trim($this->bean->name);
			if(empty($name)) {
				throw new ValidationException( 'You have to provide a name.' );
			}
		}
		
		$searchCategory  = R::findOne( 'categories', ' name = ? && id != ?', [ $name, $this->bean->id ] );
		if(!empty($searchCategory)){
			$equal = $this->bean->equals($searchCategory);
		} else {
			$equal = FALSE;
		}
		
		if(!empty($searchCategory) && !$equal) {
			throw new ValidationException( 'This name already exists.' );
		}	
	}

}