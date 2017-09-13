<?php

namespace app\controllers\api;

use app\core\Controller;
use m4\m4mvc\helper\Session;
use app\helper\Image;
use m4\m4mvc\helper\Str;

class Produkty extends Controller
{
  public function __construct()
  {
    $this->model = $this->model('Product');
  }

  public function produkt($slug = null)
  {
    $this->data['product'] = $this->model->single('slug', $slug);

    if (Session::get('user_id')) {

      $this->data['liked'] = $this->model->isProductFavourite(
        $this->data['product']['id'], Session::get('user_id')
      )[0];
    }
  }

  public function pridat() 
  {

    if (!Session::get('user_id')) {
      redirect('/');
    }

    if ($_POST) {
      $data['name']         = $_POST['productName'];
      $data['category_id']  = $_POST['selectCategory'];
      $images['1']          = $_FILES['file'];
      $images['2']          = $_FILES['ingredients'];
      $data['price']        = $_POST['productPrice'];
      $data['barcode']      = preg_replace('/\s+/','',$_POST['barcode']);
      $supermarkets         = $_POST['supermarket'] ?? array();
      $tags                 = $_POST['tag'] ?? array();
      $data['note']         = $_POST['note'];
      $data['type']         = $_POST['type'];

      if(Session::get('user_role') !== null && Session::get('user_role') > 20) {
        $visibility = 1;
      } else {
        $visibility = 2;
      }

      // check for duplicant
      if ($slug = $this->model->checkProduct(
            $data['barcode'], 
            Str::slugify($data['name']))['slug']
        ) 
      {
        Session::setFlash(
          getString('PRODUCT_ALREADY_EXISTS') . 
                    "<a href='/produkty/produkt/$slug'>link</a>",
          "warning"
        );
        // to avoid name collision
        $_POST['selectedsupermarkets'] = $_POST['supermarket'] ?? [];
        $_POST['selectedtags'] = $_POST['tag'] ?? [];
        $this->data = $_POST;

      } else {

        if ( $id = $this->model->insert($data, true, $visibility) ){
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

    $this->listFilters();

  }

  public function listFilters ()
  {
    $c = new \app\model\Category;
    $s = new \app\model\Store;
    $t = new \app\model\Tag;
    $this->data['categories']   = $c->list();
    $this->data['supermarkets'] = $s->list();
    $this->data['tags']         = $t->list();
  }

  public function index()
  {
    $params['kategoria']    = $_GET['kategoria']    ?? null;
    $params['supermarket']  = $_GET['supermarket']  ?? null;
    $params['tag']          = $_GET['tag']          ?? null;
    $params['oblubene']     = $_GET['oblubene']     ?? null;
    $params['hladat']       = $_GET['hladat']       ?? null;
    $params['autor']        = $_GET['autor']        ?? null;
    $params['stav']         = $_GET['stav']         ?? null;
    $params['sort']         = $_GET['sort']         ?? null;

    if (isset($_GET['type1']) and $_GET['type1'] == 0) { 
      $params['type1'] = 0; 
    }

    if (isset($_GET['type2']) and $_GET['type2'] == 0) { 
      $params['type2'] = 0; 
    }
    if (isset($_GET['type3']) and $_GET['type3'] == 1) { 
      $params['type3'] = 1; 
    }

    $type = [
      1 =>  $params['type1'] ?? 1,
      2 =>  $params['type2'] ?? 1,
      3 =>  $params['type3'] ?? 0
    ];

    if ($params['autor']) {
      $user = new \app\model\User;
      $this->data['username'] = $user->getById(
        $params['autor'], 'username'
      )['username'];
    }
    
    $current_page = $_GET['p'] ?? 1;

    $this->listFilters();

    $current_category = findBySlugInArray(
      $params['kategoria'], 
      $this->data['categories']
    );

    $current_supermarket = findBySlugInArray(
      $params['supermarket'], 
      $this->data['supermarkets']
    );

    $current_tags = [];
    $tag_slugs = $params['tag'];
    if($tag_slugs) {
      if(!is_array($tag_slugs)) $tag_slugs = [$tag_slugs];
      foreach ($tag_slugs as $tag_slug) {
      $current_tags[] = findBySlugInArray($tag_slug, $this->data['tags']);
      }
    }

    $number_of_products = $this->model->count([
        'category'    =>  $current_category['id'],
        'supermarket' =>  $current_supermarket['id'],
        'tags'        =>  $current_tags,
        'author'      =>  $params['autor'],
        'search'      =>  $params['hladat'],
        'favourite'   =>  $params['oblubene'],
        'visibility'  =>  1,
        'type'        =>  $type
      ]
    )['numberOfProducts'];

    $number_of_pages = ceil($number_of_products / 20);
    $start = ($current_page - 1 ) * 20;

    $this->data['params']               = $params;
    $this->data['current_supermarket']  = $current_supermarket;
    $this->data['current_category']     = $current_category;
    $this->data['current_tags']         = $current_tags;
    $this->data['number_of_pages']      = $number_of_pages;
    $this->data['current_page']         = $current_page;
    $this->data['sorting']              = $params['sort'];

    $this->data['products'] = $this->model->list([
      'category'      =>  $params['kategoria'], 
      'supermarket'   =>  $params['supermarket'], 
      'tags'          =>  $tag_slugs, 
      'start'         =>  $start, 
      'type'          =>  $type,
      'visibility'    =>  $params['stav'], 
      'author'        =>  $params['autor'], 
      'search'        =>  $params['hladat'], 
      'favourites'    =>  $params['oblubene'], 
      'sort'          =>  $params['sort']]);
  }

  public function favourites()
  {
    if (!$_POST) return;

    if ($_POST['action'] == 1) {
      $user_id = Session::get('user_id');
      $product_id = $_POST['product_id'];
      $this->model->addToFavourites($product_id, $user_id);
      echo 'added';  return;
    } 
    else {
      $id = $_POST['id'];
      $this->model->removeFromFavourites($id);
      echo 'removed'; return;
    }
  }

}
