<?php

namespace app\controllers\admin;
use app\core\Session;

use app\controllers\api\Localization as LocalizationApiController;

class Localization extends LocalizationApiController
{
  public function index()
  {
      $this->data['state'] = "view";
      // check lock
      if($this->model->isLocked()) {
        // locked - can not edit
        $this->data['state'] = "locked";
        Session::setFlash(getString('REGISTRATION_SUCCESS'), "danger", 1);
    } elseif ($this->model->haveLock()) {
      $this->data['state'] = "edit";
    }

    if  ($_POST) {
      switch($this->data['state']) {
        case "view":
          $this->model->startEdit();
          $this->data['state'] = "edit";
          break;
        case "edit":
          try {
            $this->model->treatChange($_POST);
            Session::setFlash(getString('REGISTRATION_SUCCESS'), "success", 1);
          } catch(\Exception $e) {
            Session::setFlash($e->getMessage(), "danger", 1);
          }
          $this->data['state'] = "view";
        break;
      }
    }

    $this->data['transl'] = $this->model->compareLocale();
  }
}
