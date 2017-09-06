<?php 

namespace app\controllers\admin;

use app\controllers\api\Kategorie as KategorieApiController;
use m4\m4mvc\helper\Session;
use app\controllers\api\Users;
use app\helper\Image;

class Kategorie extends KategorieApiController
{
    public function __construct()
    {
    	parent::__construct();
        if (!Users::check_premission(35)) redirect('/');
    }

    public function pridat() {

        if ($_POST) {
            $data['name'] = $_POST['categoryName'];
            $data['parent'] = $_POST['categoryParent'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if (!$data['image'] = Image::upload($image, "categories")) {
                $data['image'] = 'none';
            }

            if ($this->model->insert($data)) {
                Session::setFlash(getString('CATEGORY_ADD_SUCCESS'), "success");
            }
        }

        $categories = $this->model->list();
        $this->data['categories'] = $categories;

    }

    public function upravit($id) {

        if ($_POST) {
            $data['name'] = $_POST['categoryName'];
            $data['parent'] = $_POST['categoryParent'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if (!$data['image'] = Image::upload($image, "categories")) {
                $data['image'] = $_POST['image_old'];
            } else {
                Image::delete($_POST['image_old'], 'categories');
            }

            if ($this->model->update($data, $id)) {
                Session::setFlash(getString('CATEGORY_UPDATE_SUCCESS'), "success");
            }
        }

        $category = $this->model->getCategoryById($id);
        $this->data['category'] = $category;

        $categories = $this->model->list();
        $this->data['categories'] = $categories;
    }

    public function vymazat($id, $image) {
        return false; // do not delete category
        if ($this->model->delete($id, "id", $image)) {
            Session::setFlash("Kategória vymazany uspesne", 'success', 1);
        } else {
            Session::setFlash("Kategóriu sa nepodarilo vymazat", 'danger', 1);
        }

        redirect('/admin/kategorie');
    }

}