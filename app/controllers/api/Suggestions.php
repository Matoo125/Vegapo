<?php 

namespace app\controllers\api;

use app\core\Controller; 

class Suggestions extends Controller
{
	public function __construct()
    {
        $this->model = $this->model("Suggestion");
        $this->data['matchingType'] = [
            0 => 'supermarket',
            1 => 'kategória',
            2 => 'tag',
            3 => 'obrázok',
            4 => 'ingrediencie obrázok',
            5 => 'iny obrázok',
            6 => 'poznámka',
            7 => 'zloženie',
            8 => 'čiarovy kód',
            9 => 'nahlásenie',
            10 => 'niečo iné'
        ];

        $this->data['matchingState'] = [
          1 => getString('ACCEPTED'),
          2 => getString('DENIED'),
          3 => getString('WAITING')
        ];
    }

    public function index()
    {
      if (isset($_GET['autor'])) {
           $id = $_GET['autor'];

           $this->data['suggestions'] = $this->model->getByUser($id);
           return;
      } 

      redirect('/');
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