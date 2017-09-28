<?php 

namespace app\controllers\api; 

use app\core\Controller;
use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Session;
/*
 * Home API Controller
 * extends base Controller
 * use Dashboard model
 */
class Comments extends Controller
{
  public function __construct()
  { 
    $this->model = $this->model('Comment');
  }

  public function index ()
  {
    $c = $this->model->list(['product_id' => 1]);
    echo '<pre>'; print_r($c);die;
  }

  public function add ()
  {
    if (!$author = Session::get('user_id')) {
      Response::error('You are not logged in.');
    }

    Request::forceMethod('post');
    Request::required(['product_id', 'body']);

    $this->model->insert([
      'author_id'   =>  $author,
      'product_id'  =>  $_POST['product_id'],
      'body'        =>  $_POST['body']
    ]);
  }

}