<?php

error_reporting(E_ALL); // allow error reporting
ini_set('display_errors', 1); // display them

session_start();



define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('APP', ROOT . DS . "app");
define('UPLOADS',  DS . "uploads" . DS);

require_once ROOT . DS . 'vendor/autoload.php';
require_once APP . DS . 'core/Config.php';
require_once APP . DS . 'core/Functions.php';
require_once APP . DS . 'string/lang.php';
