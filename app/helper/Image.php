<?php

namespace app\helper;

use Intervention\Image\ImageManagerStatic as I;

class Image
{
  public function __construct()
  {
  }
  public static function upload($image, $folder)
  {
    I::configure(array('driver' => 'imagick'));
    $name = rand(100, 1000) . "-" . $image['name'];
    $name = slugifyImage($name);
    $tmp = $image['tmp_name'];
    $location = ROOT . DS . "uploads" . DS . $folder . DS . COUNTRY_CODE . DS . $name;

    $img = I::make($tmp);
    $size = $img->filesize();
    $width = $img->width();
    $height = $img->height();

    if ($size > 500000) {
      $compress = 75;
      $img->encode('jpg', $compress);
    }

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

    $img->save($location);
    self::generateThumbnail($location, 150, 150, $name);
    self::generateThumbnail($location, 450, 450, $name);
    return $name;
  }

  public static function generateThumbnail($location, $width, $height, $name)
  {
    //I::configure(array('driver' => 'imagick'));
    $path = ROOT . DS . "uploads" . DS . 'products' . DS . COUNTRY_CODE . DS . $width . "x" . $height . DS . $name;

    return $img = I::make($location)
          ->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            })->save($path);

  }

  public static function delete($filename)
  {
    $path = ROOT . DS . "uploads" . DS . 'products' . DS . COUNTRY_CODE . DS;
    if( file_exists($path . $filename) && is_file($path.$filename) ){
       unlink($path . $filename);
       unlink($path . "450x450" . DS . $filename);
       unlink($path . "150x150" . DS . $filename);
     }
  }

}
