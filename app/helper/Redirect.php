<?php

namespace app\helper;

use app\string\Url;

class Redirect
{
    public static function to($url)
    {
        header("Location: " . $url);
        exit();
    }

    public static function toURL($key)
    {
      return self::to(Url::get($key));
    }
}
