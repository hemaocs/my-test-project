<?php

namespace Appsolute\Backend\Middlewares;

use Slim;
use Appsolute\Backend\Classes\Auth\Auth;
use Appsolute\Backend\Models\Auth as AuthDatabase;
use Appsolute\Config\Database;

Class Authentication extends Slim\Middleware {
    
    public function call() { 
        $app = $this->app;
        $app->hook('slim.after.router', function () use ($app) {
            $this->redirectAuth($app);
        });
        $this->next->call(); 
    }
    
    /**
     * This method check if a user is allowed to accessthe current route.
     * @param  object   $app   Slim instance
     * @return boolean
     */
    private function isAllowed($app) {
        //Authenticate user based on the token from the cookie
        $cookie = $this->app->getCookie('isAdmin');
        $auth = new Auth(new AuthDatabase(new Database));
        $isAllowed = $auth->isAllowed($cookie);
        $user = $auth->getUser($cookie);

        //If user has no role, then he is "guest" by default
        if(!isset($_SESSION['user']['role']) || empty($_SESSION['user']['role'])) {
            $_SESSION['user']['role'] = "guest";
        }

        //Check role if user is authenticated
        if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $user['id']) {
            if($this->checkRole()) {
                return ($isAllowed === 1) ? TRUE : FALSE;
            } else {
                $app->flash("error", "Not authorized to access this section.");
                $app->redirect($app->urlFor('unauthorized'));
                exit(1);
            }
        } else {
            return false;
        }
        
    }

    /**
     * Check if the role of the user (from the cookie) is one of the allowed role for the route.
     * @return boolean
     */
    private function checkRole() {
        $allowedRoles = $this->app->getRoles(); //get list of authorized roles
        if(isset($_SESSION['user']['role'])) {
            if(!empty($allowedRoles)){
                foreach($allowedRoles as $role) {
                    if($_SESSION['user']['role'] == $role) {
                        return true;
                    }
                }
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * Redirect the user to the home page if he's authenticated, otherwise to the login page.
     * @param  object   $app   Slim instance
     * @return void
     */
    private function redirectAuth($app) {
        $currentRoute = $app->router->getCurrentRoute()->getName();
        $folderName = FOLDER_NAME;
        $folderName = (!empty($folderName)) ? SERVER_URL.$folderName : "";
        if($this->isAllowed($app)) {
            ($currentRoute == "public.login") ? $app->redirect($app->urlFor('public.home')) : null;        
        } else {
            ($currentRoute == "public.login") ? null : $app->redirect($app->urlFor('public.login'));
        }
    }
}