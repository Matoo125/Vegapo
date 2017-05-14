<?php
use Intervention\Image\ImageManagerStatic as I;
require_once 'vendor/autoload.php';
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
I::configure(array('driver' => 'imagick'));

$path = 'uploads/products/cz/';

$files = scandir($path);
$files = array_diff($files, ['..', '.', '450x450', '150x150']);

//echo '<pre>'; print_r($files);

foreach ($files as $filename) {
  $img = I::make($path.$filename);
  $size = $img->filesize();
  $width = $img->width();
  $height = $img->height();

  if ($width > 1000) {
    $img->resize(1000, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });
  }

  if ($height > 1000) {
      $img->resize(null, 1000, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
      });
  }

  $img->save($path.$filename);
  generateThumbnail($path, 150, 150, $filename);
  generateThumbnail($path, 450, 450, $filename);

  echo $filename . " generated <br>";
}
// resize images

function generateThumbnail($path, $width, $height, $filename) {


      return $img = I::make($path.$filename)
            ->fit($width, $height, function ($constraint) {
                  $constraint->upsize();
              })->save($path.$width."x".$height."/".$filename);
}
