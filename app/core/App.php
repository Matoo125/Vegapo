<?php

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $method_prefix = '';
    protected $params = [];

    /**
     * App constructor.
     */
    public function __construct()
    {
        $url = $this->parseURL();
        if ($url[0] == 'admin'){
            $this->method_prefix = 'admin_';
            array_shift($url);
            if(check_user_premission(30) != true) redirect(toURL("LOGIN"));
           // if (! Session::get('user_id') || !Session::get('user_role') || !Session::get('user_country')) redirect(LOGIN_PAGE);
        }

        if ($url && file_exists(APP . DS . 'controllers' . DS . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]); // set controller from URL
                array_shift($url);
        }

        require_once APP . DS . 'controllers' . DS . $this->controller . '.php'; // require controller file
        $this->controller = new $this->controller; // create new instance of controller in member variable controller

        // set method if exists and add prefix
        if (isset($url[0]) && method_exists($this->controller, $this->method_prefix . $url[0])) {
            $this->method = $this->method_prefix . $url[0];
            array_shift($url);
        } else {
            $this->method = $this->method_prefix . $this->method;
        }
        // set params from url
        $this->params = $url ? $url : [];
        // call method in controller and pass parameters
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        return null;
    }

}
