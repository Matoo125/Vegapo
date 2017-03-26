<?php

namespace app\core;

use app\string\Url;


class Controller {

    protected $data = [];
    protected $model;

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
        $loader = new \Twig_Loader_Filesystem(APP . DS . 'view');
        $twig = new \Twig_Environment( $loader, array(
            'debug' => true,
        ) );
        $twig->addExtension(new \Twig_Extension_Debug());
        $twig->addGlobal("session", $_SESSION);

        $slugifilter = new \Twig_Filter('slugifilter', 'slugify');
        $twig->addFilter($slugifilter);

        $this->data['sessionclass'] = new Session;
        $this->data['lang'] = $GLOBALS['lang'];
        $this->data['url']  = Url::getAll();

        echo $twig->render($view.".twig", $this->data);
    }

}