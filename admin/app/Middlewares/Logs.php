<?php

namespace Appsolute\Backend\Middlewares;

use Slim;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;

Class Logs extends Slim\Middleware {
    
    public function call() { 
        $this->app->hook('slim.before.router', function () {
            $this->app->container->singleton('log', function () {
                $processor = new WebProcessor();
                $handler = new StreamHandler(LOGS_FOLDER.date('Y-m').'.log');
                $log = new Logger($this->app->getName(), array($handler));
                $log->pushProcessor($processor);
                return $log;
            });
        });
        $this->next->call(); 
    }
    
}