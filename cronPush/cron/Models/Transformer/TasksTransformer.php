<?php

namespace Appsolute\Cron\Models\Transformer;

use RedbeanPHP\OODBBean;
use League\Fractal;

class TasksTransformer extends Fractal\TransformerAbstract {

	public function transform(OODBBean $task) {
		if (!empty($task->completed_at)) {
			$completedAt = date('Y-m-d\TH:i:s\Z', strtotime($task->completed_at));
			//$newStart = str_replace('+00:00', 'Z', gmdate('c', strtotime($event->start_at)));
		} else {
			$completedAt = null;
		}
		if (!empty($task->start_at)) {
			$startAt = date('Y-m-d\TH:i:s\Z', strtotime($task->start_at));
			//$newStart = str_replace('+00:00', 'Z', gmdate('c', strtotime($event->start_at)));
		} else {
			$startAt = null;
		}
		if (!empty($task->end_at)) {
			$endAt = date('Y-m-d\TH:i:s\Z', strtotime($task->end_at));
			//$newStart = str_replace('+00:00', 'Z', gmdate('c', strtotime($event->start_at)));
		} else {
			$endAt = null;
		}
		if (!empty($task->created_at)) {
			$createAt = date('Y-m-d\TH:i:s\Z', strtotime($task->created_at));
			//$newEnd = str_replace('+00:00', 'Z', gmdate('c', strtotime($event->end_at)));
		} else {
			$createAt = null;
		}
		if (!empty($task->updated_at)) {
			$updateAt = date('Y-m-d\TH:i:s\Z', strtotime($task->updated_at));
			//$newEnd = str_replace('+00:00', 'Z', gmdate('c', strtotime($event->end_at)));
		} else {
			$updateAt = null;
		}
		return [
			'id'      	   => (int) $task->id,
			'title'        => $task->title,
			'description'  => $task->description,
			'is_complete'  => (int) $task->is_complete,
			'start_at'     => $startAt,
			'end_at'       => $endAt,
			'completed_at' => $completedAt,
			'created_at'   => $createAt,
			'updated_at'   => $updateAt
	    ];
	}
}