<?php 

$base_path = '../uploads/';

$folders = ['categories', 'supermarkets', 'tags'];

foreach ($folders as $folder) {
  $path = $base_path . $folder;

  mkdir($path . '/150x150');
  mkdir($path . '/450x450');

  $files = array_diff(scandir($path), ['.', '..']);

  foreach ($files as $file) {
    if (strpos($file, '-thumb450x450')) {
      rename($path . '/' . $file, $path . '\/450x450\/' . substr($file, 0, -13));
    }
    else if (strpos($file, '-thumb')) {
      rename($path . '/' . $file, $path . '\/150x150\/' . substr($file, 0, -6));
    }
  }
}