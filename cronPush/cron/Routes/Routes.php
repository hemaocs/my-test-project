<?php
namespace Appsolute\Cron\Routes;

use Monolog\Logger;
use Appsolute\Cron\Api;
use Appsolute\Cron\Classes\Logs;
use Appsolute\Cron\Controllers\Admin;

Class Routes extends Api {

	public function routes() {

		$this->app->group(null, function () {

			require 'Tasks.php';

		});
        
        /* ---
		// Not found page
		-- */     
		$this->app->notFound(function () {
		    $this->app->response->setStatus(404);
		});

		/* ---
		// Error page
		-- */
		$this->app->error(function (\Exception $e) {
			$instance = Logs\Exceptions::getInstance();
	        $instance->getLogger()->log(Logger::CRITICAL, $e->getCode().': '.$e->getMessage(), $instance->exceptionFormatter($e));
	        echo json_encode(array(
				'error_code' => $e->getCode(),
				'message' => $e->getMessage()
			));
			(method_exists($e, 'getStatusCode')) ? $this->app->response->setStatus($e->getStatusCode()) : null;
        });

	}

}