<?php

namespace app\controllers\api;

use app\core\Controller;
use app\model\Statistic;		

class Info extends Controller
{

	public function __construct()
    {
       // $this->model = $this->model("Tag");
    }

    public function index ($page)
    {
      switch ($page) {
        case 'o-autoroch':
          $this->data['title'] = $GLOBALS['lang']['ABOUT_AUTHORS'];
        break;
        case 'o-projekte':
          $this->data['title'] = $GLOBALS['lang']['ABOUT_PROJECT'];
        break;
        case 'o-tom-ako-pomoct':
          $this->data['title'] = $GLOBALS['lang']['ABOUT_HELP'];
        break;
        case 'faq':
          $this->data['title'] = $GLOBALS['lang']['FAQ'];
        break;
        default:
          $this->data['title'] = $page;
        break;
      }

      if (file_exists($location = ROOT . DS . 'pages' . DS . COUNTRY_CODE . DS . $page . '.md')) {
          $page = file_get_contents($location);
          $Parsedown = new \Parsedown();
          $this->data['parsedown'] = $Parsedown->text($page);
      } else {
          $this->data['title'] = "Page not found";
          $this->data['parsedown'] =   "Please contact us if you think it is mistake on our side. Thank you.";
      }
    }

    public function statistics ()
    {
      $this->data['stats'] = (new Statistic)->list();
    }

}
