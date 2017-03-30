<?php 

namespace app\controllers\api;

use app\core\Controller; 
use app\core\Session;

class Produkty extends Controller
{
    public function __construct()
    {
        $this->model = $this->model('Product');
    }


	public function index($category_slug = null, $supermarket_slug = null, $tag_slug = null, $current_page = 1) {

        $categories = $this->model->getCategories();
        $supermarkets = $this->model->getSupermarkets();
        $tags = $this->model->getTags();

        $current_category = findBySlugInArray($category_slug, $categories);
        $current_supermarket = findBySlugInArray($supermarket_slug, $supermarkets);
        $current_tag = findBySlugInArray($tag_slug, $tags);

        $number_of_products = $this->model->count(
            isset($current_category['id']) ? $current_category['id'] : null,
            isset($current_supermarket['id']) ? $current_supermarket['id'] : null,
            isset($current_tag['id']) ? $current_tag['id'] : null
        )['numberOfProducts'];

        $number_of_pages = ceil($number_of_products / 20);
        $start = ($current_page - 1 ) * 20;

        $this->data['supermarkets'] = $supermarkets;
        $this->data['categories'] = $categories;
        $this->data['tags'] = $tags;
        $this->data['current_supermarket'] = $current_supermarket;
        $this->data['current_category'] = $current_category;
        $this->data['current_tag'] = $current_tag;
        $this->data['number_of_pages'] = $number_of_pages;
        $this->data['current_page'] = $current_page;
        $this->data['products'] = $this->model->getProducts($category_slug, $supermarket_slug,$tag_slug, $start, 1);


    }

    public function produkt($slug = null)
    {
    	$this->data['product'] = $this->model->getProductBySlug($slug);
    }

    public function pridat() {

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

            // check for duplicant
            if ($this->model->getProductBySlug(slugify($data['name']))) {
                Session::setFlash(getString('PRODUCT_ALREADY_EXISTS'), "danger");
            } else {
                if ( $id = $this->model->insert($data, true, $visibility) ){
                    // get last inserted id
                    $this->model->matching_supermarkets($id, $supermarkets);
                    $this->model->matching_tags($id, $tags);

                    Session::setFlash(getString('PRODUCT_ADD_SUCCESS'), "success");
                }
            }



        }

        $this->data['categories'] = $this->model->getCategories();
        $this->data['supermarkets'] = $this->model->getSupermarkets();
        $this->data['tags'] = $this->model->getTags();

    }

    // get products by user id 
    public function user($id, $current_page = 1)
    {
        $this->view = 'web/produkty/index';
        $number_of_products = $this->model->count(null, null, null, null, $id)['numberOfProducts'];

        $number_of_pages = ceil($number_of_products / 20);
        $start = ($current_page - 1 ) * 20;

        $this->data['number_of_pages'] = $number_of_pages;
        $this->data['current_page'] = $current_page;
        $this->data['products'] = $this->model->getProducts(null, null, null, $start, null, $id);
    }

}