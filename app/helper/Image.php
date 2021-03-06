<?php

/*
  This should be updated to handle 
  all images not only product related
 */

namespace app\helper;

use Intervention\Image\ImageManagerStatic as I;
use m4\m4mvc\helper\Str;

class Image
{

  public static function upload($image, $folder, $thumbnails = true)
  {
    if (!extension_loaded('imagick')) {
      I::configure(array('driver' => 'gd'));
    }
    else {
        I::configure(array('driver' => 'imagick'));
    }

    $name = rand(100, 1000) . "-" . $image['name'];
    $name = self::slugifyImage($name);
    $tmp = $image['tmp_name'];
    $folder = ROOT . DS . 'uploads' . DS . $folder . DS . COUNTRY_CODE;
    $location = $folder . DS . $name;

    $img = I::make($tmp);
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

    $img->save($location);
    if ($thumbnails) {
      self::generateThumbnail(
        $folder . DS . '150x150', 
        $location, 
        150, 
        150, 
        $name
      );
      self::generateThumbnail(
        $folder . DS . '450x450', 
        $location, 
        450, 
        450,
        $name
      );
    }
    return $name;
  }

  public static function generateThumbnail($folder, $location, $width, $height, $name)
  {
    $path = $folder . DS . $name;

    return $img = I::make($location)
          ->fit($width, $height, function ($constraint) {
                $constraint->upsize();
            })->save($path);
  }

  public static function rotate($image)
  {
    $path = ROOT.DS.'uploads'.DS.'products'.DS.COUNTRY_CODE.DS;
    I::configure(array('driver' => 'imagick'));
    $img = I::make($path.$image)->rotate(-90)->save();
    $img = I::make($path.'450x450'.DS.$image)->rotate(-90)->save();
    $img = I::make($path.'150x150'.DS.$image)->rotate(-90)->save();

  }

  public static function delete($filename, $folder)
  {
    $path = ROOT . DS . "uploads" . DS . $folder . DS . COUNTRY_CODE . DS;
    if( file_exists($path . $filename) && is_file($path.$filename) ){
       unlink($path . $filename);
       unlink($path . "450x450" . DS . $filename);
       unlink($path . "150x150" . DS . $filename);
     }
  }

  public static function slugifyImage($image)
  {
    $explode = explode(".", $image);
    $extension = end($explode);
    $filename = substr($image, 0, strlen($image) - (strlen($extension) + 1));
    return Str::slugify($filename) . "." . $extension;
  }

}
