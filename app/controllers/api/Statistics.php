<?php

namespace app\controllers\api;

use app\core\Controller;

class Statistics extends Controller
{
  public function __construct()
  {
    $this->model = $this->model('Statistic');
  }
  public function index()
  {
    $this->data['stats'] = $this->model->list();
  }
}
