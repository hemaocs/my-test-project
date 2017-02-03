<?php 

namespace Appsolute\Cron\Models\Validation;

use R;
use Appsolute\Cron\Models\Resource\Resource;

class PushToken extends Resource { 
	
	public function checkProperties( $tokenData ){ 

		if(!isset($tokenData->platform)){
			throw new ValidationException( 'Platform attribute is missing.', 10, 400 );
		}

		if(!isset($tokenData->mode)){
			throw new ValidationException( 'Mode attribute is missing.', 10, 400 );
		}

		if(!isset($tokenData->token)){
			throw new ValidationException( 'Token attribute is missing.', 10, 400 );
		}
		
		if(!isset($tokenData->app_version)){
			throw new ValidationException( 'App Version attribute is missing.', 10, 400 );
		}

		if(!isset($tokenData->app_id)){
			throw new ValidationException( 'App id attribute is missing.', 10, 400 );
		}
	}
	
	public function update() { 
		if(isset($this->bean->platform)){ 
			$platform = trim($this->bean->platform);
			if(empty($platform)) {
				throw new ValidationException( 'You have to provide platform field.', 10, 400 );
			} else {
				if (($platform != 'ios') && ($platform != 'android')) {
                    throw new ValidationException( 'Invalid platform.', 10, 400 );
				}
			}
		}

		if(isset($this->bean->mode)){
			$mode = trim($this->bean->mode);
			if(empty($mode)) {
				throw new ValidationException( 'You have to provide mode field.', 10, 400 );
			} else {
				if (($mode != 'dev') && ($mode != 'prod')) {
                    throw new ValidationException( 'Invalid mode.', 10, 400 );
				}
			}
		}

		if(isset($this->bean->token)){
			$token = trim($this->bean->token);
			if(empty($token)) {
				throw new ValidationException( 'You have to provide token field.', 10, 400 );
			}
		}

		if(isset($this->bean->app_version)){
			$appVersion = trim($this->bean->app_version);
			if(empty($appVersion)) {
				throw new ValidationException( 'You have to provide app version field.', 10, 400 );
			}
		}		
	}

}