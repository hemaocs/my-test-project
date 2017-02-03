<?php

namespace Appsolute\Backend\Models\Validation;

use R;
use RedBeanPHP\SimpleModel;

class Products extends SimpleModel {

	public function update() {
		$name = trim($this->bean->name);
		if(isset($this->bean->name)){
			$name = trim($this->bean->name);
			if(empty($name)) {
				throw new ValidationException( 'You have to provide a name.' );
			}
		}

		if(isset($this->bean->category_id)){
			$category_id = trim($this->bean->category_id);
			if(empty($category_id)) {
				throw new ValidationException( 'You have to provide a category.' );
			}
		}
        
        if (isset($_POST['frm_submit_pro'])) {
			if(isset($_POST['frmCategoryId']) && !empty($_POST['frmCategoryId'])){
	            if ($_POST['frmCategoryId'] == '0') {
	            	throw new ValidationException( 'You have to provide a category.' );
	            }
			} else {
                throw new ValidationException( 'You have to provide a category.' );
			}
	    }
		
		$searchProduct  = R::findOne( 'products', ' name = ? && id != ? AND deleted_at IS NULL ', [ $name, $this->bean->id ] );
		if(!empty($searchProduct)){
			$equal = $this->bean->equals($searchProduct);
		} else {
			$equal = FALSE;
		}
		
		if(!empty($searchProduct) && !$equal) {
			throw new ValidationException( 'This name already exists.' );
		}	
	}

}