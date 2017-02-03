<?php

namespace Appsolute\Backend\Controllers\Admin;

use Slim\Slim;
use Appsolute\Backend\Classes\Auth\Auth;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Admin;
use Appsolute\Backend\Models\Auth as AuthDatabase;

Class PostController extends Controllers\Controller {

	public function login(){
		
		$auth = new Auth(new AuthDatabase($this->configManager('Database')));
		$user = new Admin($this->configManager('Database'));
		
		$this->data['retry']['email'] = $this->app->request->post('email');
		$rememberMe = $this->app->request->post('frmRemember');
		$data = $user->findOneByEmail($this->app->request->post('email'));
		
		//If Email patch
		if(!empty($data) && array_key_exists('password', $data)){
			//Password verification
			$password = $this->app->request->post('password');
			if(!Auth::passwordVerify($password, $data['password'])){
				$this->app->errors += ["Wrong username or password"];
			} 
			//If passwords match : connected
			else {
				if($rememberMe == "1") {
                    $userEmail = $this->app->request->post('email');
                    $userPass = $this->app->request->post('password');		
   	                $this->app->setCookie('remEmail', $userEmail, time()+31556926);
   	                $this->app->setCookie('remPass', $userPass, time()+31556926);
                } else {
                    $this->app->setCookie('remEmail', '', time()+3600);
   	                $this->app->setCookie('remPass', '', time()+3600);
                }

				$dataSession = $auth->insertSession(array("admin_id" => $data['id']));
				$this->app->setCookie('isAdmin', $dataSession, '1 days');
				$_SESSION['user'] = array(
					'id' 		=> $data['id'],
					'firstname' => $data['firstname'],
					'lastname' 	=> $data['lastname'],
					'role' 		=> $data['role']
				);
			}
		} 
		else {
			$this->app->errors += ["Wrong username or password"]; //user doesn't exist
		}
	}

	public function logout(){
		$this->app->deleteCookie('isAdmin');
		unset($_SESSION['user']);
	}

}