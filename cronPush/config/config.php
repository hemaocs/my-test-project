<?php

	/* Server info */
	ini_set('display_errors','ON');
	define('APP_NAME', 'Vagone');
	define('SERVER_URL', 'http://appsolute.ocs.org/vagone/cronPush/');
	define('BASE_URL', SERVER_URL );
    
	define('BASE_FOLDER', "/var/www/vagone/cronPush/");
	define('API_FOLDER', BASE_FOLDER . "cron/");

	define('FOLDER_NAME', "cron");

	date_default_timezone_set('Europe/Paris');
	

	/* Log files */
	define('API_EXCEPTION_FILE', BASE_FOLDER . "logs/api/exceptions.json");
	define('API_ERROR_FILE', BASE_FOLDER . "logs/api/errors.json");
	define('API_DEBUG_FILE', BASE_FOLDER . "logs/api/debug.json");

	/* Database */
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'appsolute_vagone_v2');
	define('DB_USER', 'dbuser');
	define('DB_PASS', 'dbuser123');
	
	/* Redbean */
	define( 'REDBEAN_MODEL_PREFIX', '\\Appsolute\\Cron\\Models\\Validation\\' );

	/* Require */
	require_once BASE_FOLDER . 'vendor/autoload.php';
	