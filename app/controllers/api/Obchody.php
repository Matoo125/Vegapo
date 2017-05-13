<?php 

namespace app\controllers\api;

use app\core\Controller; 

class Obchody extends Controller
{
	public function __construct()
    {
        $this->model = $this->model("Store");
    }

    public function index() {

        $this->data['stores'] = $this->model->getSupermarkets();

        if ($this->data['stores']) {
            $x = 0;
            foreach ($this->data['stores'] as $store) {
                $this->data['stores'][$x]['numberOfProducts'] = $this->model->countTable("matching_supermarkets", array('supermarket_id' => $store['id'] ));
                $x++;
            }
        }
    }

}