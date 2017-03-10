<?php

error_reporting(E_ALL); // allow error reporting
ini_set('display_errors', 1); // display them

session_start();

require_once 'strings/sk.php';
require_once 'strings/url.php';
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'core/Config.php';
require_once 'core/Session.php';
require_once 'core/Functions.php';
require_once 'core/Lang.php';
require_once 'vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('APP', ROOT . DS . "app");
define('UPLOADS',  DS . "uploads" . DS);
