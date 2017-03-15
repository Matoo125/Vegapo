<?php

class Kategorie extends Controller {

    public function __construct()
    {
        if (!check_user_premission(35)) redirect('/');
        $this->model = $this->model('Category');
    }

    public function index() {
        $this->admin_index();
        $this->view = "public/home/kategorie";

    }


    public function admin_index() {

        $this->view = 'admin/kategorie/index';
        $this->data['categories'] = $this->model->getCategories();
        if ($this->data['categories']) {
            $x = 0;
            foreach ($this->data['categories'] as $category) {
                $this->data['categories'][$x]['numberOfProducts'] = $this->model->countTable("products", array("category_id" => $category['id']) );
                $x++;
            }
        }

    }

    public function admin_pridat() {
        $this->view = 'admin/kategorie/pridat';

        if ($_POST) {
            $categoryName = $_POST['categoryName'];
            $categoryParent = $_POST['categoryParent'];
            $image = $_FILES['file'];

            if (!$image = $this->model->uploadImage($image, "categories")) {
                $image = 'none';
            }

            if ($this->model->insert($categoryName, $categoryParent, $image)) {
                Session::setFlash(getString('CATEGORY_ADD_SUCCESS'), "success");
            }
        }

        $categories = $this->model->getCategories();
        $this->data['categories'] = $categories;


    }

    public function admin_upravit($id) {
        $this->view = '/admin/kategorie/upravit';

        if ($_POST) {
            $categoryName = $_POST['categoryName'];
            $categoryParent = $_POST['categoryParent'];
            $image = $_FILES['file'];

            if (!$image = $this->model->uploadImage($image, "categories")) {
                $image = $_POST['image_old'];
            } else {
                delete_image(ROOT.DS."uploads".DS."categories".DS.$_POST['image_old']);
            }

            if ($this->model->update($categoryName, $categoryParent, $image, $id)) {
                Session::setFlash(getString('CATEGORY_UPDATE_SUCCESS'), "success");
            }
        }

        $category = $this->model->getCategoryById($id);
        $this->data['category'] = $category;

        $categories = $this->model->getCategories();
        $this->data['categories'] = $categories;
    }

    public function admin_vymazat($id, $image) {

        if ($this->model->delete($id, "id", $image)) {
            Session::setFlash("Kategória vymazany uspesne", 'success', 1);
        } else {
            Session::setFlash("Kategóriu sa nepodarilo vymazat", 'danger', 1);
        }

        redirect('/admin/kategorie');
    }

    public function __destruct()
    {
        $this->view($this->view, $this->data);
    }

}