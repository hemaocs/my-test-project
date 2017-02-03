<?php

namespace Appsolute\Api\Models;

use R;

Class Clients extends Database {

    public function find( $user, $secret ) {
		$client = R::findOne( $this->config->getTable('clients'), 'client_id = ? AND client_secret = ?', [ $user, $secret ] );
		if(!empty($client)){
			return TRUE;
		} else {
			return FALSE;
		}
    }

}