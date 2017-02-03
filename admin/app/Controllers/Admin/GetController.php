<?php

namespace Appsolute\Backend\Controllers\Admin;

use Slim\Slim;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Users;

Class GetController extends Controllers\Controller {

	public function home(){
		$user = new Users($this->configManager('Database'));
		/*$this->data['user_pending_count'] = $user->getUserPendingCount();
		$this->data['user_pending_fb_count'] = $user->getUserPendingFacebookCount();
		$this->data['user_pending_google_count'] = $user->getUserPendingGoogleCount();

		$this->data['vagone_recipes_pending_count'] = $user->getVagoneRecipesPendingCount();
		$this->data['users_recipes_pending_count'] = $user->getUsersRecipesPendingCount();

		$this->data['event_count'] = $user->getEventCount();
		$this->data['task_count'] = $user->getTaskCount();*/

		/*$filter = array();	
		$queryStr = (isset($_GET['q']) && $_GET['q'] != '') ? $_GET['q'] : "";	
		if(isset($_GET['q'])) {
			if(!empty($queryStr) && $queryStr == 'all'){
				$filter['filterby'] = $queryStr;
			} elseif(!empty($queryStr) && $queryStr == 'year'){
				$filter['filterby'] = $queryStr;
			}
		} else {
			$filter['filterby'] = "home";
		}
		$this->data['filter'] = $filter; */
	}

	public function loginView(){
		if (isset($_COOKIE['remEmail']) && isset($_COOKIE['remPass'])) {
            $this->data['remEmail'] = $_COOKIE['remEmail'];
            $this->data['remPass'] = $_COOKIE['remPass'];
        }
	}


}