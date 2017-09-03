<?php

namespace app\controllers\api;

use app\core\Controller;

class Locale extends Controller
{
  public function __construct()
  {
    $this->model = $this->model("Locale");
  }
}
