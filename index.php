<?php

require_once 'app/init.php';

use m4\m4mvc\core\Module;
use m4\m4mvc\helper\Request;

$app = new app\core\App;

$app->settings = [
  'namespace' =>  'app',
  'viewExtension' => 'twig',
  'renderFunction' => 'view'
];

$app->paths = [
  'controllers' =>  'controllers',
  'app' =>  APP,
  'model' =>  'model'
];

Module::register([
  'web' => [
    'render'  =>  'view',
    'folder'  =>  'view' . DS . 'web'
  ], 
  'admin' => [
    'render'  =>  'view',
    'folder'  =>  'view/admin'
  ],
  'api' =>  [
    'render'  =>  'json',
  ]
]);

// db connection
$app->db([
  'DB_HOST'   =>  DB_HOST,
  'DB_PASSWORD' =>  DB_PASSWORD,
  'DB_NAME'   =>  DB_NAME,
  'DB_USER'   =>  DB_USER
]);

Request::mapUrl([
  'prihlasenie' =>  'users/login',
  'registracia' => 'users/register'
]);

$app->run();