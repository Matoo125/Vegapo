<?php 

namespace app\controllers\admin;

use app\controllers\api\Tagy as TagyApiController;
use app\core\Session;

class Tagy extends TagyApiController
{
    public function __construct()
    {
    	parent::__construct();
        if (!check_user_premission(35)) redirect('/');
    }

    public function pridat() {

        if ($_POST) {
            $data['name'] = $_POST['name'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if (!$data['image'] = $this->model->uploadImage($image, "tags")) {
                $data['image'] = 'none';
            }

            if ($this->model->insert($data)) {
                Session::setFlash("Tag pridany uspesne", 'success');
            }
        }

        $this->data['tags'] = $this->model->getTags();
    }

    public function upravit($id) {

        if ($_POST) {
            $data['name'] = $_POST['name'];
            $image = $_FILES['file'];
            $data['description'] = $_POST['description'];
            $data['note'] = $_POST['note'];

            if ($image['error'] == 4) {
                $data['image'] = $_POST['image_old'];
            } elseif ($image['error'] == 0 ){
                $data['image'] = $this->model->uploadImage($image, 'tags');
                delete_image(ROOT.DS."uploads".DS."tags".DS.$_POST['image_old']);
            }



            if ($this->model->update($data, $id)) {
                Session::setFlash("Tag zmeneny", 'success');
            } else {
                Session::setFlash("Nastala chyba", 'danger');
            }
        }

        $this->data['tags'] = $this->model->getTags();
        $this->data['tag']  = $this->model->getTagById($id);

        //echo '<pre>';print_r($this->data['supermarket']); die;

    }

    public function vymazat($id, $image) {
        return false; // do not delete tag
        if ($this->model->delete($id, "id", $image)) {
            // delete all matching tables
            $this->model->runQuery("DELETE FROM matching_tags WHERE id = :id", array("id" => $id), "post");
            Session::setFlash("Tag vymazany uspesne", 'success', 1);
        } else {
            Session::setFlash("Tag sa nepodarilo vymazat", 'danger', 1);
        }

        redirect('/admin/tagy');
    }}