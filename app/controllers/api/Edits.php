<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Session;

class Edits extends Controller
{
	public function __construct()
  {
    $this->model = $this->model("Edit");
  }

  public function index()
  {
        // $this->data['tags'] = $this->model->getTags();
  }

  public function open($what = null)
  {
    if(isset($what)) {
      switch ($what) {
        case 'lang':
          if($this->model->isLangLocked()){
            Session::setFlash('Cant edit langueages. Somebody alredy has.', "danger", 1);
            redirect('/admin/Translate');
          }
          $data['type'] = "lang";
          $this->model->insert($data);
          redirect('/admin/Translate');
          break;
      }
    }
  }
}
