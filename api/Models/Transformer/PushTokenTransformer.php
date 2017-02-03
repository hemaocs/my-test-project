<?php
namespace Appsolute\Api\Models\Transformer;

use RedbeanPHP\OODBBean;
use League\Fractal;
use Appsolute\Api\Models\Users;

class PushTokenTransformer extends Fractal\TransformerAbstract { 
	
	public function transform(OODBBean $token) {
	   return [
	        'id'		     => (int) $token->id,
			'app_id'         => $token->app_id,
			'platform'       => $token->platform,
			'mode'           => $token->mode,
			'token'          => $token->token,
			'devicename'     => $token->devicename,
			'app_version'    => $token->app_version,
			'created_at'     => $token->created_at,
			'updated_at'     => $token->updated_at	       
	    ];
	}

}