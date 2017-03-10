<?php

class Produkty extends Controller {

    private $model = null;
    private $view = null;
    private $data = null;

    public function __construct()
    {
        $this->model = $this->model('Product');
    }

    public function index($category_slug = null, $supermarket_slug = null, $tag_slug = null, $current_page = 1) {
        $this->admin_index($category_slug, $supermarket_slug, $tag_slug, $current_page);
        $this->view = "public/home/produkty";
    }

    public function pridat() {
        $this->admin_pridat();
        $this->view = "public/home/pridat";
    }

    public function admin_index($category_slug = null, $supermarket_slug = null, $tag_slug = null, $current_page = 1) {
        $this->view = 'admin/produkty/index';

        $this->data['current_tag'] = 0;
        $this->data['current_category'] = 0;
        $this->data['current_supermarket'] = 0;

        // count number of products
        $number_of_products = $this->model->countTable('products', array('visibility' => 1));
        $number_of_pages = ceil($number_of_products / 20);
        // get number of pages

        $start = ($current_page - 1 ) * 20;

        $products = $this->model->getProducts($category_slug, $supermarket_slug,$tag_slug, $start, 1);
        $categories = $this->model->getCategories();
        $supermarkets = $this->model->getSupermarkets();


        if($category_slug != null) {
             for($i = 0; $i < count($categories); $i++) {
                if($categories[$i]['slug'] == $category_slug) {
                    $this->data['current_category'] = $categories[$i];
                    unset($categories[$i]);
                }
            }
        }

        if($supermarket_slug != null) {
            for($i = 0; $i < count($supermarkets); $i++) {
                if($supermarkets[$i]['slug'] == $supermarket_slug) {
                    $this->data['current_supermarket'] = $supermarkets[$i];
                    unset($supermarkets[$i]);
                }
            }
        }


        $this->data['supermarkets'] = $supermarkets;
        $this->data['categories'] = $categories;
        $this->data['products'] = $products;
        $this->data['number_of_pages'] = $number_of_pages;
        $this->data['current_page'] = $current_page;

    }

    public function admin_ziadosti($action = null, $id = null) {
        $this->view = 'admin/produkty/ziadosti';

        if( isset($action) && isset($id) ) {
            if ($action == "accept") {
                 $this->model->setVisibility(1, $id);
            } elseif ($action == "deny") {
                 $this->model->setVisibility(3, $id);
            }
            redirect("admin/produkty/ziadosti");
        } 

        $products = $this->model->getProducts(null, null, 2);

        $this->data['products'] = $products;
    }

    public function admin_trash($action = null, $id = null, $image = null) {
        $this->view = "admin/produkty/trash";

        if( isset($action) && isset($id) ) {
            if ($action == "recover") {
                $this->model->setVisibility(2, $id);
            } elseif ($action == "delete") {
                $this->model->delete($id, "id", $image);
            } elseif ($action == "move") {
                $this->model->setVisibility(3, $id);
            }
            redirect("admin/produkty/trash");
        }

        $this->data['products'] = $this->model->getProducts(null, null, 3);
    }

    public function admin_pridat() {

        $this->view = 'admin/produkty/pridat';

        if ($_POST) {
            $data['name'] = $_POST['productName'];
            $data['category_id'] = $_POST['selectCategory'];
            $data['image'] = $_FILES['file'];
            $data['price'] = $_POST['productPrice'];
            $supermarkets = isset($_POST['supermarket']) ? $_POST['supermarket'] : array();
            $tags = isset($_POST['tag']) ? $_POST['tag'] : array();

            if (!$data['image'] = $this->model->uploadImage($data['image'], "products")) {
                $data['image'] = 'none';
            }

            if(Session::get('user_role') !== null && Session::get('user_role') > 20) {
                $visibility = 1;
            } else {
                $visibility = 2;
            }

            if ( $id = $this->model->insert($data, true, $visibility) ){
                // get last inserted id
                $this->model->matching_supermarkets($id, $supermarkets);
                $this->model->matching_tags($id, $tags);

                Session::setFlash(PRODUCT_ADD_SUCCESS, "success");
            }

        }

        $this->data['categories'] = $this->model->getCategories();
        $this->data['supermarkets'] = $this->model->getSupermarkets();
        $this->data['tags'] = $this->model->getTags();

    }

    public function admin_vymazat($id, $image) {

        if ($this->model->delete($id, "id", $image)) {
            Session::setFlash("Produkt vymazany uspesne", 'success', 1);
        } else {
            Session::setFlash("Produkt sa nepodarilo vymazat", 'danger', 1);
        }

        redirect('admin/produkty');
    }

    public function admin_upravit($id) {
        $this->view = 'admin/produkty/upravit';

        if ($_POST) {
            $data['name'] = $_POST['productName'];
            $data['category_id'] = $_POST['selectCategory'];
            $data['image'] = $_FILES['file'];
            $data['price'] = $_POST['productPrice'];
            $data['id'] = $id;

            // editing supermarkets
            $supermarkets_new = isset($_POST['supermarket']) ? $_POST['supermarket'] : array();
            $supermarkets_old = explode(",", $_POST['supermarkets_old']);
            $added_supermarkets = array_diff($supermarkets_new, $supermarkets_old);
            $deleted_supermarkets = array_diff($supermarkets_old, $supermarkets_new);

            // editing tags
            $tags_new = isset($_POST['tag']) ? $_POST['tag'] : array();
            $tags_old = explode(",", $_POST['tags_old']);
            $added_tags = array_diff($tags_new, $tags_old);
           // echo "<pre>"; print_r($added_tags); echo "</pre>";
            $deleted_tags = array_diff($tags_old, $tags_new);
           // echo "<pre>"; print_r($deleted_tags); echo "</pre>";


            // editing main image
            if (!$data['image'] = $this->model->uploadImage($data['image'], "products")) {
                $data['image'] = $_POST['image_old'];
            } else {
                delete_image(ROOT.DS."uploads".DS."products".DS.$_POST['image_old']);
            }

            $this->model->update($data);
            $this->model->matching_supermarkets($id, $added_supermarkets, $deleted_supermarkets);
            $this->model->matching_tags($id, $added_tags, $deleted_tags);
            Session::setFlash(PRODUCT_UPDATE_SUCCESS, "success");
            
        }

        // get product by id, supermarkets and categories
        $this->data['product'] = $this->model->getProductById($id);
        $this->data['categories'] = $this->model->getCategories();
        $this->data['supermarkets'] = $this->model->getSupermarkets();
        $this->data['tags'] = $this->model->getTags();

    }



    public function __destruct()
    {
        $this->view($this->view, $this->data);
    }

}