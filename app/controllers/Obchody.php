<?php

class Obchody extends Controller {

    private $model = null;
    private $view = null;
    private $data = null;

    public function __construct()
    {
        $this->model = $this->model("Store");
    }

    public function index() {
        $this->admin_index();
        $this->view = "public/home/obchody";

    }

    public function admin_index() {

        $this->view = 'admin/obchody/index';
        $this->data['stores'] = $this->model->getSupermarkets();

        if ($this->data['stores']) {
            $x = 0;
            foreach ($this->data['stores'] as $store) {
                $this->data['stores'][$x]['numberOfProducts'] = $this->model->countTable("matching_supermarkets", array('supermarket_id' => $store['id'] ));
                $x++;
            }
        }
    }

    public function admin_pridat() {
        $this->view = 'admin/obchody/pridat';

        if ($_POST) {
            $supermarketName = $_POST['supermarketName'];
            $image = $_FILES['file'];

            if (!$image = $this->model->uploadImage($image, "supermarkets")) {
                $image = 'none';
            }

            if ($this->model->insert($supermarketName, $image)) {
                Session::setFlash(SUPERMARKET_ADD_SUCCESS, 'success');
            }
        }

        $this->data['supermarkets'] = $this->model->getSupermarkets();
    }

    public function admin_upravit($id) {
        $this->view = 'admin/obchody/upravit';

        if ($_POST) {
            $supermarketName = $_POST['supermarketName'];
            $image = $_FILES['file'];

            if ($image['error'] == 4) {
                $image = $_POST['image_old'];
            } elseif ($image['error'] == 0 ){
                $image = $this->model->uploadImage($image, 'supermarkets');
                delete_image(ROOT.DS."uploads".DS."supermarkets".DS.$_POST['image_old']);
            }



            if ($this->model->update($supermarketName, $image, $id)) {
                Session::setFlash(SUPERMARKET_UPDATE_SUCCESS, 'success');
            } else {
                Session::setFlash(SUPERMARKET_UPDATE_FAILED, 'danger');
            }
        }

        $this->data['supermarkets'] = $this->model->getSupermarkets();
        $this->data['supermarket']  = $this->model->getSupermarketById($id);

        //echo '<pre>';print_r($this->data['supermarket']); die;

    }

    public function admin_vymazat($id, $image) {

        if ($this->model->delete($id, "id", $image)) {
            // delete all matching tables
            $this->model->runQuery("DELETE FROM matching_supermarkets WHERE id = :id", array("id" => $id), "post");
            Session::setFlash("Supermarket vymazany uspesne", 'success', 1);
        } else {
            Session::setFlash("Supermarket sa nepodarilo vymazat", 'danger', 1);
        }

        redirect('admin/obchody');
    }

    

    public function __destruct()
    {
        $this->view($this->view, $this->data);
    }

}