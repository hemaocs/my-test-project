<?php

	/* Server info */
	//ini_set('display_errors','ON');
	define('APP_NAME', 'Vagone');
	define('SERVER_URL', 'http://appsolute.ocs.org/project/');
	define('BASE_URL', SERVER_URL );
	define('UPLOAD_URL', SERVER_URL . 'upload/');
    
	define('BASE_FOLDER', "/var/www/project/");
	define('API_FOLDER', BASE_FOLDER . "api/");
	define('UPLOAD_FOLDER', "/var/www/project/upload/");
	define('TMP_FOLDER', BASE_FOLDER . "tmp/");

	define('FOLDER_NAME', "api");

	date_default_timezone_set('Europe/Paris');
	

	/* Log files */
	define('API_EXCEPTION_FILE', BASE_FOLDER . "logs/api/exceptions.json");
	define('API_ERROR_FILE', BASE_FOLDER . "logs/api/errors.json");
	define('API_DEBUG_FILE', BASE_FOLDER . "logs/api/debug.json");

	/* Database */
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'appsolute_project');
	define('DB_USER', 'dbuser');
	define('DB_PASS', 'dbuser123');
	
	/* Redbean */
	define( 'REDBEAN_MODEL_PREFIX', '\\Appsolute\\Api\\Models\\Validation\\' );

	/* Require */
	require_once BASE_FOLDER . 'vendor/autoload.php';
	