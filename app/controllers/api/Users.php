<?php

namespace app\controllers\api;

use app\core\Controller;
use app\model\Newsletter;
use app\helper\Email;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Redirect;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Request;

class Users extends Controller
{

  public function __construct()
  {
    $this->model = $this->model('User');
  }

  public function logout()
  {
    Session::destroy();
    if (isset($_COOKIE['auth_token'])) {
      $this->model->delete_token($_COOKIE['auth_token']);
      unset($_COOKIE['auth_token']);
    }
    Redirect::toURL("LOGIN");
  }

  public function login()
  {
    if (!$_POST) return;

    if (!$_POST['loginEmail'] || !$_POST['loginPassword']) {
      Session::setFlash("No input received", "danger");
      return;
    }

    if (!$user = $this->model->find('email', $_POST['loginEmail'])) {
      Session::setFlash("User not found.", "danger");
      return;
    }

    if (!password_verify($_POST['loginPassword'], $user['password'])) {
      Session::setFlash(getString('CREDENTIALS_NOT_MATCH'), "warning", 1);
      return;
    } 

    $this->model->login($user);
    isset($_POST['remember-me']) ? $this->create_cookie() : '';
    redirect('/users');
  
  }

  public function generateToken ($user_id, $expire)
  {
    $selector = base64_encode(random_bytes(9));
    $authenticator = random_bytes(33);
    $token = $selector.':'.base64_encode($authenticator);
    $expire = time() + $expire;

    $this->model->add_token($selector, $authenticator, $expire, $user_id);

    return $token;
  }

  public function create_cookie ()
  {
    $expire = 5184000; // 60 days
    $token = $this->generateToken(Session::get('user_id'), $expire); 
    setcookie('auth_token', $token, $expire, '/');
  }

  public function register() 
  {
    if (!$_POST) return;

    // if fail, return post data
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
      $this->model->login($this->model->find('email', $data['email']));
      Session::setFlash(getString('REGISTRATION_SUCCESS'), "success", 1);
      redirect('/users/update');
    }
  }

  public function userInfo ()
  {
    $this->data['user'] = $this->model->find(
        'user_id', Session::get('user_id')
      );

    $this->data['user']['newsletter'] = Newsletter::findByEmail(
        $this->data['user']['email']
      );

    $this->data['user']['avatar'] =  UPLOADS . DS .'users' . DS . 
                                    $this->data['user']['user_id'] . '.svg' .
                                    '?'.date('ms');

    //echo $this->data['user']['avatar'];die;
  }

  public function avatarList ()
  {
    $dir = ROOT  . DS . 'images' . DS . 'avatars';
    if (!file_exists($dir)) {
      echo 'file does not exists';
      die;
    } 

    $list['avatars'] = array_diff(scandir($dir), ['.','..']);

    Response::success('Avatars has been loaded', $list);
  }

  public function avatarChange ()
  {
    Request::forceMethod('post');
    Request::required('avatar');
    $avatar = $_POST['avatar'];

    $path = ROOT . DS . 'images' . DS . 'avatars' . DS . $avatar;
    $destination = ROOT.UPLOADS.'users'.DS.Session::get('user_id').'.svg';
    if (!file_exists($path)) Response::error('Avatar not found');

    $c = copy($path, $destination);

    if ($c) {
      Response::success('You have new avatar!');
    } else {
      Response::error('Something went wrong');
    }

  }

  public function updateDetails ()
  {
    $set = [
      'email'         =>  $_POST['email'],
      'username'      =>  $_POST['username'],
      'about_me'      =>  $_POST['about-me'],
      'first_name'    =>  $_POST['first-name'],
      'last_name'     =>  $_POST['last-name']
    ];

    if (isset($_POST['newsletter'])) {
      $set['newsletter'] = $_POST['newsletter'];
    }

    $where = ['user_id' => Session::get('user_id')];

    if ($this->model->update($set, $where)) {
      Session::setFlash(getString('PROFILE_UPDATED'), 'success');
    } else {
      Session::setFlash(getString('PROFILE_UPDATE_FAILED'), 'danger');
    }
  }

  public function updatePassword ()
  {
    if ($_POST['new-password'] != $_POST['new-password2']) {
      return Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), 'warning');
    }

    $current_password = $this->model->find(
      'user_id', Session::get('user_id'), 'password'
    )['password'];

    $correctPassword = password_verify(
      $_POST['old-password'], 
      $current_password
    );

    // fb registered users have no password
    $havePassword = !($current_password == "no password");

    if ($havePassword && !$correctPassword) {
      return Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), 'danger');
    }

    $hash = password_hash($_POST['new-password'], PASSWORD_DEFAULT);

    $update = $this->model->update(
      ['password' => $hash], 
      ['user_id'  => Session::get('user_id')]
    );

    if ($update) {
      Session::setFlash(getString('PASSWORD_CHANGED'), 'success');
    } 
    else {
      Session::setFlash(getString('PASSWORD_UPDATE_ERROR'), 'danger');
    }
  }

  public function update()
  {

    $this->userInfo();

    if (isset($_POST['change-details'])) {
      $this->updateDetails();
      $this->userInfo();
    }

    elseif(isset($_POST['change-password'])) {
      $this->updatePassword();
    } 

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

  public function forgotten_password ()
  {
    if (!$_POST) return;

    // check for email in database
    if (!$user = $this->model->find('email', $_POST['email'])) {
      Session::setFlash(getString('EMAIL_NOT_FOUND'), "warning");
      return;
    }

    // create token
    $token = $this->generateToken($user['user_id'], 3600); // 1 hour

    // send token
    $link = "http://vegapo." . COUNTRY_CODE . 
            "/users/password_recovery/" . $token;

    $subject = getString("RECOVER_PASSWORD");

    $b =  getString("GREETING") . " " . $user['username'] . " " . getString("RECOVER_PASSWORD_MESSAGE") . " ";

    $body = [
      'text'  =>  $b . "Link: {$link}",
      'html'  =>  $b . "<a href='{$link}'>Zmeniť heslo<a>"
    ];

    $recipient = [
        'email'   =>  $user['email'],
        'name'    =>  $user['username']
    ];

    if(!Email::send($subject, $body, $recipient)) {
      Session::setFlash($mail->ErrorInfo, 'danger');
    } 

    else {
      Session::setFlash(getString('RECOVERY_EMAIL_SENT'), 'success');
    }
  }

  public function password_recovery ($token)
  {
    $user = $this->model->find_by_token($token);

    if (!$user) {
      Session::setFlash(getString('INVALID_TOKEN'), 'danger');
      return;
    }

    $this->data['token'] = true;

    if (!$_POST) return;

    if ($_POST['password'] != $_POST['password2']) {
      Session::setFlash(getString('PASSWORDS_DO_NOT_MATCH'), 'warning');
      return;
    }

    $update = $this->model->update(
      ['password' => password_hash($_POST['password'], PASSWORD_DEFAULT)],
      ['user_id' =>  $user['user_id']]
    );

    if (!$update) {
      Session::setFlash(getString('PASSWORD_UPDATE_ERROR'), 'danger');
      return;
    }

    Session::setFlash(getString('PASSWORD_CHANGED'), 'success');
    $this->model->delete_token($token);

  }

}
