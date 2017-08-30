<?php

error_reporting(E_ALL); // allow error reporting
ini_set('display_errors', 1); // display them

session_start();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__)); // root folder
define('APP', ROOT . DS . "app"); // app folder
define('UPLOADS',  DS . "uploads" . DS); // this path is relative so it can be used in url

// require autoloader
// and files which are not classes
// so cannot be autoloaded
require_once ROOT . DS . 'vendor/autoload.php';
require_once APP . DS . 'core/Config.php';
require_once APP . DS . 'core/Functions.php';
require_once APP . DS . 'string/lang.php';
