<?php 

namespace app\controllers\api;

use app\core\Controller; 

class Suggestions extends Controller
{
	public function __construct()
    {
        $this->model = $this->model("Suggestion");
    }

    public function index()
    {
        $this->data['result'] = '1';
    }

    public function add() {

      if (!$_POST) return;

       $data = [];
       
       $data['reason'] = $_POST['reason'];
       $data['body'] = $_POST['body'];
       $data['author_id'] = $_POST['user_id'];
       $data['product_id'] = $_POST['product_id'];
       $data['country'] = $_POST['country'];

       if ($this->model->save($data)) {
        $this->data['result'] = '1';
       } else {
        $this->data['result'] = 'something wrong';
       }

       return;
    }


}