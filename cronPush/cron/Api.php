<?php

namespace Appsolute\Cron;

use Slim\Slim;
use Appsolute\Cron\Classes\Views;
use Appsolute\Cron\Classes\Authorization\Types\Basic;

Abstract class Api {

	protected $app;

	public function __construct() {
		Classes\Logs\Exceptions::register();
		Classes\Logs\Debug::register();

		$this->app = new Slim(array(
				'debug' => false
			));
		$this->loadMiddlewares();
		$this->routes();
		$this->app->response->headers->set('Content-Type', 'application/json');
		$this->app->run();
		exit(1);
	}

	private function loadMiddlewares() {
		$this->app->add(new Middlewares\Env());
		$this->app->add(new Middlewares\Authorization(array(new Basic())));
		$this->app->add(new Middlewares\ContentTypes());
		$this->app->add(new Middlewares\ErrorsHandler());
	}

	public static function init() {
		$trueSelf = get_called_class(); 
		$self = new $trueSelf();
		$self->routes();
	}

	abstract public function routes();

}


