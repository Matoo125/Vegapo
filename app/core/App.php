<?php

namespace app\core;

use m4\m4mvc\core\App as FrameworkApp;

use app\helper\Redirect;
use app\controllers\api\Users;


class App extends FrameworkApp
{
  public function __construct ()
  {
    set_error_handler([$this, 'errorHandler']);
    if (isset($_GET['url']) && substr($_GET['url'], 0, 5) == 'admin'){
       if (!Users::check_premission(30)) redirect('/users/login'); // if not admin redirect
    } 

    else if (isset($_GET['url']) && substr($_GET['url'], 0, 3) == 'api') {
      $this->response = 'json';
    }

    new \app\string\Url(); 

  }

  public function errorHandler ($level, $message, $file, $line)
  {
    $exit = false;
    $time = date('Y-m-d H:s:i');

    switch ($level) {
      case E_NOTICE:
      case E_USER_NOTICE:
      case E_DEPRECATED:
      case E_USER_DEPRECATED:
      case E_STRICT:
        $type = "Notice";
      break;

      case E_WARNING:
      case E_USER_WARNING:
        $type = "Warning";
      break;

      case E_ERROR:
      case E_USER_ERROR:
        $type = "Fatal";
        $exit = true;
      break;
      
      default:
        $type = "Unknown";
        $exit = true;
      break;
    }

    if (SHOW_ERRORS) {
      $show = "<b>Type:</b> {$type} | 
            <b>Errno:</b> {$level} | 
            <b>Message:</b> {$message} | 
            <b>File:</b> {$file} | 
            <b>Line:</b> {$line} | 
            <b>Time:</b> {$time}";
      echo $show;
    }

    $log = "Type: {$type} | No: {$level} | Msg: {$message} | File: {$file} | Line: {$line} | Time {$time}";

    error_log($log); 

    if ($exit) exit();

    return true;
  }

}
