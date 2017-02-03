<?php

namespace Appsolute\Cron\Models\Transformer;

use RedbeanPHP\OODBBean;
use League\Fractal;
use Appsolute\Cron\Models\Tasks;

class AcceptedTasksTransformer extends Fractal\TransformerAbstract {

	public function transform(OODBBean $task) {
	    $tasks = new Tasks();
		$getAuthor = $tasks->getTaskCreator( $task->id );
		
		if (isset($getAuthor['id']) && !empty($getAuthor['id'])) {
        	$authorId = $getAuthor['id'];
        } else {
            $authorId = NULL;
        }
		
		if (!empty($task->created_at)) {
			$invitedAt = date('Y-m-d\TH:i:s\Z', strtotime($task->created_at));
		} else {
			$invitedAt = null;
		}
		if (!empty($task->accepted_at)) {
			$acceptedAt = date('Y-m-d\TH:i:s\Z', strtotime($task->accepted_at));
		} else {
			$acceptedAt = null;
		}
		
		return [
			'id'      	   => (int) $task->id,
			'title'        => $task->title,
			'user_id'      => (int) $authorId,
			'invited_at'   => $invitedAt,
			'accepted_at'  => $acceptedAt
	    ];
	}
}