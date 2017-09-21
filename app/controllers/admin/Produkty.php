<?php

namespace app\controllers\admin;

use app\controllers\api\Produkty as ProduktyApiController;
use m4\m4mvc\helper\Session;
use app\helper\Image;
use app\controllers\api\Users;
use app\model\Edit;
use mrkovec\sdiff\SDiff;

class Produkty extends ProduktyApiController
{

  public function upravit($id, $reason = null, $reason_id = null)
  {
    if ($_POST) {
      $data['name']         = $_POST['productName'];
      $data['barcode']      = $_POST['barcode'];
      $data['category_id']  = $_POST['selectCategory'];
      $images['1']          = $_FILES['file'];
      $images['2']          = $_FILES['file2'];
      $data['price']        = $_POST['productPrice'];
      $data['id']           = $id;
      $data['note']         = $_POST['note'];
      $data['type']         = $_POST['type'];

      // editing supermarkets
      $supermarkets_new = $_POST['supermarket'] ?? array();
      $supermarkets_old = explode(",", $_POST['supermarkets_old']);

      $added_supermarkets = array_diff(
        $supermarkets_new,
        $supermarkets_old
      );

      $deleted_supermarkets = array_diff(
        $supermarkets_old,
        $supermarkets_new
      );

      // editing tags
      $tags_new = $_POST['tag'] ?? array();
      $tags_old = explode(",", $_POST['tags_old']);

      $added_tags = array_diff($tags_new, $tags_old);
      $deleted_tags = array_diff($tags_old, $tags_new);

      // original product data
      $old_product = $this->model->single('id', $id);

      $this->model->update($data);
      $this->model->matching_supermarkets(
        $id, $added_supermarkets, $deleted_supermarkets
      );
      $this->model->matching_tags($id, $added_tags, $deleted_tags);

      // new edit log
      $this->model->createEdit($id, $reason ?? "update", $reason_id,
        SDiff::getObjectDiff($old_product,$this->model->single('id', $id), False),
        $_POST['edit_comment']);

      // editing main image
      if ($images['1'] && $images['1']['error'] === 0) {

        $images['1'] = Image::upload($images['1'], "products");
        Image::delete($_POST['image_old'], 'products');
        $this->model->deleteImages(null, $data['id'], 1);
        $this->model->insertImage($id, 1, $images['1']);

      }

      // editing image of ingredients
      if ($images['2'] && $images['2']['error'] === 0) {
        $images['2'] = Image::upload($images['2'], 'products');
        Image::delete($_POST['image2_old'], 'products');
        $this->model->deleteImages(null, $data['id'], 2);
        $this->model->insertImage($id, 2, $images['2']);
      }

      Session::setFlash(getString('PRODUCT_UPDATE_SUCCESS'), "success");

    }

    // get product by id, supermarkets and categories
    $this->data['product'] = $this->model->single('id', $id);
    $this->listFilters();

    // retrieve past product edits
    if($e = (new Edit())->getObjectEdits("product", $id)) {
      $this->data['last_edit'] = $e[0];
    }

  }

  public function ziadosti($action = null, $id = null) {
    if( isset($action) && isset($id) ) {
      if ($action == "accept") {
         $this->model->setVisibility(1, $id);
      } elseif ($action == "deny") {
         $this->model->setVisibility(3, $id);
      }
      // new edit log
      $this->model->createEdit($id, $action);

      redirect("/admin/produkty/ziadosti");
    }

    $products = $this->model->list(['visibility' => 2]);

    $this->data['products'] = $products;
  }

  public function trash($action = null, $id = null, $image = null)
  {

    if (!Users::check_premission(30)) redirect('/'); // admin at least

    if( isset($action) && isset($id) ) {
      if ($action == "recover") {
        $this->model->setVisibility(2, $id);
      } elseif ($action == "delete") {
        if (!Users::check_premission(35)) redirect('/'); // more than admin
        $this->model->delete($id, "id", $image);
      } elseif ($action == "move") {
        $this->model->setVisibility(3, $id);
      }
      // new edit log
      $this->model->createEdit($id, $action);

      redirect("/admin/produkty/trash");
    }

    $this->data['products'] = $this->model->list(['visibility' => 3]);
  }

  public function rotate()
  {
    Image::rotate($_GET['image']);
    $this->data['result'] = true;
  }

/*
  public function vymazat($id, $image)
  {
    if ($this->model->delete($id, "id", $image)) {
      Session::setFlash("Produkt vymazany uspesne", 'success', 1);
    } else {
      Session::setFlash("Produkt sa nepodarilo vymazat", 'danger', 1);
    }

    redirect('/admin/produkty');
  }
*/
  /* move from sk to cz or the other way around */
  public function move_to($product_id, $from, $to)
  {
    new \app\helper\Move($product_id, $from, $to);
  }
}
