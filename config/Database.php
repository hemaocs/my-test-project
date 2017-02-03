<?php

namespace Appsolute\Config;

Class Database implements ConfigManagerInterface {

	private $tables = array(
		'clients'                  => 'auth_client',
		'users'                    => 'users',	
		'reserves'                 => 'reserves',
		'categories'               => 'categories',
		'products'                 => 'products',
		'productCategory'          => 'product_category',
		'pushToken'                => 'push_token'
	);

	public function getTable($tableName){
		if(array_key_exists($tableName, $this->tables)){
			return $this->tables[$tableName];
		} else {
			throw new \Exception("The table {$tableName} doesn't exist.");
		}
	}

}