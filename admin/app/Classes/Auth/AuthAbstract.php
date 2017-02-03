<?php

namespace Appsolute\Backend\Classes\Auth;

/**
 * Auth abstract class
 *
 * This class implement the required method from the interface.
 * It also add some "useful" static method.
 *
 */
Abstract Class AuthAbstract {

	/**
	 * Database instance.
	 * @var Appsolute\Backend\Classes\Auth\DatabaseInterface
	 */
	protected $database;

	public function __construct( DatabaseInterface $database ) {
		$this->database = $database;
	}

	/**
	 * Convenient method to hash the password of a user.
	 * @param  string $password Password to hash
	 * @return string           Password hash
	 */
	public static function passwordHash( $password ) {
		return password_hash($password, PASSWORD_BCRYPT, array("cost" => 12));
	}

	/**
	 * Convenient method to compare a password to a hash.
	 * @param  string 	$passwordToCheck Password
	 * @param  string 	$passwordStored  Password hash
	 * @return boolean
	 */
	public static function passwordVerify( $passwordToCheck, $passwordStored ) {
		return password_verify($passwordToCheck, $passwordStored);
	}

	/**
	 * Insert a session in database.
	 * @param  array  		$custom         Custom field that you wish to use in your Database implementation
	 * @param  date|null 	$expirationDate Expiration date of the token
	 * @return string                 		Token to store in the cookie. ($key|$token)
	 */
	public function insertSession( array $custom = array(), $expirationDate = null ) {
		$expirationDate = (empty($expirationDate)) ? date("Y-m-d H:i:s", strtotime("+1 day")) : $expirationDate;
		$token = self::genSessionToken();
		$data = array(
			"key" => uniqid().mt_rand(5, 12),
			"token" => password_hash($token, PASSWORD_DEFAULT),
			"expires" => $expirationDate
		);
		$array = (!empty($custom)) ? array_merge($data, $custom) : $data;
		$id = $this->database->insertSession( $array );
		return $data['key']."|".$token;
	}

	/**
	 * Check if the user session exists in database.
	 * @param  string  $sessionToken 	The token stored in the cookie. ($key|$token)
	 * @return int              		-2 = wrong session, -1 = session expired, 0 = session doesn't exist, 1 = ok
	 */
	public function isAllowed( $sessionToken ) {
		$cookie = explode("|", $sessionToken);
		$session = $this->database->getSession( $cookie[0] );
		if(!empty($session)) {
			$expireTimestamp = strtotime($session['expires']);
			if($expireTimestamp <= time()) {
				$this->database->removeSession( $cookie[0] );
				$isAllowed = -1; //expired
			} else {
				if(password_verify($cookie[1], $session['session'])) {
					$isAllowed = 1; //ok
				} else {
					$isAllowed = -2; //wrong session
					$this->database->removeSession( $cookie[0] );
				}
			}
		} else {
			$isAllowed = 0; //session doesn't exists
		}
		return $isAllowed;
	}

	/**
	 * Remove session from database.
	 * @param  string $key Key selector for the token
	 * @return void
	 */
	public function invalidateSession( $key ) {
		$this->database->removeSession( $key );
	}

	/**
	 * Generate a random session token.
	 * @return string Token
	 */
	private static function genSessionToken() {
		$try = 0;
		do {
			$bytes = openssl_random_pseudo_bytes(64, $isStrong);
			$try++;
		} while($isStrong == false && $try <= 10);
		
		$token = bin2hex($bytes);
		return $token;
	}

}