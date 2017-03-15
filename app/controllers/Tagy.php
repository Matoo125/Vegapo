<?php

class Tagy extends Controller {

    public function __construct()
    {
        if (!check_user_premission(35)) redirect('/');
        $this->model = $this->model("Tag");
    }

    public function index() {
        $this->admin_index();
        $this->view = "public/home/tagy";

    }

    public function admin_index() {

        $this->view = 'admin/tagy/index';
        $this->data['tags'] = $this->model->getTags();

        if ($this->data['tags']) {
            $x = 0;
            foreach ($this->data['tags'] as $tag) {
                $this->data['tags'][$x]['numberOfProducts'] = $this->model->countTable("matching_tags", array('tag_id' => $tag['id'] ));
                $x++;
            }
        }
    }

    public function admin_pridat() {
        $this->view = 'admin/tagy/pridat';

        if ($_POST) {
            $name = $_POST['name'];
            $image = $_FILES['file'];

            if (!$image = $this->model->uploadImage($image, "tags")) {
                $image = 'none';
            }

            if ($this->model->insert($name, $image)) {
                Session::setFlash("Tag pridany uspesne", 'success');
            }
        }

        $this->data['tags'] = $this->model->getTags();
    }

    public function admin_upravit($id) {
        $this->view = 'admin/tagy/upravit';

        if ($_POST) {
            $name = $_POST['name'];
            $image = $_FILES['file'];

            if ($image['error'] == 4) {
                $image = $_POST['image_old'];
            } elseif ($image['error'] == 0 ){
                $image = $this->model->uploadImage($image, 'tags');
                delete_image(ROOT.DS."uploads".DS."tags".DS.$_POST['image_old']);
            }



            if ($this->model->update($name, $image, $id)) {
                Session::setFlash("Tag zmeneny", 'success');
            } else {
                Session::setFlash("Nastala chyba", 'danger');
            }
        }

        $this->data['tags'] = $this->model->getTags();
        $this->data['tag']  = $this->model->getTagById($id);

        //echo '<pre>';print_r($this->data['supermarket']); die;

    }

    public function admin_vymazat($id, $image) {

        if ($this->model->delete($id, "id", $image)) {
            // delete all matching tables
            $this->model->runQuery("DELETE FROM matching_tags WHERE id = :id", array("id" => $id), "post");
            Session::setFlash("Tag vymazany uspesne", 'success', 1);
        } else {
            Session::setFlash("Tag sa nepodarilo vymazat", 'danger', 1);
        }

        redirect('/admin/tagy');
    }



    public function __destruct()
    {
        $this->view($this->view, $this->data);
    }

}