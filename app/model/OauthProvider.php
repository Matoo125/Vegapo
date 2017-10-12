<?php

namespace app\model;

use app\core\Model;
use m4\m4mvc\helper\Session;

class OauthProvider extends Model
{
    private $facebook;

    function __construct() {
      $this->facebook = new Facebook();
    }

    public static function fbLoginUrl($control) {
      return (new Facebook())->loginUrl($control);
    }

    public function  fbCheckUserLogin() {
      return $this->facebook->checkUserLogin();
    }
}

class Facebook
{
  private $fbCredentials = [
  'app_id' => FACEBOOK_APP_ID,
  'app_secret' => FACEBOOK_APP_SECRET,
  'default_graph_version' => 'v2.2',
  ];

  public function loginUrl($control)
  {
    $helper = (new \Facebook\Facebook($this->fbCredentials))->getRedirectLoginHelper();
    return $helper->getLoginUrl(
      "https://{$_SERVER['HTTP_HOST'] }/OauthProvider/".$control, ['email']
    );
  }

  public function checkUserLogin()
  {
    try {
      $fb = new \Facebook\Facebook($this->fbCredentials);
      $helper = $fb->getRedirectLoginHelper();
      $accessToken = $helper->getAccessToken();
    } catch(\Exception $e) {
      throw new \Exception('Facebook login error: '.$e->getMessage());
    }

    if (!isset($accessToken)) {
      if ($this->helper->getError()) {
        throw new \Exception('Facebook login unauthorized: '.$helper->getError().' - '.$helper->getErrorReason().' ('.$helper->getErrorDescription().')');
      } else {
        throw new \Exception('Facebook login bad request.');
      }
    }

    try {
      $response = $fb->get('/me?fields=name,email', $accessToken->getValue());
    } catch(\Exception $e) {
      throw new \Exception('Facebook error: '.$e->getMessage());
    }

    return $response->getGraphUser();
  }
}
