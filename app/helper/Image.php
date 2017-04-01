<?php

namespace app\helper;

class Image
{
  public static function upload($image, $folder)
  {
    $name = rand(100, 1000) . "-" . $image['name'];
    $name = slugifyImage($name);
    $tmp = $image['tmp_name'];
    $location = ROOT . DS . "uploads" . DS . $folder . DS . COUNTRY_CODE . DS . $name;
    if(!move_uploaded_file($tmp, $location)){
      return false;
    }
    self::generateThumbnail($location, 150, 150, $name);
    self::generateThumbnail($location, 450, 450, $name);
    return $name;
  }

  public static function generateThumbnail($path, $width, $height, $name)
  {
    $info = getimagesize($path);
    $size = array($info[0], $info[1]);

    if ($info['mime'] == 'image/png') {
        $src = imagecreatefrompng($path);
    } elseif ($info['mime'] == 'image/jpeg') {
        $src = imagecreatefromjpeg($path);
    } elseif ($info['mime'] == 'image/gif') {
        $src = imagecreatefromgif($path);
    } else {
        return false;
    }

    $thumb = imagecreatetruecolor($width, $height);

    $src_aspect = $size[0] / $size[1];
    $thumb_aspect = $width / $height;

    if ($src_aspect < $thumb_aspect) {
        // narrower
        $scale = $width / $size[0];
        $new_size = array($width, $width / $src_aspect);
        $src_pos = array(0, ($size[1] * $scale - $height) / $scale / 2);

    } elseif ($src_aspect > $thumb_aspect) {
        // wider
        $scale = $height / $size[1];
        $new_size = array($height * $src_aspect, $height);
        $src_pos = array(($size[0] * $scale - $width) / $scale / 2, 0);
    } else {
        // same shape
        $new_size = array($width, $height);
        $src_pos = array(0, 0);
    }

    $new_size[0] = max($new_size[0], 1);
    $new_size[1] = max($new_size[1], 1);

    imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]);



    // example path main  : uploads/produkty/cz/cool-image.jpg
    // example path thumb : uploads/produkty/cz/450x450/cool-image.jpg


    // change images folder for thumbs
    // add folder for sizes
    $path = ROOT . DS . "uploads" . DS . 'products' . DS . COUNTRY_CODE . DS . $width . "x" . $height . DS . $name;

    return imagepng($thumb, $path);
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
