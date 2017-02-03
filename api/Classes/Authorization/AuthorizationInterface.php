<?php

namespace Appsolute\Api\Classes\Authorization;

Interface AuthorizationInterface {

	public function isValid();

	public function setRouteIdentifier( $identifier );

	public function getRouteIdentifier();

	public function getConnectionType();


}