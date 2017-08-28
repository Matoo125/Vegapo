<?php

namespace app\core;

use m4\m4mvc\core\App as FrameworkApp;

use app\helper\Redirect;

class App extends FrameworkApp
{
  /*  public $controller = 'Home';
    public $method = 'index';
    public $module = 'web';
    public $params = [];
    public $view;

    /**
     * App constructor.
     */
 /*   public function __construct()
    {
        // create array from and clean the url
        $url = $this->parseURL();

        // set admin prefix if exists
         if ($url[0] == 'admin'){
            $this->module = 'admin';
            array_shift($url);
            if (! Session::get('user_id')) Redirect::toURL("LOGIN"); // redirect(toURL("LOGIN"));
          }

        // set api prefix if exists
        if (isset($url[0]) && $url[0] == 'api') {
            $this->module = "api";
            array_shift($url);
        }

        // set first folder for view
        $this->view = $this->module . DS;

        // set controller
        if ($url && file_exists(APP . DS . 'controllers' . DS . $this->module . DS . ucfirst($url[0] ) . '.php')) {
            $this->controller = ucfirst($url[0]); // set controller from URL
            array_shift($url);
        }
        // set second folder for view
        $this->view .= lcfirst($this->controller) . DS;

        // require controller file
        require_once APP . DS . 'controllers' . DS . $this->module . DS . $this->controller . '.php';

        // prepend namespace
        // create new instance of the controller
        $controller = "app\controllers\\" . $this->module . "\\" . $this->controller;
        $this->controller = new $controller();

        // replace - with _

        if ( isset( $url[0] ) && method_exists( $this->controller, $url[0] ) ) {
            $this->method = $url[0];
            array_shift($url);
        }
        // set file for view
        $this->view .= $this->method;

        // set params if possible
        $this->params = $url ? $url : [];

        if ( method_exists( $this->controller, $this->method) ) {
          // call controller's method with parameters
          call_user_func_array([$this->controller, $this->method], $this->params);
          // call view
          if (file_exists(APP . DS . "view" . DS .$this->view . ".twig")) {
             call_user_func_array([$this->controller, 'view'], [$this->view]);
          } else {
             call_user_func([$this->controller, 'view'], null);
          }
        }


    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        return null;
    }
*/
}
