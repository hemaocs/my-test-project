<?php

	/* Server info */
	//ini_set('display_errors','ON');
	define('MODE', "development");
	define('APP_NAME', 'Project');
	define('SERVER_URL', 'http://appsolute.ocs.org/');	
	define('MAIN_URL', 'http://appsolute.ocs.org/project/');
	define('BASE_URL', MAIN_URL . 'admin/');
	define('UPLOAD_URL', MAIN_URL . 'upload/');

	define('BASE_FOLDER', $_SERVER['DOCUMENT_ROOT'].'/project/admin/');
	define('SERVER_FOLDER', $_SERVER['DOCUMENT_ROOT'].'/project/'); 
	define('APP_FOLDER', BASE_FOLDER . "app/");
	define('TMP_FOLDER', BASE_FOLDER . "tmp/");
	define('LOGS_FOLDER', BASE_FOLDER . "logs/");
	define('UPLOAD_FOLDER', SERVER_FOLDER . "upload/");
	define('CERT_FOLDER', BASE_URL."certificates/");


	define('FOLDER_NAME', "project/admin");
    
	date_default_timezone_set('Europe/Paris');
	
	define('SHA1KEY','9sy02Bo0fMZ9s9jAl3Nf8ad7OzU8TObI');

	/* Database */
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'appsolute_project');
	define('DB_USER', 'dbuser');
	define('DB_PASS', 'dbuser123');

	/* Log files */
	define('API_EXCEPTION_FILE', BASE_FOLDER . "logs/app/exceptions.json");
	define('API_ERROR_FILE', BASE_FOLDER . "logs/api/errors.json");
	define('API_DEBUG_FILE', BASE_FOLDER . "logs/api/debug.json");
	
	/* Languages USE_MULTILINGUAGES = 'lang1,lang2,lang3,lang4' */
	define('USE_MULTILINGUAGES' , 'fr,en');
	define('LANG_FOLDER' , 'assets/lang/');

	define('API_KEY', 'AIzaSyAoDqiF_k_G6gJtFthog3FF2pT1Ods_LG0');
    define('DEV_PASSPHRASE', 'U3BlYWtIb3RlbA');
    define('PROD_PASSPHRASE', 'U3BlYWtIb3RlbA');
	
	/* Require */
	require_once BASE_FOLDER . 'vendor/autoload.php';