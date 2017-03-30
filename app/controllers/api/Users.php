<?php 

namespace app\controllers\api;

use app\core\Controller;
use app\core\Session;
use app\helper\Redirect;

class Users extends Controller 
{

    public function __construct()
    {
        $this->model = $this->model('User');

    }

    public function logout()
    {
        Session::destroy();
        Redirect::toURL("LOGIN");
    }

    public function login()
    {
        if ($_POST) {
            $mEmail = $_POST['loginEmail'];
            $mPass = $_POST['loginPassword'];

            if ($mEmail && $mPass) {

                if (!$user = $this->model->getByEmail($mEmail)) {
                    Session::setFlash("User not found.", "danger");
                    return;
                }

                if ( password_verify($mPass, $user['password']) ) {
                    Session::set('user_id', $user['user_id']);
                    Session::set('user_role', $user['role']);
                    Session::set('user_country', $user['country']);
                    Session::set('username', $user['username']);
                    $this->model->runQuery("UPDATE `users` SET last_activity = now() WHERE user_id=:id", array("id" => $user['user_id']), "post");
                    redirect('/users');
                } else {
                    Session::setFlash("Credentials do not match.", "warning", 1);
                }
            } else {
                Session::setFlash("No input received", "danger");
            }

        }
    }

    public function register() {
        $this->view = 'public/account/register';
        $this->data = $_POST;
        if($_POST) {
            $data['email'] = $_POST['email'];
            $data['username'] = $_POST['username'];
            $data['password1'] = $_POST['password1'];
            $data['password2'] = $_POST['password2'];

            if($this->model->getByEmail($data['email'])) {
                Session::setFlash("This email is already in the database", "warning", 1);
                return;
            }

            if($data['password1'] != $data['password2']) {
                Session::setFlash("Passwords does not match", "warning", 1);
                return;
            }

            $data['password'] = password_hash($data['password1'], PASSWORD_DEFAULT);

            if($this->model->register($data)){
                Session::setFlash("You are registered", "success", 1);
            }
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

                if ( password_verify($_POST['old-password'], $current_password[0]) ) {
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



}