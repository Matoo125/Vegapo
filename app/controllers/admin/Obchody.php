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
            $data['name'] = $_POST['supermarketName'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if (!$data['image'] = $this->model->uploadImage($image, "supermarkets")) {
                $data['image'] = 'none';
            }

            if ($this->model->insert($data)) {
                Session::setFlash(getString('SUPERMARKET_ADD_SUCCESS'), 'success');
            }
        }

        $this->data['supermarkets'] = $this->model->getSupermarkets();
    }

    public function upravit($id) {

        if ($_POST) {
            $data['name'] = $_POST['supermarketName'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if ($image['error'] == 4) {
                $data['image'] = $_POST['image_old'];
            } elseif ($image['error'] == 0 ){
                $data['image'] = $this->model->uploadImage($image, 'supermarkets');
                delete_image(ROOT.DS."uploads".DS."supermarkets".DS.$_POST['image_old']);
            }

            if ($this->model->update($data, $id)) {
                Session::setFlash(getString('SUPERMARKET_UPDATE_SUCCESS'), 'success');
            } else {
                Session::setFlash(getString('SUPERMARKET_UPDATE_FAILED'), 'danger');
            }
        }

        $this->data['supermarket']  = $this->model->getSupermarketById($id);

    }

    public function vymazat($id, $image) {
        return false; // do not delete supermarket
        if ($this->model->delete($id, "id", $image)) {
            // delete all matching tables
            $this->model->runQuery("DELETE FROM matching_supermarkets WHERE id = :id", array("id" => $id), "post");
            Session::setFlash(getString('SUPERMARKET_DELETE_SUCCESS'), 'success', 1);
        } else {
            Session::setFlash(getString('SUPERMARKET_DELETE_FAILED'), 'danger', 1);
        }

        redirect('/admin/obchody');
    }

}