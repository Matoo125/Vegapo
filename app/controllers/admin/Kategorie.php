<?php 

namespace app\controllers\admin;

use app\controllers\api\Kategorie as KategorieApiController;
use app\core\Session;


class Kategorie extends KategorieApiController
{
    public function __construct()
    {
    	parent::__construct();
        if (!check_user_premission(35)) redirect('/');
    }

    public function pridat() {

        if ($_POST) {
            $data['name'] = $_POST['categoryName'];
            $data['parent'] = $_POST['categoryParent'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if (!$data['image'] = $this->model->uploadImage($image, "categories")) {
                $data['image'] = 'none';
            }

            if ($this->model->insert($data)) {
                Session::setFlash(getString('CATEGORY_ADD_SUCCESS'), "success");
            }
        }

        $categories = $this->model->getCategories();
        $this->data['categories'] = $categories;

    }

    public function upravit($id) {

        if ($_POST) {
            $data['name'] = $_POST['categoryName'];
            $data['parent'] = $_POST['categoryParent'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if (!$data['image'] = $this->model->uploadImage($image, "categories")) {
                $data['image'] = $_POST['image_old'];
            } else {
                delete_image(ROOT.DS."uploads".DS."categories".DS.$_POST['image_old']);
            }

            if ($this->model->update($data, $id)) {
                Session::setFlash(getString('CATEGORY_UPDATE_SUCCESS'), "success");
            }
        }

        $category = $this->model->getCategoryById($id);
        $this->data['category'] = $category;

        $categories = $this->model->getCategories();
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