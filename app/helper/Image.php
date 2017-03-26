<?php

namespace app\helper;

class Image
{
  public static function upload($image, $folder)
  {
    $name = rand(100, 1000) . "-" . $image['name'];
    $tmp = $image['tmp_name'];
    $location = ROOT . DS . "uploads/images" . DS . $folder . DS . $name;
    if(!move_uploaded_file($tmp, $location)){
      return false;
    }
    self::generateThumbnail($location, 150, 150);
    return $name;
  }

  public function generateThumbnail($path, $width, $height)
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
    // example path : uploads/images/post/first-post/cool-image.jpg
    //              : uploads/images/page/one-page/anathat-imag.gif
    //              : uploads/images/user/ja-user/my-pic.png

    // example path : uploads/thumbs/450x450/post/first-post/cool-image.jpg
    //              : uploads/thumbs/200x200/page/one-page/anathat-imag.gif
    //              : uploads/thumbs/150x150/user/ja-user/my-pic.png

    // change images folder for thumbs
    // add folder for sizes
    $path = preg_replace('/images/', 'thumbs'.DS.$width."x".$height, $path, 1);

    // create folder if it doesn't exists
    if(!is_dir(dirname($path))) {
      mkdir(dirname($path), 0777, true);
    }

    return imagepng($thumb, $path);
  }

}
