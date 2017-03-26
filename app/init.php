<?php

error_reporting(E_ALL); // allow error reporting
ini_set('display_errors', 1); // display them

session_start();

require_once 'core/Config.php';
require_once 'core/Functions.php';
require_once 'string/' . COUNTRY_CODE. '.php';
require_once 'vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('APP', ROOT . DS . "app");
define('UPLOADS',  DS . "uploads" . DS);
