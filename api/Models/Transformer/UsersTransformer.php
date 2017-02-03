<?php

namespace Appsolute\Api\Models\Transformer;

use RedbeanPHP\OODBBean;
use League\Fractal;

class UsersTransformer extends Fractal\TransformerAbstract {

	public function transform(OODBBean $user) { //print_r($user);exit();
		if (file_exists(UPLOAD_FOLDER.'user/'.$user->avatar) && !empty($user->avatar)) {
			$icon = UPLOAD_URL.'user/'.$user->avatar;
		} else {
			$icon = NULL;
		}
	    $resource = [
			'id'      	  => (int) $user->id,
			'avatar'      => $icon,
			'firstname'   => $user->firstname,
			'lastname'    => $user->lastname,
			'email'       => $user->email,
			'is_active'   => $user->is_active
	    ];

	    /*if (isset($user->is_friend)) {
			$resource['is_friend'] = $user->is_friend;
		}

	    if (isset($user->contact_id)) {
		    if (isset($user->calendar_color)) {
				$resource['calendar_color'] = $user->calendar_color;
			} else {
				$resource['calendar_color'] = NULL;
			}

			if (isset($user->accepted_at)) {
				if (!empty($user->accepted_at)) {
                    $resource['is_accepted'] = 'yes';
                } else {
                	$resource['is_accepted'] = 'no';
                }
			} else {
				$resource['is_accepted'] = 'no';
			}

			if (isset($user->status)) {
				$resource['status'] = $user->status;
			}
	    }*/
	    return $resource;
	}
}