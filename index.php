<?php

require_once 'app/init.php';

use m4\m4mvc\core\Module;


$app = new app\core\App;


$app->settings = [
  'namespace' =>  'app',
  'modules'   =>  true, // if you want co use modules
  'moduleView'=> false,
  'viewExtension' => 'twig',
  'renderFunction' => 'view'
];

// register modules
Module::register(['web', 'admin', 'api']);

$app->paths = [
  'controllers' =>  'controllers',
  'app' =>  'app',
  'theme'       =>  [
    'web'   =>  'web', // path to public theme
    'admin' =>  'admin' // path to admin theme
  ]
];

// db connection
$app->db([
  'DB_HOST'   =>  DB_HOST,
  'DB_PASSWORD' =>  DB_PASSWORD,
  'DB_NAME'   =>  DB_NAME,
  'DB_USER'   =>  DB_USER
]);


$app->run();