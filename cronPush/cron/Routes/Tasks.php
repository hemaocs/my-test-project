<?php

use Appsolute\Cron\Controllers\Tasks;

//Define a group
$this->app->group('/users', function () {
    
    //Get single task
    $this->app->get('/:userId/task/:taskId', function ($userId, $taskId) {
        $data = new Tasks\GetController( 'getSingleTask', ['userId' => $userId, 'taskId' => $taskId] );
        $data->send();
    });

    //Get all completed tasks
    $this->app->get('/:userId/tasks/completed(/page/:page(/limit/:limit))', function ($userId, $page = 1, $limit = 25) {
        $data = new Tasks\GetController( 'getCompletedTasks', ['userId' => $userId, 'page' => (int)$page, 'limit' => (int)$limit] );
        $data->send();
    });

    //Get all incompleted tasks
    $this->app->get('/:userId/tasks(/page/:page(/limit/:limit))', function ($userId, $page = 1, $limit = 25) {
        $data = new Tasks\GetController( 'getIncompletedTasks', ['userId' => $userId, 'page' => (int)$page, 'limit' => (int)$limit] );
        $data->send();
    });
	
});