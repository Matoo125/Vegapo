<?php 

namespace app\controllers\api;

use app\core\Controller; 

class Tagy extends Controller
{    

	public function __construct()
    {
        $this->model = $this->model("Tag");
    }

    public function index() 
    {
        $this->data['tags'] = $this->model->list();

        if ($this->data['tags']) {
            $x = 0;
            foreach ($this->data['tags'] as $tag) {
                $this->data['tags'][$x]['numberOfProducts'] = $this->model->countTableCS("matching_tags", array('tag_id' => $tag['id'] ));
                $x++;
            }
        }
    }
}