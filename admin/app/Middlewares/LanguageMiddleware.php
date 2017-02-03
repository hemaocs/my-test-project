<?php
namespace Appsolute\Backend\Middlewares;

use Appsolute\Backend\Classes;
use Slim;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;

class LanguageMiddleware extends Slim\Middleware {

    protected $availableLangs = array();

    public function __construct() 
    {
    	$languages 	= explode(",", USE_MULTILINGUAGES);
        isset($languages[0]) ? $default = $languages[0] : $this->next->call();
        
        $this->availableLangs = $languages;
        $app = \Slim\Slim::getInstance();
        $app->container->singleton('locale', function () use ($default) {
            return new Classes\Language($default);
        });
    }

    public function call() 
    { 
        $basePath = $this->app->request->getScriptName();
        $virtualPath = $this->app->request->getPathInfo();
        $pathChunk = explode("/",$virtualPath);        

        if(FOLDER_NAME == ""){            
        	$lang = $pathChunk[2];
        	$detectedLangPosition = 3;
        	$notDetectedLangPosition = 4;            
        }else{            
        	$lang = $pathChunk[3];
        	$detectedLangPosition = 4;
        	$notDetectedLangPosition = 3;
        }
 

        if(count($pathChunk) > 1 && in_array($lang, $this->availableLangs)) {
            $this->app->locale->set($lang);
            $this->app->lang_url = $lang;
            $pathChunk = array_slice($pathChunk, $detectedLangPosition);
        }else{
            $pathChunk = array_slice($pathChunk, $notDetectedLangPosition);
            $this->app->lang_url = "";
        }
   
        //Setted language
        $translator = new Translator($this->app->locale->get(), new MessageSelector());
        $translator->addLoader('php', new PhpFileLoader());
        
        //Add languages files
        $translator->addResource('php', LANG_FOLDER.$this->app->locale->get().'.php', $this->app->locale->get());
        $view = $this->app->view();		
        $view->parserExtensions = array(new \Slim\Views\TwigExtension(), new TranslationExtension($translator));         
        $_SERVER['REQUEST_URI'] = FOLDER_NAME."/".implode("/",$pathChunk);        
        $newEnv = Slim\Environment::getInstance(true);
        $newRequest = new Slim\Http\Request($newEnv);
        $this->app->container->request = $newRequest;
        $this->next->call();        
    }
}