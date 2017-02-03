<?php

namespace Appsolute\Backend\Classes\Slim;

use Slim as SlimDefault;

Class Router extends SlimDefault\Router {

	public function getRoles()
    {
        return $this->matchedRoutes[0]->roles;
    }

}