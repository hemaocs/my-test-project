<?php

namespace Appsolute\Backend\Classes\Auth;

/**
 * Auth base class
 *
 * This class is the one that should be instancied.
 * You can add additional method to meet the requirements of your application.
 * Method name should be different from the one in AuthAbstract.
 *
 * Don't forget to implement DatabaseInterface before using this class.
 * 
 */
Class Auth extends AuthAbstract {

	/**
	 * Retrieve the user of a token.
	 * @param  string $cookie 	The token stored in the cookie. ($key|$token)
	 * @return array         	Array with data of the user.
	 */
	public function getUser( $cookie ) {
		$cookie = explode("|", $cookie);
		return $this->database->getUserByKey( $cookie[0] );
	}

}