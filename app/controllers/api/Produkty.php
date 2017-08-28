<?php

namespace app\controllers\api;

use app\core\Controller;
use app\core\Session;
use app\helper\Image;

class Produkty extends Controller
{
    public function __construct()
    {
        $this->model = $this->model('Product');
    }

    public function produkt($slug = null)
    {
    	$this->data['product'] = $this->model->getProductBySlug($slug);

        if (Session::get('user_id')) {

            $this->data['liked'] = $this->model->isProductFavourite($this->data['product']['id'], Session::get('user_id'))[0];
        }
    }

    public function pridat() {

        if (!Session::get('user_id')) {
            redirect('/');
        }

        if ($_POST) {
            $data['name'] = $_POST['productName'];
            $data['category_id'] = $_POST['selectCategory'];
            $images['1'] = $_FILES['file'];
            $images['2'] = $_FILES['ingredients'];
            $data['price'] = $_POST['productPrice'];
            $data['barcode'] = preg_replace('/\s+/','',$_POST['barcode']);
            $supermarkets = isset($_POST['supermarket']) ? $_POST['supermarket'] : array();
            $tags = isset($_POST['tag']) ? $_POST['tag'] : array();
            $data['note'] = $_POST['note'];


            if(Session::get('user_role') !== null && Session::get('user_role') > 20) {
                $visibility = 1;
            } else {
                $visibility = 2;
            }

            // check for duplicant
            if ($slug = $this->model->checkProduct($data['barcode'], slugify($data['name']))['slug']) {
                Session::setFlash(getString('PRODUCT_ALREADY_EXISTS') . "<a href='/produkty/produkt/$slug'>tu</a>", "warning");
                // to avoid name collision
                $_POST['selectedsupermarkets'] = isset($_POST['supermarket']) ? $_POST['supermarket'] : [];
                $_POST['selectedtags'] = isset($_POST['tag']) ? $_POST['tag'] : [];
                $this->data = $_POST;

            } else {

                if ( $id = $this->model->insert($data, true, $visibility) ){
                    // get last inserted id
                    $this->model->matching_supermarkets($id, $supermarkets);
                    $this->model->matching_tags($id, $tags);

                    // store image in db if it was uploaded
                    if ($images['1'] = Image::upload($images['1'], "products")) {
                        $this->model->insertImage($id, 1, $images['1']);
                    }

                    // store ingredients image in db
                    if ($images['2']['error'] == 0 && $images['2'] = Image::upload($images['2'], "products")) {
                        $this->model->insertImage($id, 2, $images['2']);
                    }

                    Session::setFlash(getString('PRODUCT_ADD_SUCCESS'), "success");
                }
            }



        }

        $this->data['categories'] = $this->model->getCategories();
        $this->data['supermarkets'] = $this->model->getSupermarkets();
        $this->data['tags'] = $this->model->getTags();

    }


    public function index()
    {
        $category_slug = $params['kategoria'] = isset($_GET['kategoria']) ? $_GET['kategoria'] : null;
        $supermarket_slug = $params['supermarket'] = isset($_GET['supermarket']) ? $_GET['supermarket'] : null;
        $tag_slugs = $params['tag'] = isset($_GET['tag']) ? $_GET['tag'] : null;
        $favourites_user_id = $params['oblubene'] = isset($_GET['oblubene']) ? $_GET['oblubene'] : null;
        $search_term = $params['hladat'] = isset($_GET['hladat']) ? $_GET['hladat'] : null;
        $author_id = $params['autor'] = isset($_GET['autor']) ? $_GET['autor'] : null;
        $visibility = $params['stav'] = isset($_GET['stav']) ? $_GET['stav'] : null;

        if ($author_id) {
            // get author username

            $this->data['username'] = $this->model->getUsername($author_id);
        }
        $current_page = isset($_GET['p']) ? $_GET['p'] : 1;

        $categories = $this->model->getCategories();
        $supermarkets = $this->model->getSupermarkets();
        $tags = $this->model->getTags();

        $current_category = findBySlugInArray($category_slug, $categories);
        $current_supermarket = findBySlugInArray($supermarket_slug, $supermarkets);

        $current_tags = [];
        if($tag_slugs) {
          if(!is_array($tag_slugs)) $tag_slugs = [$tag_slugs];
          foreach ($tag_slugs as $tag_slug) {
            $current_tags[] = findBySlugInArray($tag_slug, $tags);
          }
        }

        $number_of_products = $this->model->count(
            $current_category['id'], $current_supermarket['id'], $current_tags, 1, $author_id, $search_term, $favourites_user_id
        )['numberOfProducts'];

        $number_of_pages = ceil($number_of_products / 20);
        $start = ($current_page - 1 ) * 20;

        $this->data['params'] = $params;
        $this->data['supermarkets'] = $supermarkets;
        $this->data['categories'] = $categories;
        $this->data['tags'] = $tags;
        $this->data['current_supermarket'] = $current_supermarket;
        $this->data['current_category'] = $current_category;
        $this->data['current_tags'] = $current_tags;
        $this->data['number_of_pages'] = $number_of_pages;
        $this->data['current_page'] = $current_page;
        $this->data['products'] = $this->model->getProducts($category_slug, $supermarket_slug, $tag_slugs, $start, $visibility, $author_id, $search_term, $favourites_user_id);
    }


    /*
    *       24.4.2017
    *       AJAX function Favourites
    *       adding and deleting
    */

    public function favourites()
    {
        if (!$_POST) return;

        $action = $_POST['action'];

        if ($action == 1) {

            $user_id = Session::get('user_id');
            $product_id = $_POST['product_id'];


            $this->model->addToFavourites($product_id, $user_id);

            echo 'added';  return;

        } else {
            $id = $_POST['id'];

            $this->model->removeFromFavourites($id);

            echo 'removed'; return;
        }

        echo 'error';
    }

}
