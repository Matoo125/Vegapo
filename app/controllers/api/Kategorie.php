<?php 

namespace app\controllers\api;

use app\core\Controller; 

class Kategorie extends Controller
{
    public function __construct()
    {
        $this->model = $this->model('Category');
    }

    public function index() {

        $this->data['categories'] = $this->model->list();
        if ($this->data['categories']) {
            $x = 0;
            foreach ($this->data['categories'] as $category) {
                $this->data['categories'][$x]['numberOfProducts'] = $this->model->countTableCS("products", array("category_id" => $category['id']) );
                $x++;
            }
        }

    }

 }