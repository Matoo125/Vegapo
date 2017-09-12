<?php

namespace app\core;

use m4\m4mvc\core\Controller as FrameworkController;
use app\string\Url;
use app\model\OauthProvider;
use m4\m4mvc\helper\Session;
use app\model\User;

class Controller extends FrameworkController 
{
  /*
  * function model
  * @param    $name  string   Name of model, i.e: Dashboard
  * @return          object   Model Instance
  */
  public function model ($name) {
    return $this->getModel($name);
  }
    
  /*
   * Function view - Renders twig view or json
   * @param    $view    string   Path to view
   * @return            void
   */
  public function view($view) 
  {
    /* this is only temporary it will be moved */
    if (!Session::get('user_id') && isset($_COOKIE['auth_token'])) {
        $user = new User;
        $data = $user->find_by_token($_COOKIE['auth_token']);
        if ($data) {
          $user->login($data);
          redirect($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
        }
    } 

    if (!$view && !$this->view) {
      header('content-type: application/json; charset=utf-8');
      header("access-control-allow-origin: *");
        echo json_encode($this->data);
        return;
    };

    // if someone wants to render view in different path
    if ($this->view) {
        $view = $this->view;
    }

    if (DEVELOPMENT) {
      $environment =  array('debug' => true);
    } else {
      $environment = array('cache' => APP . DS . 'cache');
    }

    // load folder
    $loader = new \Twig_Loader_Filesystem(APP . DS . 'view');

    // create twig instance
    $twig = new \Twig_Environment($loader, $environment);

    if (DEVELOPMENT) $twig->addExtension(new \Twig_Extension_Debug());

    // add session global variable
    $twig->addGlobal("session", $_SESSION);

    // add slugify filter
    $slugifilter = new \Twig_Filter('slugify', 'm4\m4mvc\helper\Str::slugify');
    $twig->addFilter($slugifilter);

     /*
     * add buildURL function
     * @param   $params  array             Array of parameters to join
     * @param   $key     string || array   Key to change in params
     * @param   $value   string            Value of key in params to be set 
     * @return          string            Http Query String
     */ 
    $buildUrl = new \Twig_SimpleFunction('buildUrl', function($params, $key, $value) {
      if ($key == "tag" && $value && $params[$key]) {
        //allow multiple 'tag' values
        if(is_array($params[$key])) {
          // new tag
          if (!in_array($value, $params[$key])) {
            $params[$key][] = $value;
          }
        } else {
          // new tag
          if($params[$key] !=  $value) {
            $params[$key] = [$params[$key]];
            $params[$key][] = $value;
          }
        }
      } else {
        // replace old part of params for new
        $params[$key] = $value;
      }
      return '/produkty?' . http_build_query($params, '', '&');
    });
    $twig->addFunction($buildUrl);

    /*
    * add stripUrlParam function
    * removes $params[$remKey] (or $params[$remKey][$remValue]) from $params list
    * @param  $params    array             Array of parameters to join
    * @param  $remKey    string || array   Key to remove or array to remove from
    * @param  $remValue  string            Value to remove from key array
    * @return           string             Http Query String
    */
    $stripUrlParam = new \Twig_SimpleFunction('stripUrlParam', function($params, $remKey, $remValue = null) {
      $newParams = [];
      foreach ($params as $key => $value) {
        if ($key != $remKey) {
          // leave param in list
          $newParams[$key] = $value;
        } else {
          if($remValue) {
            if (is_array($value)) {
              foreach($value as $v) {
                if ($v != $remValue) {
                  $newParams[$key][] = $v;
                }
              }
            } else {
              if ($value != $remValue) {
                // leave param and value in list
                $newParams[$key] = $value;
              }
            }
          }
        }
      }
      return '/produkty?' . http_build_query($newParams, '', '&');
    });
    $twig->addFunction($stripUrlParam);

    // add function to generate facebook oauth url
    $fburl = new \Twig_SimpleFunction('fbLoginUrl', function($c) {
      return OauthProvider::fbLoginUrl($c);
    });
    $twig->addFunction($fburl);

    // add som other variables
    // sessionclass, lang, url, cc
    $this->data['sessionclass'] = new \m4\m4mvc\helper\Session;
    $this->data['lang'] = $GLOBALS['lang'];
    $this->data['url']  = Url::getAll();
    $this->data['cc'] = COUNTRY_CODE;

    // render the view
    echo $twig->render($view."", $this->data);
  } 

}
