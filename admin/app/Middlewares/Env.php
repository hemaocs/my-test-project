<?php

namespace Appsolute\Backend\Middlewares;

use Slim;

Class Env extends Slim\Middleware {

	public function call(){
        $this->app->environment['PATH'] = $_SERVER['PATH'];
        $this->next->call(); 
    }

}