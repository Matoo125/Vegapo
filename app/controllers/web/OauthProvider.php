<?php

namespace app\controllers\web;

use app\controllers\api\OauthProvider as OauthProviderAPIController;

use app\core\Session;

class OauthProvider extends OauthProviderAPIController
{
  public function facebook_disconnect()
  {
    $this->model->runQuery("UPDATE `users` SET updated_at = now(), facebook_id = null WHERE user_id=:id", array("id" => Session::get('user_id')), "post");

    Session::setFlash(getString('FACEBOOK_DISCONNECT_SUCCESS'), "success", 1);
    redirect('/users/update');
  }
}
