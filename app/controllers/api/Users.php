<?php

namespace app\controllers\api;

use app\core\Controller;
use m4\m4mvc\helper\Session;
use app\helper\Redirect;
use app\model\Newsletter;

class Users extends Controller
{

  public function __construct()
  {
    $this->model = $this->model('User');
  }

  public function logout()
  {
    $this->model->logoutUser();
  }

  public function login()
  {
    if (!$_POST) return;

    if (!$_POST['loginEmail'] || !$_POST['loginPassword']) {
      Session::setFlash("No input received", "danger");
    }

    if (!$user = $this->model->find('email', $_POST['loginEmail'])) {
      Session::setFlash("User not found.", "danger");
      return;
    }

    if (password_verify($_POST['loginPassword'], $user['password'])) {
      $this->model->loginUser($user);
      redirect('/users');
    } 
    else {
      Session::setFlash(getString('CREDENTIALS_NOT_MATCH'), "warning", 1);
    }
  
    redirect('/users');
  }

  public function register() 
  {
    if (!$_POST) return;

    // it will return data if it fails
    // so user does not have to type again
    $this->data = $_POST;

    $data['email'] = $_POST['email'];
    $data['username'] = $_POST['username'];
    $data['password1'] = $_POST['password1'];
    $data['password2'] = $_POST['password2'];
    $data['newsletter'] = isset($_POST['newsletter']);

    if ($data['newsletter']) {
      Newsletter::insert($data['email']);
    }

    if($this->model->find('email', $data['email'])) {
      Session::setFlash(getString('EMAIL_ALREADY_EXISTS'), "warning", 1);
      return;
    }

    if($data['password1'] != $data['password2']) {
      Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), "warning", 1);
      return;
    }

    $data['password'] = password_hash($data['password1'], PASSWORD_DEFAULT);

    if($this->model->register($data)){
      $this->model->loginUser($this->model->find('email', $data['email']));
      Session::setFlash(getString('REGISTRATION_SUCCESS'), "success", 1);
      redirect('/users/update');
    }
  }

  public function update()
  {
     if ($_POST) {
      if (isset($_POST['change-details'])){

        if ($this->model->update($_POST)) {
          Session::setFlash(getString('PROFILE_UPDATED'), 'success');
        } else {
          Session::setFlash(getString('PROFILE_UPDATE_FAILED'), 'danger');
        }

      } elseif(isset($_POST['change-password'])) {
        $current_password = $this->model->find(
          'user_id', Session::get('user_id'), 'password'
        )['password'];

        // fb registered users have no password
        if ($current_password == "no password" || 
            password_verify($_POST['old-password'], $current_password)) 
        {
          if ($_POST['new-password'] == $_POST['new-password2']) {
            if ( $this->model->updatePassword(
              password_hash($_POST['new-password'], PASSWORD_DEFAULT) ) ) {
              Session::setFlash(getString('PASSWORD_CHANGED'), 'success');
            } 
            else {
              Session::setFlash(getString('PASSWORD_UPDATE_ERROR'), 'danger');
            }
          } 
          else {
            Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), 'warning');
          }

        } 
        else {
          Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), 'danger');
          return;
        }
      }
    }

    $this->data['user'] = $this->model->find(
        'user_id', Session::get('user_id')
      );

    $this->data['user']['newsletter'] = Newsletter::findByEmail(
        $this->data['user']['email']
      );
  }

  public function list()
  {
    $this->data['users'] = $this->model->getList();
  }

  public static function check_premission ($premission_level)
  {
    $id = Session::get('user_id');
    $country = Session::get('user_country');
    $role = Session::get('user_role');

    if (!$id) {
      Session::setFlash("Please login. ", "warning", 1);
      return false;
    }

    elseif($premission_level > $role) {
      Session::setFlash(
        "You do not have premission for this action. ", "danger", 1
      );
      return false; 
    }

    elseif($role < 400 && COUNTRY_CODE != $country) {
      Session::setFlash(
        "Please login to the administration of your country ", "danger", 1
      );
      return false;
    }

    return true;
  }

}
