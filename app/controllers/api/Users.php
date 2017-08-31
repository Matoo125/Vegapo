<?php

namespace app\controllers\api;

use app\core\Controller;
use m4\m4mvc\helper\Session;
use app\helper\Redirect;

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
        $mEmail = $_POST['loginEmail'];
        $mPass = $_POST['loginPassword'];

        if ($mEmail && $mPass) {
            // check if user exists
            if (!$user = $this->model->getByEmail($mEmail)) {
                Session::setFlash("User not found.", "danger");
                return;
            }
            // verify password
            if ( password_verify($mPass, $user['password']) ) {
                $this->model->loginUser($user);
                redirect('/users');
            } else {
                Session::setFlash(getString('CREDENTIALS_NOT_MATCH'), "warning", 1);
            }
        } else {
            Session::setFlash("No input received", "danger");
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

        // check if user already exists
        if($this->model->getByEmail($data['email'])) {
            Session::setFlash(getString('EMAIL_ALREADY_EXISTS'), "warning", 1);
            return;
        }

        // check if passwords are the same
        if($data['password1'] != $data['password2']) {
            Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), "warning", 1);
            return;
        }

        // create password hash
        $data['password'] = password_hash($data['password1'], PASSWORD_DEFAULT);

        // register user
        if($this->model->register($data)){
          // login user
          $this->model->loginUser($this->model->getByEmail($data['email']));
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
                $current_password = $this->model->getUserPassword();

                // dont demand old password for facebook registered user (until he changes it to valid)
                if ( $current_password[0] == "no password" || password_verify($_POST['old-password'], $current_password[0]) ) {
                    if ($_POST['new-password'] == $_POST['new-password2']) {
                        if ( $this->model->updatePassword( password_hash($_POST['new-password'], PASSWORD_DEFAULT) ) ) {
                            Session::setFlash(getString('PASSWORD_CHANGED'), 'success');
                        } else {
                            Session::setFlash(getString('PASSWORD_UPDATE_ERROR'), 'danger');
                        }
                    } else {
                        Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), 'warning');
                    }

                } else {
                    Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), 'danger');
                    return;
                }
            }
        }

        $this->data['user'] = $this->model->getById(Session::get('user_id'));
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
          Session::setFlash("You do not have premission for this action. ", "danger", 1);
          return false; 
        }

        elseif($role < 400 && COUNTRY_CODE != $country) {
          Session::setFlash("Please login to the administration of your country ", "danger", 1);
          return false;
        }

        return true;
    }

}
