<?php

namespace app\controllers\admin;

use app\controllers\api\Tagy as TagyApiController;
use m4\m4mvc\helper\Session;
use app\controllers\api\Users;
use app\helper\Image;
use mrkovec\sdiff\SDiff;

class Tagy extends TagyApiController
{
    public function __construct()
    {
    	parent::__construct();
        if (!Users::check_premission(35)) redirect('/');
    }

    public function pridat()
    {
        if ($_POST) {
            $data['name'] = $_POST['name'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if(!$image['name']) $data['image'] = 'none';
            elseif (!$data['image'] = Image::upload($image, "tags")) {
                $data['image'] = 'none';
            }

            if ($id = $this->model->insert($data)) {
                Session::setFlash("Tag pridany uspesne", 'success');
                // log edit
                $this->model->createEdit($id);
            }
        }

        $this->data['tags'] = $this->model->list();
    }

    public function upravit($id)
    {
        if ($_POST) {
            $data['name'] = $_POST['name'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if ($image['error'] == 4) {
                $data['image'] = $_POST['image_old'];
            } elseif ($image['error'] == 0 ){
                $data['image'] = Image::upload($image, 'tags');
                Image::delete($_POST['image_old'], 'tags');
            }

            $old_tag = $this->model->getTagById($id);

            if ($this->model->update($data, $id)) {

              // log edit
              $this->model->createEdit($id,
                'update',
                SDiff::getObjectDiff(
                  $old_tag,
                  $this->model->getTagById($id),
                  False
                ),
                $_POST['edit_comment']
              );

              Session::setFlash("Tag zmeneny", 'success');
            } else {
                Session::setFlash("Nastala chyba", 'danger');
            }
        }

        $this->data['tags'] = $this->model->list();
        $this->data['tag']  = $this->model->getTagById($id);

    }

    public function vymazat($id, $image)
    {
        redirect('/admin/tagy'); // otherwise error
        return false; // do not delete tag

        if ($this->model->delete($id, "id", $image)) {
            // delete all matching tables
            $this->model->runQuery("DELETE FROM matching_tags WHERE id = :id", array("id" => $id), "post");
            // log edit
            $this->model->createEdit($id, 'delete');

            Session::setFlash("Tag vymazany uspesne", 'success', 1);
        } else {
            Session::setFlash("Tag sa nepodarilo vymazat", 'danger', 1);
        }

        redirect('/admin/tagy');
    }
}
