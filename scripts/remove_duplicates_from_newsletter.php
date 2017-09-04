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
$newsletter = $model->fetchAll('select email from newsletter');

foreach ($newsletter as $key => $value) {
  $newsletter[$key] = $value['email'];
}

$duplicates = array_diff_assoc($newsletter, array_unique($newsletter));

foreach ($duplicates as $duplicate) {
  $model->save('delete from newsletter where email = :email LIMIT 1', ['email' => $duplicate]);
}

echo '<pre>';print_r($duplicates);
//echo '<hr>';print_r($newsletter);