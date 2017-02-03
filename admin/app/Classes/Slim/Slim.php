<?php

namespace Appsolute\Backend\Classes\Slim;

class Slim extends \Slim\Slim
{
	public function __construct(array $userSettings = array()) {
		parent::__construct($userSettings);

		//Override the Request property with our own implementation
		$this->request = null;
		$this->container->singleton('request', function ($c) {
            return new Http\Request($c['environment']);
        });

        //Override the Router property with our own implementation
        $this->container->singleton('router', function ($c) {
            return new Router();
        });

	}

    public function getRoles()
    {
        return $this->router->getRoles();
    }

    protected function mapRoute($args)
    {
        $pattern = array_shift($args);
        $callable = array_pop($args);
        $route = new Route($pattern, $callable, $this->settings['routes.case_sensitive']);
        $this->router->map($route);
        if (count($args) > 0) {
            $route->setMiddleware($args);
        }

        return $route;
    }

	public function urlFor($name, $params = array())
    {	
    	$folderName = FOLDER_NAME;
    	if(!empty($folderName)) {
    		return "/".$folderName."/".ltrim(str_replace($folderName, "", $this->router->urlFor($name, $params)), "/");
    	} else {
    		return $this->request->getRootUri() . $this->router->urlFor($name, $params);
    	}
    }
}