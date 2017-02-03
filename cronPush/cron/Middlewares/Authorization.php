<?php

namespace Appsolute\Cron\Middlewares;

use Slim;
use Appsolute\Cron\Classes\Authorization\AuthorizationInterface;
use Appsolute\Cron\Classes\Exceptions\ClassException;

Class Authorization extends Slim\Middleware {

    protected $app;
    private $dependency;

    public function __construct(Array $dependencies){
        foreach($dependencies as $dependency){
            if(!$dependency instanceof AuthorizationInterface){
                throw new ClassException("Not of instance of AuthorizationInterface.", 0, 500);
            } else {
                $this->dependency[] = $dependency;
            }
        }
    }

	public function call(){
        
        $app = $this->app;

        $isAuthorized = function () use ($app) {
            foreach($this->dependency as $dependency){
                $dependency->setRouteIdentifier($app->router->getCurrentRoute()->getName());
                $dependency->process();
                $validity = $dependency->isValid();
                if($validity){
                    break;
                }
            }
            /*if(!$validity){
                throw new ClassException("You're authorization is not valid.", -20, 401);
            }*/
        };
        $app->hook('slim.before.dispatch', $isAuthorized);

        $this->next->call(); 
          
    }

}