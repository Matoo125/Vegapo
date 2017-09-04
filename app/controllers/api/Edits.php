<?php

namespace app\controllers\api;

use app\core\Controller;

class Edits extends Controller
{
	public function __construct()
  {
    $this->model = $this->model("Edit");
  }
}
