<?php

namespace app\controllers\admin;

use app\controllers\api\Testimonials as TestimonialsApiController;
use m4\m4mvc\helper\Session;
use app\controllers\api\Users;

class Testimonials extends TestimonialsApiController
{
    public function __construct()
    {
      parent::__construct();
      if (!Users::check_premission(30)) redirect('/');
    }

    public function index()
    {
        $this->data['testimonials'] = $this->model->getAll();
    }

    public function create()
    {
        if ($_POST && $this->model->create([
                'name'      =>  $_POST['name'],
                'message'   =>  $_POST['message']
            ])) {
            
            Session::setFlash("Ohlas pridany uspesne", 'success');
            redirect('/admin/testimonials');
        } else if ($_POST) {
            Session::setFlash('Ohlas sa nepodarilo vytvoriť.', 'danger');
        }

    }

    public function update ($id)
    {
        if ($_POST) {
            if ($this->model->update([
                'name'      =>  $_POST['name'],
                'message'   =>  $_POST['message'],
                'id'        =>  $_POST['id']
            ])) {
                Session::setFlash('Ohlas upravený úspešne. ', 'success');
            } else {
                Session::setFlash('Ohlas sa nepodarilo upraviť. ', 'danger');
            }
        }

        $this->data['testimonial'] = $this->model->getById($id);


    }

    public function remove ($id)
    {
        $this->model->remove($id);
        redirect('/admin/testimonials');
    }


}
