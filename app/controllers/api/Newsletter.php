<?php 

namespace app\controllers\api;

use app\core\Controller; 

class Newsletter extends Controller
{    

  public function __construct()
  {
    $this->model = $this->getModel('Newsletter');
  }

  public function unsubscribe ($id, $email)
  {
    if ($this->model::remove($email, $id)) {
      echo 'You are unsubscribed';
    } else {
      echo 'This link is not valid';
    }
  }


}