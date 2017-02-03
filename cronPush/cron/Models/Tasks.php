<?php

namespace Appsolute\Cron\Models;

use R;
use Appsolute\Cron\Models\Resource\Resource;
use Appsolute\Cron\Models\Validation\ValidationException;

Class Tasks extends Database {

	private $tables;
    
    // Get Event by Id
    public function getSingleTask( $id ) {
    	$task = R::findOne( $this->config->getTable('tasks'), 'id = ? AND `deleted_at` IS NULL', [ $id ] );		
		if(!empty($task)){
	    	return $task->toArray();
		} else {
			throw new ValidationException("This mission doesn't exist", 10, 400);
		}
    }		 
    
	// Insert Event
	public function insert( $userId, $entry ) {
		$user = R::findOne($this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ]);
		if(empty($user)){
			throw new ValidationException("This user doesn't exist.", 10, 400);
		} else {
            $task = R::xdispense($this->config->getTable('tasks'));
			$task->checkProprerties($entry);
			$task->title = $entry->title;
			$task->description = (!empty($entry->description)) ? $entry->description : NULL;
			$task->is_complete = 0;

			$task->start_at = NULL;
			$task->end_at = NULL;
            if (!empty($entry->start_at)) {
				$startDate = gmdate('Y-m-d H:i:s', strtotime($entry->start_at));
				$task->start_at = $startDate;
			}
			if (!empty($entry->end_at)) {
				$endDate = gmdate('Y-m-d H:i:s', strtotime($entry->end_at));
				$task->end_at = $endDate;
			}
			$task->created_at = date('Y-m-d H:i:s', strtotime('now'));
			R::store( $task );
            
            if (!empty($task->id)) {
                $userTask = R::xdispense($this->config->getTable('usersTasks'));
				$userTask->user_id = $userId;
				$userTask->task_id = $task->id;
				$userTask->is_creator = 1;
				$userTask->accepted_at = date('Y-m-d H:i:s', strtotime('now'));
				$userTask->created_at = date('Y-m-d H:i:s', strtotime('now'));
                R::store( $userTask );
                
                if (isset($entry->participants) && !empty($entry->participants)) {
		            foreach ($entry->participants as $parts) {
		            	if (!empty($parts->id)) {
			                $dupTask = R::findOne($this->config->getTable('usersContacts'), 'users_id = ? AND contact_id = ? AND `deleted_at` IS NULL AND `accepted_at` IS NOT NULL', [ $userId, $parts->id ]);
			                if (!empty($dupTask)) {
			                	if ($userId != $parts->id) {
									$invTaskNew = R::xdispense($this->config->getTable('usersTasks'));
									$invTaskNew->user_id = $parts->id;
									$invTaskNew->task_id = $task->id;
									$invTaskNew->is_creator = 0;
									$invTaskNew->created_at = date('Y-m-d H:i:s', strtotime('now'));
									R::store( $invTaskNew );
							    }
			                }
		                }
		            }
                }
            }
			return $task->toArray();
		}
    }

    // Update Event
    public function update( $userId, $taskId, $entry ) {
	    $user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );		
		if(!empty($user)){ 
			$userTasks = R::findOne( $this->config->getTable('usersTasks'), 'task_id = ? AND user_id = ? AND `is_creator` = 1 AND `deleted_at` IS NULL', [ $taskId, $userId ] );
			if(!empty($userTasks)){ 
				$taskUpdate = R::findOne($this->config->getTable('tasks'), 'id = ? AND `deleted_at` IS NULL', [ $taskId ] );
				$taskUpdate->checkUpdateProprerties($entry);
				$taskUpdate->completed_at = NULL;
				$taskUpdate->title = $entry->title;
				$taskUpdate->description = (!empty($entry->description)) ? $entry->description : NULL;
				/*if($entry->is_complete == '1') {
				    $taskUpdate->is_complete = 1;
				    $taskUpdate->completed_at = date('Y-m-d H:i:s', strtotime('now'));
				} elseif ($entry->is_complete == '0') {
		            $taskUpdate->is_complete = 0;
				} else {
					throw new ValidationException( 'You have to choose Is Completed status.', 10, 400 );
				}*/
				$taskUpdate->start_at = NULL;
				$taskUpdate->end_at = NULL;
				if (!empty($entry->start_at)) {
					$startDate = gmdate('Y-m-d H:i:s', strtotime($entry->start_at));
					$taskUpdate->start_at = $startDate;
				}
				if (!empty($entry->end_at)) {
					$endDate = gmdate('Y-m-d H:i:s', strtotime($entry->end_at));
					$taskUpdate->end_at = $endDate;
				}
				$taskUpdate->updated_at = date('Y-m-d H:i:s', strtotime('now'));
				R::store( $taskUpdate );

				if (!empty($taskUpdate->id)) {
                    $userTask = R::findOne($this->config->getTable('usersTasks'), "task_id = ? AND user_id = ? AND `is_creator` = '1' AND `deleted_at` IS NULL", [ $taskId, $userId ] );
					$userTask->user_id = $userId;
					$userTask->task_id = $taskId;
					$userTask->is_creator = 1;
					$userTask->updated_at = date('Y-m-d H:i:s', strtotime('now'));
	                R::store( $userTask );
                    
	                if (isset($entry->participants) && !empty($entry->participants)) {
			            foreach ($entry->participants as $parts) {
						    if (!empty($parts->id)) { 
								$dupTask = R::findOne($this->config->getTable('usersContacts'), 'users_id = ? AND contact_id = ? AND `deleted_at` IS NULL AND `accepted_at` IS NOT NULL', [ $userId, $parts->id ]);
								if (!empty($dupTask) && ($userId != $parts->id)) {
									$invTaskUpdt = R::findOne($this->config->getTable('usersTasks'), "task_id = ? AND user_id = ? AND `is_creator` = '0' AND `deleted_at` IS NULL", [ $taskId, $parts->id ] );
									if (!empty($invTaskUpdt)) {
										$invTaskUpdt->task_id = $taskId;
										$invTaskUpdt->user_id = $parts->id;
										$invTaskUpdt->is_creator = 0;
										$invTaskUpdt->updated_at = date('Y-m-d H:i:s', strtotime('now'));
										R::store( $invTaskUpdt );
									} else {
										$invTaskNew = R::xdispense($this->config->getTable('usersTasks'));
										$invTaskNew->task_id = $taskId;
										$invTaskNew->user_id = $parts->id;
										$invTaskNew->is_creator = 0;
										$invTaskNew->created_at = date('Y-m-d H:i:s', strtotime('now'));
										R::store( $invTaskNew );
									}
								}
							}
			            }
	                }
				}
				if (!empty($taskUpdate->id)) {
					$taskDetails = R::getRow( "SELECT A.`id`, A.`title`, A.`description`, A.`is_complete`, A.`completed_at`, A.`start_at`, A.`end_at`, A.`created_at`, A.`updated_at`, B.`accepted_at`
											   FROM `".$this->config->getTable('tasks')."` AS A, `".$this->config->getTable('usersTasks')."` AS B
											   WHERE A.`id` = B.`task_id`
											   AND B.`task_id` = :taskId
											   AND B.`user_id` = :userId
											   AND A.`deleted_at` IS NULL
											   AND B.`deleted_at` IS NULL", array(":taskId" => $taskId, ":userId" => $userId) );
            		$tasksResult = array($taskDetails);
            		$tasks = R::convertToBeans( 'task', $tasksResult );
            		return (new Resource($tasks, 'SingleTask'))->toArray(1);
				    //return $taskUpdate->toArray();
			    }
			} else {
			    throw new ValidationException("You do not have permission to update this mission.", 10, 400);
			}
		} else {
			throw new ValidationException("This user doesn't exist", 10, 400);
		}
    }

    public function updateTaskStatus( $userId, $taskId, $status ) {
        $user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );		
		if(!empty($user)){
            $userTasks = R::findOne( $this->config->getTable('usersTasks'), 'task_id = ? AND user_id = ? AND `is_creator` = 1 AND `deleted_at` IS NULL', [ $taskId, $userId ] );
			if(!empty($userTasks)){
                $taskUpdate = R::findOne($this->config->getTable('tasks'), 'id = ? AND `deleted_at` IS NULL', [ $taskId ] );
                $taskUpdate->completed_at = NULL;
                if($status == '1') {
				    $taskUpdate->is_complete = 1;
				    $taskUpdate->completed_at = date('Y-m-d H:i:s', strtotime('now'));
				} elseif ($status == '0') {
		            $taskUpdate->is_complete = 0;
				} else {
					throw new ValidationException( 'You have to provide a valid status.', 10, 400 );
				}
				$taskUpdate->updated_at = date('Y-m-d H:i:s', strtotime('now'));
				R::store( $taskUpdate );
				//return $taskUpdate->toArray();
			} else {
			    throw new ValidationException("You're not able to change the status for this mission.", 10, 400);
			}
		} else {
			throw new ValidationException("This user doesn't exist", 10, 400);
		}
    }

    // Delete Event
    public function delete( $userId, $taskId ) {
    	$user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );		
		if(!empty($user)){
			$userTasks = R::findOne( $this->config->getTable('usersTasks'), 'task_id = ? AND user_id = ? AND `is_creator` = 1 AND `deleted_at` IS NULL', [ $taskId, $userId ] );
	 		if(!empty($userTasks)){
	 			$taskDelete = R::findOne($this->config->getTable('tasks'), 'id = ? AND `deleted_at` IS NULL', [ $taskId ]);
				$taskDelete->deleted_at = date('Y-m-d H:i:s', strtotime('now'));
			    R::store( $taskDelete );

			    $deleteTasks = R::getAll( "SELECT `id`, `task_id`, `user_id` FROM`".$this->config->getTable('usersTasks')."` WHERE `task_id` = :taskId AND `deleted_at` IS NULL ", 
			    	                      array(":taskId" => $taskId) );
			    if (!empty($deleteTasks)) {
	                foreach ($deleteTasks as $deleteAllTasks) {
	                	$taskDel = R::load( $this->config->getTable('usersTasks'), $deleteAllTasks['id'] );
	                    $taskDel->deleted_at = date('Y-m-d H:i:s', strtotime('now'));
			            R::store( $taskDel );
	                }
			    }
			    if (!empty($taskDelete->deleted_at)) {
			    	$deletedAt = date('Y-m-d\TH:i:s\Z', strtotime($taskDelete->deleted_at));
			    } else {
			    	$deletedAt = NULL;
			    }
			    $deletedDetails = array("id" => (int) $taskId, "user_id" => (int) $userId, "deleted_at" => $deletedAt);
			    return $deletedDetails;
			} else {
				throw new ValidationException("You do not have permission to delete this mission.", 10, 400);
			}
		} else {
			throw new ValidationException("This user doesn't exist", 10, 400);
		}
    }

    // Accept Task invitation
    public function acceptTask( $userId, $taskId ) {
    	$user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );		
		if(!empty($user)){
			$taskAccept = R::findOne($this->config->getTable('usersTasks'), 'task_id = ? AND user_id = ? AND `is_creator` = ? AND `accepted_at` IS NULL AND `refused_at` IS NULL AND `deleted_at` IS NULL', [ $taskId, $userId, '0' ]);
	 		if(!empty($taskAccept)){
				$taskAccept->accepted_at = date('Y-m-d H:i:s', strtotime('now'));
			    R::store( $taskAccept );

			    if (!empty($taskAccept)) {
                    $taskDetails = R::getAll( "SELECT A.`id`, A.`title`, A.`created_at`, B.`user_id`, B.`accepted_at`
												FROM `".$this->config->getTable('tasks')."` AS A, `".$this->config->getTable('usersTasks')."` AS B
												WHERE A.`id` = B.`task_id`
												AND B.`task_id` = :taskId
												AND B.`user_id` = :userId
												AND A.`deleted_at` IS NULL
												AND B.`deleted_at` IS NULL
												LIMIT 0 , 1", array(":taskId" => $taskId, ":userId" => $userId) );
                    if (!empty($taskDetails)) {
                    	$missions = R::convertToBeans( 'mission', $taskDetails );
						if (!empty($missions)) {
						    return (new Resource($missions, 'AcceptedTasks'))->toArray(1);
				        }
                    }
			    }
			} else {
				throw new ValidationException("Invalid mission.", 10, 400);
			}
		} else {
			throw new ValidationException("This user doesn't exist", 10, 400);
		}
    }

    // Refuse Task invitation
    public function refuseTask( $userId, $taskId ) {
    	$user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );		
		if(!empty($user)){
			$taskRefuse = R::findOne($this->config->getTable('usersTasks'), 'task_id = ? AND user_id = ? AND `is_creator` = ? AND `accepted_at` IS NULL AND `refused_at` IS NULL AND `deleted_at` IS NULL', [ $taskId, $userId, '0' ]);
	 		if(!empty($taskRefuse)){
				$taskRefuse->refused_at = date('Y-m-d H:i:s', strtotime('now'));
			    R::store( $taskRefuse );
			} else {
				throw new ValidationException("Invalid mission.", 10, 400);
			}
		} else {
			throw new ValidationException("This user doesn't exist", 10, 400);
		}
    }

    // Get Task by Id
    public function findOneById( $taskId, $userId ) {
    	$userTask = R::findOne($this->config->getTable('usersTasks'), 'task_id = ? AND user_id = ? AND `refused_at` IS NULL AND `deleted_at` IS NULL', [ $taskId, $userId ]);		
		if(!empty($userTask)){
	    	$taskDetails = R::getAll( "SELECT A.`id`, A.`title`, A.`description`, A.`is_complete`, A.`completed_at`, A.`start_at`, A.`end_at`, A.`created_at`, A.`updated_at`, B.`accepted_at`
										FROM `".$this->config->getTable('tasks')."` AS A, `".$this->config->getTable('usersTasks')."` AS B
										WHERE A.`id` = B.`task_id`
										AND B.`task_id` = :taskId
										AND B.`user_id` = :userId
										AND A.`deleted_at` IS NULL
										AND B.`deleted_at` IS NULL
										LIMIT 0 , 1", array(":taskId" => $taskId, ":userId" => $userId) );
            $tasks = R::convertToBeans( 'task', $taskDetails );
            if (!empty($tasks)) {
			    return (new Resource($tasks, 'SingleTask'))->toArray(1);
	        } else {
			    throw new ValidationException("This mission doesn't exist.", 10, 400);
			}
		} else {
			throw new ValidationException("Invalid details.", 10, 400);
		}
    }

    // Get All Completed Tasks
	public function getAllCompletedTasks($userId, $l1, $l2) {
		$user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );		
		if(!empty($user)){
			$sql = "SELECT A.`id`, A.`title`, A.`completed_at`, A.`start_at`, A.`end_at`, A.`created_at`, B.`user_id` 
			        FROM `".$this->config->getTable('tasks')."` AS A, `".$this->config->getTable('usersTasks')."` AS B 
	                WHERE A.`id` = B.`task_id`
	                AND B.`user_id` = :userId
	                AND A.`is_complete` = 1
	                AND B.`accepted_at` IS NOT NULL
	                AND B.`refused_at` IS NULL
	                AND A.`deleted_at` IS NULL
	                AND B.`deleted_at` IS NULL 
	                ORDER BY A.`completed_at` ASC LIMIT :l1,:l2";
		    $rows = R::getAll( $sql, array(":userId" => $userId, ":l1" => $l1, ":l2" => $l2) );
			$userTasks = R::convertToBeans( 'tasks', $rows );
			if (!empty($userTasks)) {
			    return (new Resource($userTasks, 'ValidatedTasks'))->toArray(1);
	        }
        } else {
			throw new ValidationException("This user doesn't exist", 10, 400);
		}
	}
    
    // Completed tasks count
	public function completedTasksCount($userId) {
		$sql = "SELECT A.`id`, B.`user_id` 
		        FROM `".$this->config->getTable('tasks')."` AS A, `".$this->config->getTable('usersTasks')."` AS B 
                WHERE A.`id` = B.`task_id`
                AND B.`user_id` = :userId
                AND A.`is_complete` = 1
                AND B.`accepted_at` IS NOT NULL
                AND B.`refused_at` IS NULL
                AND A.`deleted_at` IS NULL
                AND B.`deleted_at` IS NULL";
	    $rows = R::getAll( $sql, array(":userId" => $userId) );
	    if (!empty($rows)) {
	    	return count($rows);
	    }
    }

    // Get All Incompleted Tasks
	public function getAllIncompletedTasks($userId, $l1, $l2) {
		$user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );		
		if(!empty($user)){
			$sql = "SELECT A.`id`, A.`title`, A.`completed_at`, A.`start_at`, A.`end_at`, A.`created_at`, B.`user_id` 
			        FROM `".$this->config->getTable('tasks')."` AS A, `".$this->config->getTable('usersTasks')."` AS B 
	                WHERE A.`id` = B.`task_id`
	                AND B.`user_id` = :userId
	                AND A.`is_complete` = 0
	                AND B.`accepted_at` IS NOT NULL
	                AND B.`refused_at` IS NULL
	                AND A.`deleted_at` IS NULL
	                AND B.`deleted_at` IS NULL
	                ORDER BY A.`end_at` ASC LIMIT :l1,:l2";
		    $rows = R::getAll( $sql, array(":userId" => $userId, ":l1" => $l1, ":l2" => $l2) );
			$userTasks = R::convertToBeans( 'tasks', $rows );
			if (!empty($userTasks)) {
			    return (new Resource($userTasks, 'ValidatedTasks'))->toArray(1);
	        }
        } else {
			throw new ValidationException("This user doesn't exist", 10, 400);
		}
	}

	// Incompleted tasks count
	public function incompletedTasksCount($userId) {
		$sql = "SELECT A.`id` , B.`user_id` 
		        FROM `".$this->config->getTable('tasks')."` AS A, `".$this->config->getTable('usersTasks')."` AS B 
                WHERE A.`id` = B.`task_id`
                AND B.`user_id` = :userId
                AND A.`is_complete` = 0
                AND B.`accepted_at` IS NOT NULL
                AND B.`refused_at` IS NULL
                AND A.`deleted_at` IS NULL
                AND B.`deleted_at` IS NULL";
	    $rows = R::getAll( $sql, array(":userId" => $userId) );
	    if (!empty($rows)) {
	    	return count($rows);
	    }
    }

	// Get All Invited Incompleted Tasks
	public function getIncompleteInvTasks($userId) {
		$user = R::findOne( $this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ] );		
		if(!empty($user)){
			$sql = "SELECT A.`id`, A.`title`, A.`description`, A.`is_complete`, A.`completed_at`, A.`start_at`, A.`end_at`, A.`created_at`, A.`updated_at`, B.`accepted_at`
			        FROM `".$this->config->getTable('tasks')."` AS A, `".$this->config->getTable('usersTasks')."` AS B 
	                WHERE A.`id` = B.`task_id`
	                AND B.`user_id` = :userId
	                AND A.`is_complete` = 0
	                AND B.`accepted_at` IS NULL
	                AND B.`refused_at` IS NULL
	                AND A.`deleted_at` IS NULL 
	                AND B.`deleted_at` IS NULL
	                ORDER BY A.`end_at` ASC";
		    $rows = R::getAll( $sql, array(":userId" => $userId) );
			$userTasks = R::convertToBeans( 'tasks', $rows );
			if (!empty($userTasks)) {
			    return (new Resource($userTasks, 'InviteTask'))->toArray(1);
	        }
        } else {
			throw new ValidationException("This user doesn't exist", 10, 400);
		}
	}

	// Get pending tasks count
    public function pendingTasksCnt( $userId ) {
	    $user = R::findOne($this->config->getTable('users'), 'id = ? AND `deleted_at` IS NULL', [ $userId ]);
		if(empty($user)){
			throw new ValidationException("This user doesn't exist.", 10, 400);
		} else {
			$tasksDetails = R::getAll( "SELECT `id` FROM `".$this->config->getTable('usersTasks')."` 
				                        WHERE `user_id` = :userId
				                        AND `is_creator` = 0
				                        AND `accepted_at` IS NULL 
				                        AND `deleted_at` IS NULL", array(":userId" => $userId) );
			return count($tasksDetails);
		}
    }

    // Task Creator
    public function getTaskCreator( $taskId ) {
    	if (!empty($taskId)) {
    		$author = array();
    		$result = array();
            $getTaskAuthor = R::getAll( "SELECT A.`id`, A.`firstname`, A.`lastname`, A.`avatar`
			                              FROM `users` AS A, `users_tasks` AS B
			                              WHERE A.`id` = B.`user_id`
			                              AND B.`task_id` = :taskId
			                              AND B.`is_creator` = 1
			                              AND A.`deleted_at` IS NULL
                                          AND B.`deleted_at` IS NULL LIMIT 0,1", array(":taskId" => $taskId) );
            if (!empty($getTaskAuthor)) {
                $author['id'] = $getTaskAuthor[0]['id'];
                $author['firstname'] = $getTaskAuthor[0]['firstname'];
                $author['lastname'] = $getTaskAuthor[0]['lastname'];
                /*if (file_exists(UPLOAD_FOLDER.'user/'.$getTaskAuthor[0]['avatar']) && !empty($getTaskAuthor[0]['avatar'])) {
					$author['avatar'] = UPLOAD_URL.'user/'.$getTaskAuthor[0]['avatar'];
				} else {
					$author['avatar'] = NULL;
				}*/
				$result = $author;
            }
            return $result;
    	}
    }

    // Task Participants
    public function getTaskParticipants( $taskId ) {
    	if (!empty($taskId)) {
    		$members = array();
    		$result = array();
            $getTaskParticipants = R::getAll( "SELECT A.`id`, A.`firstname`, A.`lastname`, A.`avatar`, B.`is_creator`, B.`accepted_at`
										  FROM `users` AS A, `users_tasks` AS B
										  WHERE A.`id` = B.`user_id`
										  AND B.`task_id` = :taskId
										  AND B.`is_creator` = 0
										  AND A.`deleted_at` IS NULL
										  AND B.`deleted_at` IS NULL", array(":taskId" => $taskId) );
            if (!empty($getTaskParticipants)) {
                foreach ($getTaskParticipants as $participants) {
                    $members['id'] = $participants['id'];
                    $members['firstname'] = $participants['firstname'];
                    $members['lastname'] = $participants['lastname'];
                    if (!empty($participants['accepted_at'])) {
                        $members['has_accepted'] = '1';   
                    } else {
                    	$members['has_accepted'] = '0';
                    }
                    /*if (file_exists(UPLOAD_FOLDER.'user/'.$participants['avatar']) && !empty($participants['avatar'])) {
						$members['avatar'] = UPLOAD_URL.'user/'.$participants['avatar'];
					} else {
						$members['avatar'] = NULL;
					}*/
					$result[] = $members;
                }
            }
            return $result;
    	}
    }
	
}