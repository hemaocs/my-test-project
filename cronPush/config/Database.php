<?php

namespace Appsolute\Config;

Class Database implements ConfigManagerInterface {

	private $tables = array(
		'clients'                  => 'auth_client',
		'users'                    => 'users',
		'usersContacts'            => 'users_contacts',
		'events'                   => 'events',
		'usersCalendars'           => 'users_calendars',
		'tasks'                    => 'tasks',
		'usersTasks'               => 'users_tasks',
		'ideas'                    => 'ideas',
		'usersIdeas'               => 'users_ideas',
		'recipes'                  => 'recipes',
		'usersRecipes'             => 'users_recipes',
		'ingredients'              => 'ingredients',
		'comments'                 => 'comments',
		'recipeComments'           => 'recipes_comments',
		'reserves'                 => 'reserves',
		'reservesHasUsers'         => 'reserves_has_users',
		'reservesProductsNeeded'   => 'reserves_products_needed',
		'reservesProductsSuggest'  => 'reserves_products_suggested',
		'reserveRoles'             => 'reserve_roles',
		'reserveRecipes'           => 'reserve_recipes',
		'usersReserves'            => 'users_reserves',
		'categories'               => 'categories',
		'products'                 => 'products',
		'productCategory'          => 'product_category',
		'season'                   => 'season',
		'recipeIngredients'        => 'recipe_ingredients'
	);

	public function getTable($tableName){
		if(array_key_exists($tableName, $this->tables)){
			return $this->tables[$tableName];
		} else {
			throw new \Exception("The table {$tableName} doesn't exist.");
		}
	}

}