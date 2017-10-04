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

$path = '../images/avatars';


if (!file_exists($path)) {
  echo 'i cannot find the avatar folder';
  exit;
}

$avatars = array_diff(scandir($path), ['.','..']);

$users_uploads = '../uploads/users';
if (!file_exists($users_uploads)){
  mkdir($users_uploads);
}

// assign image to every user
$users = $model->fetchAll("SELECT user_id FROM users");

foreach ($users as $user) {
  $id = $user['user_id'];
  //select random avatar
  $image_id = array_rand($avatars);
  $image = $avatars[$image_id];

  copy($path . '/' . $image, $users_uploads . '/' . $id . '.svg');

  $sql = "UPDATE users 
          SET avatar = :avatar 
          WHERE user_id = :user_id";
  $bind = [
    'avatar'  =>  $id . '.svg',
    'user_id' =>  $id
  ];
  $model->save($sql, $bind);

  echo "User {$id} has new avatar {$image} <br>";
}


