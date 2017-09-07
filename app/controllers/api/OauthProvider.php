<?php

namespace app\controllers\api;

use app\model\User;
use app\core\Controller;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Redirect;

class OauthProvider extends Controller
{

	public function __construct()
  {
    $this->model = $this->model("OauthProvider");
  }

  public function fb_login_callback()
  {
    try {
      $fbUser = $this->model->fbCheckUserLogin();
    } catch(\Exception $e) {
      Session::setFlash(getString('FACEBOOK_LOGIN_ERROR')." ".$e->getMessage(), "danger",1);
      Redirect::toURL('LOGIN');
    }

    $user = new User();
    // search user by facebook id
    if ($fbUser['id']) {
      if ($userData = $user->find('facebook_id', $fbUser['id'])) {
        $user->loginUser($userData);
        Session::setFlash(getString('FACEBOOK_LOGIN_SUCCESS'), "success", 1); //not yet used - maybe sometimes
        redirect('/users');
      }
    }

    // try to look up user by facebook email
    if ($fbUser['email']) {
      if ($userData = $user->find('email', $fbUser['email'])) {
        // update user facebook_id
        $user->changeFacebookId($userData['user_id'], $fbUser['id']);

        $user->loginUser($userData);

        Session::setFlash(getString('FACEBOOK_LOGIN_SUCCESS'), "success", 1); //not yet used - maybe sometimes
        redirect('/users');
      }
    }

    Session::setFlash(getString('FACEBOOK_LOGIN_ERROR')." User not found.", "danger",1);
    Redirect::toURL('LOGIN');
  }

  public function fb_register_callback()
  {
    try {
      $fbUser = $this->model->fbCheckUserLogin();
    } catch(\Exception $e) {
      Session::setFlash(getString('FACEBOOK_REGISTER_ERROR')." ".$e->getMessage(), "danger",1);
      Redirect::toURL('REGISTER');
    }

    // not enough data
    if (!$fbUser['email'] || !$fbUser['name']) {
      Session::setFlash(getString('FACEBOOK_REGISTER_ERROR')." Not enough data", "danger",1);
      Redirect::toURL('REGISTER');
    }

    $user = new User();
    // check if user already exists
    if ($userData = $user->find('facebook_id', $fbUser['id'])) {
      $user->loginUser($userData);
      Session::setFlash(getString('FACEBOOK_REGISTER_SUCCESS'), "success", 1);
      redirect('/users');
    }
    if ($userData = $user->find('email', $fbUser['email'])) {
      // update user facebook_id
      $user->changeFacebookId($userData['user_id'], $fbUser['id']);

      $user->loginUser($userData);
      Session::setFlash(getString('FACEBOOK_REGISTER_SUCCESS'), "success", 1);
      redirect('/users');
    }

    // form registration pg_parameter_status
    $userData['username'] = $fbUser['name'];
    $userData['email'] = $fbUser['email'];
    $userData['password'] = "no password";
    $userData['facebook_id'] = $fbUser['id'];
    //  guess firs/last name
    $name = explode(" ", $fbUser['name'], 2);
    $userData['first_name'] = $name[0];
    $userData['last_name'] = $name[1];

    //register
    $user->registerFacebookUser($userData);

    // add email to newsletter
    \app\model\Newsletter::insert($userData['email']);

    //refresh user
    $userData = $user->find('facebook_id', $userData['facebook_id']);

    $user->loginUser($userData);

    Session::setFlash(getString('FACEBOOK_REGISTER_SUCCESS'), "success", 1);
    redirect('/users/update');
  }

  public function fb_connect_callback()
  {
    try {
      $fbUser = $this->model->fbCheckUserLogin();
    } catch(\Exception $e) {
      Session::setFlash(getString('FACEBOOK_CONNECT_ERROR')." ".$e->getMessage(), "danger",1);
      redirect('/users');
    }
    $user = new User();

    // search user by facebook_id
    if ($userData = $user->find('facebook_id', $fbUser['id'])) {
      // if user exist and is diferent from logend user - then error
      if($userData['user_id'] != Session::get('user_id')) {
        Session::setFlash(getString('FACEBOOK_CONNECT_ERROR')." Facebook user already exists.", "danger",1);
        redirect('/users/update');
      }
    }
    // update users facebook_id
    $user->changeFacebookId(Session::get('user_id'), $fbUser['id']);

    Session::setFlash(getString('FACEBOOK_CONNECT_SUCCESS'), "success", 1);
    redirect('/users');
  }

}
