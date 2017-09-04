<?php 
require_once('../app/init.php');
use app\model\User;

m4\m4mvc\core\Model::$credentials = [
  'DB_HOST'   =>  DB_HOST,
  'DB_PASSWORD' =>  DB_PASSWORD,
  'DB_NAME'   =>  DB_NAME,
  'DB_USER'   =>  DB_USER
];

$model = new User;
$users = $model->fetchAll('select email, country from users');


foreach ($users as $user) {
  if (!$model->fetchAll('select * from newsletter where email = :email', ['email' => $user['email']])) {
    $model->save("insert into newsletter (email, country) values(:email, :country)", $user);
  }
}

echo '<pre>';print_r($users);
//echo '<hr>';print_r($newsletter);