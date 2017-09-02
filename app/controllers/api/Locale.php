<?php

namespace app\controllers\api;

use app\core\Controller;

class Localization extends Controller
{
  public function __construct()
  {
    $this->model = $this->model("Localization");
  }
}
