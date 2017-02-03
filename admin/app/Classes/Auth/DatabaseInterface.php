<?php

namespace Appsolute\Backend\Classes\Auth;

/**
 * Database interface
 *
 * You MUST implement this interface in order to use the Auth class.
 * Your implementation must be the 1st argument from the Auth constructor.
 *
 */
Interface DatabaseInterface {

	/**
	 * Method for inserting the data of the session in the database
	 * @param  Array  $data The data with at minimum the following column [key_selector, session, expires]
	 */
	public function insertSession( Array $data );

	/**
	 * Get the session array from database based on the key selector.
	 * @param  string $key You must find the object based on this key selector.
	 * @return array       The data with at minimum the following column [key_selector, session, expires]
	 */
	public function getSession( $key );

	/**
	 * Remove a session based on the key selector
	 * @param  string $key You must find the row to deleted with this key selector.
	 */
	public function removeSession( $key );

}