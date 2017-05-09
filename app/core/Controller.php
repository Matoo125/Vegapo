<?php

namespace app\core;

use app\string\Url;


class Controller {

    protected $data = [];
    protected $model;
    protected $view;

    /**
     * @param $model String
     * @return mixed new instance of model
     */
    public function model($model) {
        require_once APP . DS . 'model' . DS . $model . '.php';
        $model = "app\\model\\" . $model;
        return new $model;
    }

    public function view($view) {

        // return if no view to render
        if (!$view && !$this->view) {

            echo json_encode($this->data);
            return;

        };

        // if someone wants to render view in different path
        if ($this->view) {
            $view = $this->view;
        }


        $loader = new \Twig_Loader_Filesystem(APP . DS . 'view');
        $twig = new \Twig_Environment( $loader, array(
            'debug' => true,
           // 'cache' => APP . DS . 'cache' ,
        ) );
        $twig->addExtension(new \Twig_Extension_Debug());
        $twig->addGlobal("session", $_SESSION);

        $slugifilter = new \Twig_Filter('slugifilter', 'slugify');
        $twig->addFilter($slugifilter);

        $buildUrl = new \Twig_SimpleFunction('buildUrl', function($params, $key, $value) {
            // replace old part of params for new
            $params[$key] = $value;
            // build url string
            return '/produkty?' . http_build_query($params, '', '&');
        });
        $twig->addFunction($buildUrl);

        $this->data['sessionclass'] = new Session;
        $this->data['lang'] = $GLOBALS['lang'];
        $this->data['url']  = Url::getAll();
        $this->data['cc'] = COUNTRY_CODE;

        echo $twig->render($view.".twig", $this->data);
    }

}