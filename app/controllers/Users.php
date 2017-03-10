<?php

/**
 * Class Users Controller
 * login logic
 * insecure, but enough for working purposes
 * before release, please upgrade.
 */

class Users extends Controller {

    private $view = null;
    private $model = null;
    private $data = array();

    public function __construct()
    {
        $this->model = $this->model('User');

    }

    public function login()
    {
        $this->view = 'public/account/login';
       // $this->data = $_POST;

        if ($_POST) {
            $mEmail = $_POST['loginEmail'];
            $mPass = $_POST['loginPassword'];

            if ($mEmail && $mPass) {

                if (!$user = $this->model->getByEmail($mEmail)) {
                    Session::setFlash("User not found.", "danger");
                    return;
                }

                $mPass = md5(SALT . $mPass);

                if ($mPass == $user['password']) {
                    Session::set('user_id', $user['user_id']);
                    Session::set('user_role', $user['role']);
                    Session::set('user_country', $user['country']);
                    Session::set('username', $user['username']);
                    $this->model->runQuery("UPDATE `users` SET last_activity = now() WHERE user_id=:id", array("id" => $user['user_id']), "post");
                    Session::setFlash("You are logged in.", "success", 1);
                    redirect(PROFILE_PAGE);
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

            if($data['password1'] == $data['password2'] ) {
                $data['password'] = md5(SALT . $data['password1']);
                if($this->model->register($data)){
                    redirect('/');
                }
            }
          redirect("users/register");

        }
    }

    public function admin_index() {
        $this->view = 'admin/profil/index';

        $this->data['data'] = $this->model->getAll();
    }

    public function admin_profile($id) {

    }

    public function admin_update() {
        $this->view = 'admin/profil/upravit';

        if ($_POST) {
            if (isset($_POST['change-details'])){

                if ($this->model->update($_POST)) {
                    Session::setFlash(PROFILE_UPDATED, 'success');
                } else {
                    Session::setFlash(PROFILE_UPDATE_FAILED, 'danger');
                }

            } elseif(isset($_POST['change-password'])) {
                $current_password = $this->model->getUserPassword();

                if ($current_password[0] == md5(SALT . $_POST['old-password'])) {
                    if ($_POST['new-password'] == $_POST['new-password2']) {
                        if ( $this->model->updatePassword( md5(SALT . $_POST['new-password']) ) ) {
                            Session::setFlash(PASSWORD_CHANGED, 'success');
                        } else {
                            Session::setFlash(PASSWORD_UPDATE_ERROR, 'danger');
                        }
                    } else {
                        Session::setFlash(PASSWORDS_DO_NOT_MATCH, 'warning');
                    }

                } else {
                    Session::setFlash(PASSWORDS_DO_NOT_MATCH, 'danger');
                    return;
                }


            }
        }

        $this->data['user'] = $this->model->getById(Session::get('user_id'));

    }

    public function index() {
        $this->profile();
    }

    public function profile() {
        $this->view = "public/account/profile";
    }

    public function logout() {
        $this->view = 'public/account/login';
        Session::destroy();
        Session::setFlash("You are logged out", "success", 1);
       // $this->login();
        redirect(INDEX);

    }

    public function __destruct()
    {
        $this->view($this->view, $this->data);
    }

}