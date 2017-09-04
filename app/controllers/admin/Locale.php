<?php

namespace app\controllers\admin;
use m4\m4mvc\helper\Session;

use app\controllers\api\Locale as LocaleApiController;

class Locale extends LocaleApiController
{
  public function index()
  {
      $this->data['state'] = "view";

      // check lock
      if($this->model->isLocked()) {
        // locked - can not edit
        $this->data['state'] = "locked";
        Session::setFlash(getString('EDIT_LOCKED'), "danger", 1);
    } elseif ($this->model->haveLock()) {
      $this->data['state'] = "edit";
    }

    // if form was submitted
    if($_POST) {
      // check editing state
      switch($this->data['state']) {
        case "view":
          $this->model->startEdit();
          $this->data['state'] = "edit";
          break;
        case "edit":
          try {
            $this->model->processChange($_POST);
            Session::setFlash(getString('EDIT_SUCCESS'), "success", 1);
          } catch(\Exception $e) {
            Session::setFlash($e->getMessage(), "danger", 1);
          }
          $this->data['state'] = "view";
        break;
      }
    }

    // fill localization data for view
    $this->data['locale'] = $this->model->compareLocale();
  }
}
