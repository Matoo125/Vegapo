<?php

namespace app\core;

use m4\m4mvc\core\Controller as FrameworkController;
use app\string\Url;


class Controller extends FrameworkController {

    public function model ($name) {
      return $this->getModel($name);
    }
    
    public function view($view) {

        // return if no view to render
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


        $loader = new \Twig_Loader_Filesystem(APP . DS . 'view');

        if (DEVELOPMENT) {
            $twig = new \Twig_Environment( $loader, array(
                'debug' => true,
            ) );
        } else {
            $twig = new \Twig_Environment( $loader, array(
                'cache' => APP . DS . 'cache' ,
            ) );
        }

        $twig->addExtension(new \Twig_Extension_Debug());
        $twig->addGlobal("session", $_SESSION);

        $slugifilter = new \Twig_Filter('slugifilter', 'slugify');
        $twig->addFilter($slugifilter);

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

        // removes $params[$remKey] (or $params[$remKey][$remValue]) from $params list
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

        $this->data['sessionclass'] = new Session;
        $this->data['lang'] = $GLOBALS['lang'];
        $this->data['url']  = Url::getAll();
        $this->data['cc'] = COUNTRY_CODE;

        echo $twig->render($view."", $this->data);
    }

}
