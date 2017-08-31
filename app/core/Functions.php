<?php

use m4\m4mvc\helper\Session;

/* this file will be removed */

function redirect($url)
{
    header("Location: " . $url);
    exit();
}


function getString($key)
{
  global $lang;
  return $lang[strtoupper($key)];
}


function findBySlugInArray($slug, $array)
{
  if ($slug == null || $array == null) return null;

  for ( $i = 0; $i < count($array); $i++ ) {
    if ( $array[$i]['slug'] == $slug ) {
      return $array[$i];
    }
  }

  return null;

}

