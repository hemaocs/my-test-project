<?php

namespace Appsolute\Cron\Controllers\Tasks;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Cron\Controllers;
use Appsolute\Cron\Models\Tasks;
use Appsolute\Cron\Models\Users;

Class GetController extends Controllers\Controller {

	public function getSingleTask() {
		$task = new Tasks($this->configManager('Database'));
		if (isset($this->args['taskId']) && !empty($this->args['taskId'])) {
			$taskId = $this->args['taskId'];
		}
		if (isset($this->args['userId']) && !empty($this->args['userId'])) {
			$userId = $this->args['userId'];
		}
		$result = $task->findOneById( $taskId, $userId );
		$this->data = $result['data'][0];

		$this->message = "Successfully retrieved the mission.";
	}

	public function getCompletedTasks() {
		$tasks = new Tasks($this->configManager('Database'));
		$users = new Users($this->configManager('Database'));
        if (isset($this->args['userId']) && !empty($this->args['userId'])) {
			$userId = $this->args['userId'];
		}
		$l1 = ($this->args['page'] > 0) ? ($this->args['page']-1)*$this->args['limit'] : 1;
		$l2 = ($this->args['limit'] > 0) ? $this->args['limit'] : 25;

		$nbTask = $tasks->completedTasksCount($userId);
		$first = 1;
		$last = ceil($nbTask/$l2);
		$next = ($this->args['page']<$last) ? $this->args['page']+1 : null;
		$prev = ($this->args['page']>1) ? $this->args['page']-1 : null;

        $resultAll = $tasks->getAllCompletedTasks($userId, $l1, $l2);
	    $this->data['missions'] = $resultAll['data'];
        
        $this->meta = array(
			'first'	=> BASE_URL."cron/users/".$userId."/tasks/completed/page/{$first}/limit/{$l2}",
			'last'	=> BASE_URL."cron/users/".$userId."/tasks/completed/page/{$last}/limit/{$l2}",
			'next'	=> (!empty($next)) ? BASE_URL."cron/users/".$userId."/tasks/completed/page/{$next}/limit/{$l2}" : null,
			'prev'	=> (!empty($prev)) ? BASE_URL."cron/users/".$userId."/tasks/completed/page/{$prev}/limit/{$l2}" : null
		);

        $this->message = "Successfully retrieved the completed missions for the user.";
	}

	public function getIncompletedTasks() {
		$tasks = new Tasks($this->configManager('Database'));
		$users = new Users($this->configManager('Database'));
        if (isset($this->args['userId']) && !empty($this->args['userId'])) {
			$userId = $this->args['userId'];
		}
		$l1 = ($this->args['page'] > 0) ? ($this->args['page']-1)*$this->args['limit'] : 1;
		$l2 = ($this->args['limit'] > 0) ? $this->args['limit'] : 25;

		$nbTask = $tasks->incompletedTasksCount($userId);
		$first = 1;
		$last = ceil($nbTask/$l2);
		$next = ($this->args['page']<$last) ? $this->args['page']+1 : null;
		$prev = ($this->args['page']>1) ? $this->args['page']-1 : null;

        $resultAll = $tasks->getAllIncompletedTasks($userId, $l1, $l2);
	    $this->data['missions'] = $resultAll['data'];

	    $resultAllInv = $tasks->getIncompleteInvTasks($userId);
	    $this->data['missions_invites'] = $resultAllInv['data'];
        
        $this->meta = array(
			'first'	=> BASE_URL."cron/users/".$userId."/tasks/page/{$first}/limit/{$l2}",
			'last'	=> BASE_URL."cron/users/".$userId."/tasks/page/{$last}/limit/{$l2}",
			'next'	=> (!empty($next)) ? BASE_URL."cron/users/".$userId."/tasks/page/{$next}/limit/{$l2}" : null,
			'prev'	=> (!empty($prev)) ? BASE_URL."cron/users/".$userId."/tasks/page/{$prev}/limit/{$l2}" : null
		);

        $this->message = "Successfully retrieved the incomplete missions for the user.";
	}

}