<?php 

namespace app\controllers\api;

use app\core\Controller; 

class Testimonials extends Controller
{
  public function __construct()
    {
        $this->model = $this->model("Testimonial");


    }

    public function index()
    {

    }





}