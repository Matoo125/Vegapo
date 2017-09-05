<?php

namespace app\controllers\admin;

use app\controllers\api\Edits as EditsApiController;

use mrkovec\sdiff\SDiff;

class Edits extends EditsApiController
{
  public function index($state = null)
  {
      $this->data['state'] = $state;
      $this->data['edits'] = $this->model->getEdits($state);
  }

  public function edit($id = null)
  {
    if($id) {
      $this->data['data'] = $this->model->getEditDetailsById($id);
    }
  }

}
