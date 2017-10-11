<?php 

$base_path = dirname(__DIR__) .  DIRECTORY_SEPARATOR . 'uploads/';

$folders = ['categories', 'supermarkets', 'tags'];

foreach ($folders as $folder) {
  $path = $base_path . $folder;

  echo 'working in: ' . $path . '<br>';

  mkdir($path . '/150x150');
  mkdir($path . '/450x450');

  $files = array_diff(scandir($path), ['.', '..', '150x150', '450x450']);

  echo '<pre>';print_r($files);echo '</pre>';

  foreach ($files as $file) {
    if (strpos($file, '-thumb450x450')) {
      rename($path . '/' . $file, $path . DIRECTORY_SEPARATOR . '450x450' . DIRECTORY_SEPARATOR . substr($file, 0, -13));
      echo $file . ' has been renamed and moved to 450x450 <br>';
    }
    else if (strpos($file, '-thumb')) {
      rename($path . '/' . $file, $path . DIRECTORY_SEPARATOR . '150x150' . DIRECTORY_SEPARATOR . substr($file, 0, -6));
      echo $file . ' has been renamed and moved to 150x150 <br>';
    }
  }
}