<?php

namespace Appsolute\Api\Middlewares;

use Slim;
use Appsolute\Api\Models\Validation\ValidationException;

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