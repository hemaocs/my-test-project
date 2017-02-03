<?php

namespace Appsolute\Config;

Class Database implements ConfigManagerInterface {

	private $tables = array(
		'admin' 		     => 'admin',
		'users_sessions'     => 'adminsessions',
		'users'              => 'users',
		'usersContacts'      => 'users_contacts',
		'events'             => 'events',
		'usersCalendars'     => 'users_calendars',
		'tasks'              => 'tasks',
		'usersTasks'         => 'users_tasks',
		'ideas'              => 'ideas',
		'usersIdeas'         => 'users_ideas',
		'recipes'            => 'recipes',
		'usersRecipes'       => 'users_recipes',
		'ingredients'        => 'ingredients',
		'comments'           => 'comments',
		'recipeComments'     => 'recipes_comments',
		'reserves'           => 'reserves',
		'reservesHasUsers'   => 'reserves_has_users',
		'reservesProducts'   => 'reserves_products',
		'reserveRoles'       => 'reserve_roles',
		'usersReserves'      => 'users_reserves',
		'categories'         => 'categories',
		'products'           => 'products',
		'season'             => 'season',
		'seasonProducts'     => 'season_products',
		'product_category'   => 'product_category',
		'recipe_ingredients' => 'recipe_ingredients',
		'push_token'         => 'push_token'
		
	);

	public function getTable($tableName){
		if(array_key_exists($tableName, $this->tables)){
			return $this->tables[$tableName];
		} else {
			throw new \Exception("The table {$tableName} doesn't exist.");
		}
	}

	private $passphrases = array(
		'com.project.projectapp',
        'fr.appsolute.projectinapp',
        'fr.appsolute.projectinapp.jean'
	);

	public function getAllCertificates(){
		return $this->passphrases;
	}

}