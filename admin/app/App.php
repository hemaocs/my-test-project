<?php

namespace Appsolute\Backend;

use Appsolute\Backend\Classes\Slim\Slim;
use Appsolute\Backend\Classes\Multilingual;
use Appsolute\Config\Modes;
use Appsolute\Backend\Classes\Views;

Abstract class App {

	protected $app;
	protected $lang_url;
	
	public function __construct() {
		$env = MODE;
		
		define( 'REDBEAN_MODEL_PREFIX', '\\Appsolute\\Backend\\Models\\Validation\\' );
		
		$this->app = $app = new Slim(array(
				'mode' => $env,
				'view'=> new \Slim\Views\Twig(),
				'templates.path' => '../app/Views'
		));
		
		$app->configureMode($env, function() use ($app, $env) {
			$app->config((new Modes)->$env());
		});
		$app->setName(APP_NAME);
		$this->loadMiddlewares();
		$this->routes();
		$app->response->headers->set('Content-Type', 'text/html');
		$app->run();
		exit(1);
	}

	private function loadMiddlewares() {
		$this->app->add(new Middlewares\Env());
		$this->app->add(new Middlewares\Logs());
		$this->app->add(new Middlewares\LanguageMiddleware());
		$this->app->add(new \Slim\Middleware\SessionCookie());
		$this->app->add(new Middlewares\Authentication());
	}

	public static function init() {
		$trueSelf = get_called_class(); 
		$self = new $trueSelf();
		$self->routes();
	}

	abstract public function routes();

}