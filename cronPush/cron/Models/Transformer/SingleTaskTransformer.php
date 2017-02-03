<?php

namespace Appsolute\Cron\Models\Transformer;

use RedbeanPHP\OODBBean;
use League\Fractal;
use Appsolute\Cron\Models\Tasks;

class SingleTaskTransformer extends Fractal\TransformerAbstract {

	public function transform(OODBBean $task) {
		$tasks = new Tasks();
		$getAuthor = $tasks->getTaskCreator( $task->id );
        $getParticipants = $tasks->getTaskParticipants( $task->id );

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

		if (!empty($task->updated_at)) {
			$updateAt = date('Y-m-d\TH:i:s\Z', strtotime($task->updated_at));
		} else {
			$updateAt = null;
		}

		if (!empty($task->accepted_at)) {
			$acceptAt = date('Y-m-d\TH:i:s\Z', strtotime($task->accepted_at));
		} else {
			$acceptAt = null;
		}

		return [
			'id'      	   => (int) $task->id,
			'title'        => $task->title,
			'description'  => $task->description,
			'is_complete'  => (int) $task->is_complete,
			'start_at'     => $startAt,
			'end_at'       => $endAt,
			'completed_at' => $completedAt,
			'creator'      => $getAuthor,
			'created_at'   => $createAt,
			'updated_at'   => $updateAt,
			'invited_at'   => $createAt,
			'accepted_at'  => $acceptAt,
			'participants' => $getParticipants
	    ];
	}
}