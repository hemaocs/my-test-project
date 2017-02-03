<?php

namespace Appsolute\Cron\Classes\Authorization;

Interface AuthorizationInterface {

	public function isValid();

	public function setRouteIdentifier( $identifier );

	public function getRouteIdentifier();

	public function getConnectionType();


}