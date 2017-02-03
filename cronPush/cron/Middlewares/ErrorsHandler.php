<?php

namespace Appsolute\Cron\Middlewares;

use Slim;
use Appsolute\Cron\Models\Validation\ValidationException;

Class ErrorsHandler extends Slim\Middleware {

	public function call(){
		try {

			$this->next->call(); 

		} catch(ValidationException $e) {
			echo json_encode(array(
				'error_code' => $e->getCode(),
				'message' => $e->getMessage()
			));
			$this->app->response->setStatus($e->getStatusCode());
		}
    }

}