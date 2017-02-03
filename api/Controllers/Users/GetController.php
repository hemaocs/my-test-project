<?php

namespace Appsolute\Api\Controllers\Users;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Api\Controllers;
use Appsolute\Api\Models\Users;

Class GetController extends Controllers\Controller {

	public function getSingleUser() {
		$users = new Users($this->configManager('Database'));
		if (isset($this->args['id']) && !empty($this->args['id'])) {
			$userId = $this->args['id'];
		}
		$this->data['user'] = $users->findOneById( $userId );
		$this->message = "Successfully retrieved the user.";
	}
	
	public function getAllUsers() {
		$users = new Users($this->configManager('Database'));

		$l1 = ($this->args['page'] > 0) ?($this->args['page']-1)*$this->args['limit'] : 1;
		$l2 = ($this->args['limit'] > 0) ? $this->args['limit'] : 10;

		$nbUser = $users->count();
		$first = 1;
		$last = ceil($nbUser/$l2);
		$next = ($this->args['page']<$last) ? $this->args['page']+1 : null;
		$prev = ($this->args['page']>1) ? $this->args['page']-1 : null;

		$result = $users->getAll($l1, $l2);
		$this->data['users'] = $result['data'];

		$this->meta = array(
			'first'	=> BASE_URL."api/users/page/{$first}/limit/{$l2}",
			'last'	=> BASE_URL."api/users/page/{$last}/limit/{$l2}",
			'next'	=> (!empty($next)) ? BASE_URL."api/users/page/{$next}/limit/{$l2}" : null,
			'prev'	=> (!empty($prev)) ? BASE_URL."api/users/page/{$prev}/limit/{$l2}" : null
		);
		$this->message = "Successfully retrieved the users.";
	}
    
    //Get all user invites
	public function userInvites() {
		$users = new Users($this->configManager('Database'));
		$result = $users->userInvites( $this->args['id'] );
		$this->data['users'] = $result['data'];
		$this->message = "Successfully retrieved the user invites.";
	}
    
    // Get search users
	public function getSearchUsers() {
		$users = new Users($this->configManager('Database'));

		$l1 = ($this->args['page'] > 0) ?($this->args['page']-1)*$this->args['limit'] : 1;
		$l2 = ($this->args['limit'] > 0) ? $this->args['limit'] : 10;

		$nbUser = $users->searchUsersCount($this->args['id'], $this->args['key']);
		$first = 1;
		$last = ceil($nbUser/$l2);
		$next = ($this->args['page']<$last) ? $this->args['page']+1 : null;
		$prev = ($this->args['page']>1) ? $this->args['page']-1 : null;

		$result = $users->getSearchUsers($this->args['id'], $this->args['key'], $l1, $l2);
		$this->data['users'] = $result['data'];

		$this->meta = array(
			'first'	=> BASE_URL."api/users/".$this->args['id']."/search/".$this->args['key']."/page/{$first}/limit/{$l2}",
			'last'	=> BASE_URL."api/users/".$this->args['id']."/search/".$this->args['key']."/page/{$last}/limit/{$l2}",
			'next'	=> (!empty($next)) ? BASE_URL."api/users/".$this->args['id']."/search/".$this->args['key']."/page/{$next}/limit/{$l2}" : null,
			'prev'	=> (!empty($prev)) ? BASE_URL."api/users/".$this->args['id']."/search/".$this->args['key']."/page/{$prev}/limit/{$l2}" : null
		);
		$this->message = "Successfully retrieved the users.";
	}

}