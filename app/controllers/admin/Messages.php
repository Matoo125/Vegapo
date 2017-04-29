<?php 

namespace app\controllers\admin;

use app\core\Controller; 

/*
 *  25.4.2017 Matej Vrzala
 *  Controller to handle messages
 */

class Messages extends Controller
{
    public function __construct()
    {
        $this->model = $this->model('Message');
    }

	public function index()
	{
        $this->data['messages'] = $this->model->getAll();
	}

    public function read($id)
    {
        $this->data['message'] = $this->model->getById($id);

        print_r($this->data);
    }


}