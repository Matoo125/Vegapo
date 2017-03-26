<?php 

namespace app\controllers\admin;

use app\controllers\api\Obchody as ObchodyApiController;
use app\core\Session;

class Obchody extends ObchodyApiController
{
    public function __construct()
    {
    	parent::__construct();
        if (!check_user_premission(35)) redirect('/');
    }

    public function pridat() {

        if ($_POST) {
            $supermarketName = $_POST['supermarketName'];
            $image = $_FILES['file'];

            if (!$image = $this->model->uploadImage($image, "supermarkets")) {
                $image = 'none';
            }

            if ($this->model->insert($supermarketName, $image)) {
                Session::setFlash(getString('SUPERMARKET_ADD_SUCCESS'), 'success');
            }
        }

        $this->data['supermarkets'] = $this->model->getSupermarkets();
    }

    public function upravit($id) {

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
                Session::setFlash(getString('SUPERMARKET_UPDATE_SUCCESS'), 'success');
            } else {
                Session::setFlash(getString('SUPERMARKET_UPDATE_FAILED'), 'danger');
            }
        }

        $this->data['supermarkets'] = $this->model->getSupermarkets();
        $this->data['supermarket']  = $this->model->getSupermarketById($id);

        //echo '<pre>';print_r($this->data['supermarket']); die;

    }

    public function vymazat($id, $image) {

        if ($this->model->delete($id, "id", $image)) {
            // delete all matching tables
            $this->model->runQuery("DELETE FROM matching_supermarkets WHERE id = :id", array("id" => $id), "post");
            Session::setFlash("Supermarket vymazany uspesne", 'success', 1);
        } else {
            Session::setFlash("Supermarket sa nepodarilo vymazat", 'danger', 1);
        }

        redirect('/admin/obchody');
    }

}