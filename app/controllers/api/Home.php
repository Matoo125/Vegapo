<?php 

namespace app\controllers\api; 

use app\core\Controller;
/*
 * Home API Controller
 * extends base Controller
 * use Dashboard model
 */
class Home extends Controller
{
	public function __construct()
    {
        $this->model = $this->model('Dashboard');
    }

    public function index()
    {
    	
    }
}