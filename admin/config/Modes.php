<?php

namespace Appsolute\Config;

Class Modes implements ConfigManagerInterface {

	public function development() {
		return array(
			"log.enable" => true,
			"debug" => true,
			'cookies.httponly' => true,
			"cookies.encrypt" => false,
			'cookies.secure' => false,
			'cookies.secret_key' => 'MySecretKey',
			"cookies.lifetime" => '3600 minutes'
		);
	}

	public function production() {
		return array(
			"log.enable" => true,
			"debug" => false,
			'cookies.httponly' => true,
			"cookies.encrypt" => true,
			'cookies.secure' => false,
			'cookies.secret_key' => 'MySecretKey',
			"cookies.lifetime" => '3600 minutes'
		);
	}

}