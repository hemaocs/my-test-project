<?php

namespace Appsolute\Cron\Models\Validation;

use R;
use Appsolute\Cron\Models\Resource\Resource;

class Tasks extends Resource {	

	public function checkProprerties( $data ){
		
		if(!isset($data->title)){
			throw new ValidationException( 'Title attribute is missing.', 500, 400 );
		}

		if(!isset($data->description)){
			throw new ValidationException( 'Description attribute is missing.', 501, 400 );
		}

		if(!isset($data->start_at)){
			throw new ValidationException( 'Start Date attribute is missing.', 502, 400 );
		}

		if(!isset($data->end_at)){
			throw new ValidationException( 'End Date attribute is missing.', 502, 400 );
		}
	}

	public function checkUpdateProprerties( $data ){
		
		if(!isset($data->title)){
			throw new ValidationException( 'Title attribute is missing.', 500, 400 );
		}

		if(!isset($data->description)){
			throw new ValidationException( 'Description attribute is missing.', 501, 400 );
		}

		if(!isset($data->start_at)){
			throw new ValidationException( 'Start Date attribute is missing.', 502, 400 );
		}

		if(!isset($data->end_at)){
			throw new ValidationException( 'End Date attribute is missing.', 502, 400 );
		}
	}

	public function update() { 
		
        if(isset($this->bean->title)){
            $title = trim($this->bean->title);
            if(empty($title)) {
                throw new ValidationException( 'You have to provide a title.', 507, 400 );
            }
        }
        
        $start_at = trim($this->bean->start_at);
        if(empty($start_at)) {
            throw new ValidationException( 'You have to provide Start Date.', 507, 400 );
        }

        $end_at = trim($this->bean->end_at);
        if(empty($end_at)) {
            throw new ValidationException( 'You have to provide End Date.', 507, 400 );
        }

        if (!empty($start_at) && !empty($end_at)) {
        	$startTime = strtotime($start_at);
            $endTime = strtotime($end_at);
            if ($startTime >= $endTime) {
                throw new ValidationException( 'Start time should be less than end time.', 510, 400 );
            }
        }
		
		/*$searchTask  = R::findOne( 'tasks', ' title = ? AND `deleted_at` IS NULL ', [ $title ] );
        if(!empty($searchTask)){
            $equal = $this->bean->equals($searchTask);
        } else {
            $equal = FALSE;
        }
        
        if(!empty($searchTask) && !$equal) {
            throw new ValidationException( 'This task already exists.', 512, 400 );
        }*/
    }

}