<?php

namespace Appsolute\Backend\Classes\Slim;

use Slim as SlimDefault;
use Appsolute\Backend\Classes\Auth\Auth;

Class Route extends SlimDefault\Route {

	public $roles = array();

	public function role()
    {
        $args = func_get_args();
        $this->roles = array_merge($args);

        return $this;
    }

}