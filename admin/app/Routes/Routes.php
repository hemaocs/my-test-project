<?php

namespace Appsolute\Backend\Routes;

use Monolog\Logger;
use Appsolute\Backend\App;
use Appsolute\Backend\Controllers\Admin;

Class Routes extends App{

	public function routes() {

		$folder = FOLDER_NAME;
				
		//Main group
		$this->app->group($base_folder = (!empty($folder)) ? '/'.$folder : "", function () use ( $base_folder ) {
			foreach(glob(APP_FOLDER . 'Routes/*.php') as $file) {
				if($file != "Routes.php"){
					include_once $file;
				}
			}
		});
		
        /* --- Not found page -- */     
		$this->app->notFound(function () {
			$this->app->render('Errors/notFound.template.html');
		    $this->app->response->setStatus(404);
		});

		/* --- Error page -- */
		$this->app->error(function (\Exception $e) {
	        $this->app->log->addDebug("Error 500", array($e));
	        $this->app->render('Errors/serverError.template.html');
        	$this->app->response()->status( 500 );
        });

	}
}