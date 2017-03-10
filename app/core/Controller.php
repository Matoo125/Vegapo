<?php

class Controller {

    /**
     * @param $model String
     * @return mixed new instance of model
     */
    public function model($model) {
        require_once APP . DS . 'model' . DS . $model . '.php';
        return new $model;
    }

    public function view($view, $data = []) {
        $loader = new Twig_Loader_Filesystem(APP . DS . 'view');
        $twig = new Twig_Environment( $loader, array(
            'debug' => true,
        ) );
        $twig->addExtension(new Twig_Extension_Debug());
        $twig->addGlobal("session", $_SESSION);

        $data['sessionclass'] = new Session;
        $data['lang'] = $GLOBALS['lang'];
        $data['url']  = $GLOBALS['url'];

        echo $twig->render($view.".twig", $data);
    }

}