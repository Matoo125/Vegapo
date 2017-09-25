<?php

namespace app\controllers\admin;

use app\controllers\api\Obchody as ObchodyApiController;
use m4\m4mvc\helper\Session;
use app\controllers\api\Users;
use app\helper\Image;

use mrkovec\sdiff\SDiff;

class Obchody extends ObchodyApiController
{
    public function __construct()
    {
    	parent::__construct();
        if (!Users::check_premission(35)) redirect('/');
    }

    public function pridat() {

        if ($_POST) {
            $data['name'] = $_POST['supermarketName'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if(!$image['name']) $data['image'] = 'none';
            elseif (!$data['image'] = Image::upload($image, "supermarkets")) {
                $data['image'] = 'none';
            }

            if ($id = $this->model->insert($data)) {
              // log edit
              $this->model->createEdit($id);
              Session::setFlash(getString('SUPERMARKET_ADD_SUCCESS'), 'success');
            }
        }

        $this->data['supermarkets'] = $this->model->list();
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
                $data['image'] = Image::upload($image, 'supermarkets');
                Image::delete($_POST['image_old'], 'supermarkets');
            }

            $old_store = $this->model->getSupermarketById($id);

            if ($this->model->update($data, $id)) {

              // log edit
              $this->model->createEdit($id,
                'update',
                SDiff::getObjectDiff(
                  $old_store,
                  $this->model->getSupermarketById($id),
                  False
                ),
                $_POST['edit_comment']
              );

              Session::setFlash(getString('SUPERMARKET_UPDATE_SUCCESS'), 'success');
            } else {
                Session::setFlash(getString('SUPERMARKET_UPDATE_FAILED'), 'danger');
            }
        }

        $this->data['supermarket']  = $this->model->getSupermarketById($id);

    }

    public function vymazat($id, $image) {
        redirect('/admin/obchody');
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
