<?php

/**
 * Here's a quick example on how to use Slim. You will have to import the database(appsolute_api.sql) on your server to make it work.
 * This example demonstrate simple CRUD operation. Go to the Routes folder for the next step.
 * @version  2.0
 * @author kendryck@appsolute.fr
 */
require_once __DIR__.'/../config/config.php';

use Appsolute\Cron;

$api = new Cron\Routes\Routes();