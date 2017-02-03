<?php

namespace Appsolute\Cron\Models\Transformer;

use RedbeanPHP\OODBBean;
use League\Fractal;
use Appsolute\Cron\Models\Tasks;

class ValidatedTasksTransformer extends Fractal\TransformerAbstract {

	public function transform(OODBBean $task) {
		$tasks = new Tasks();
		$getAuthor = $tasks->getTaskCreator( $task->id );
        $getParticipants = $tasks->getTaskParticipants( $task->id );
        
        if (isset($getAuthor['id']) && !empty($getAuthor['id'])) {
        	$authorId = $getAuthor['id'];
        } else {
            $authorId = NULL;
        }

		if (!empty($task->completed_at)) {
			$completedAt = date('Y-m-d\TH:i:s\Z', strtotime($task->completed_at));
		} else {
			$completedAt = null;
		}

		if (!empty($task->start_at)) {
			$startAt = date('Y-m-d\TH:i:s\Z', strtotime($task->start_at));
		} else {
			$startAt = null;
		}

		if (!empty($task->end_at)) {
			$endAt = date('Y-m-d\TH:i:s\Z', strtotime($task->end_at));
		} else {
			$endAt = null;
		}

		if (!empty($task->created_at)) {
			$createAt = date('Y-m-d\TH:i:s\Z', strtotime($task->created_at));
		} else {
			$createAt = null;
		}

		return [
			'id'      	   => (int) $task->id,
			'title'        => $task->title,
			'start_at'     => $startAt,
			'end_at'       => $endAt,
			'completed_at' => $completedAt,
			'user_id'      => $authorId,
			'created_at'   => $createAt,
			'participants' => $getParticipants
	    ];
	}
}