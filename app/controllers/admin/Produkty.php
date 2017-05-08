<?php 

namespace app\controllers\admin;

use app\controllers\api\Produkty as ProduktyApiController;
use app\core\Session;
use app\helper\Image;

class Produkty extends ProduktyApiController
{

	public function upravit($id) 
    {
        if ($_POST) {
            $data['name'] = $_POST['productName'];
            $data['barcode'] = $_POST['barcode'];
            $data['category_id'] = $_POST['selectCategory'];
            $images['1'] = $_FILES['file'];
            $images['2'] = $_FILES['file2'];
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
            $deleted_tags = array_diff($tags_old, $tags_new);

            $this->model->update($data);
            $this->model->matching_supermarkets($id, $added_supermarkets, $deleted_supermarkets);
            $this->model->matching_tags($id, $added_tags, $deleted_tags);


            // editing main image
            if ($images['1'] && $images['1']['error'] === 0) {

                $images['1'] = Image::upload($images['1'], "products");
                Image::delete($_POST['image_old']);
                $this->model->deleteImages(null, $data['id'], 1);
                $this->model->insertImage($id, 1, $images['1']);

            } 

            // editing image of ingredients2
            if ($images['2'] && $images['2']['error'] === 0) {
                $images['2'] = Image::upload($images['2'], 'products');
                Image::delete($_POST['image2_old']);
                $this->model->deleteImages(null, $data['id'], 2);
                $this->model->insertImage($id, 2, $images['2']);
            }

            Session::setFlash(getString('PRODUCT_UPDATE_SUCCESS'), "success");
            
        }

        // get product by id, supermarkets and categories
        $this->data['product'] = $this->model->getProductById($id);
        $this->data['categories'] = $this->model->getCategories();
        $this->data['supermarkets'] = $this->model->getSupermarkets();
        $this->data['tags'] = $this->model->getTags();

    }

    public function ziadosti($action = null, $id = null) {
        if( isset($action) && isset($id) ) {
            if ($action == "accept") {
                 $this->model->setVisibility(1, $id);
            } elseif ($action == "deny") {
                 $this->model->setVisibility(3, $id);
            }
            redirect("/admin/produkty/ziadosti");
        } 

        $products = $this->model->getProducts(null, null, null, 0, 2);

        $this->data['products'] = $products;
    }

    public function trash($action = null, $id = null, $image = null) {

        if (!check_user_premission(30)) redirect('/'); // admin at least

        if( isset($action) && isset($id) ) {
            if ($action == "recover") {
                $this->model->setVisibility(2, $id);
            } elseif ($action == "delete") {
                if (!check_user_premission(35)) redirect('/'); // more than admin
                $this->model->delete($id, "id", $image);
            } elseif ($action == "move") {
                $this->model->setVisibility(3, $id);
            }
            redirect("/admin/produkty/trash");
        }

        $this->data['products'] = $this->model->getProducts(null, null, null, 0, 3);
    }


    public function vymazat($id, $image) {

        if ($this->model->delete($id, "id", $image)) {
            Session::setFlash("Produkt vymazany uspesne", 'success', 1);
        } else {
            Session::setFlash("Produkt sa nepodarilo vymazat", 'danger', 1);
        }

        redirect('/admin/produkty');
    }

    public function move_to($product_id, $from, $to)
    {
        /* move from sk to cz or the other way around */

        // change supermarket matchers country code
        $this->model->changeCountryCode('matching_supermarkets', ['product_id' => $product_id], $to);
        // get array of supermarket matchers
        $matchers = $this->model->getMatchers('supermarkets', $product_id);
        if ($matchers) {
            // get new supermarket matcher ids
            foreach ($matchers as $matcher){
                // get value from supermarket id
                $value = $this->model->getValue('supermarkets', $matcher['supermarket_id']);
                $new_id = $this->model->getNewMatchingId('supermarkets', $value['value'], $to);
               // change supermarket matcher id
                if ($new_id) {
                    $this->model->changeMatcherId(
                        'matching_supermarkets', 
                        ['supermarket_id' => $new_id['id']],
                        $matcher['id']
                    );
                } else {
                    $this->model->runQuery(
                        "DELETE FROM matching_supermarkets WHERE id = :id",
                        ['id' => $matcher['id']],
                        'post'
                    );
                }

            }
        }


        // change tags matchers ids
        $this->model->changeCountryCode('matching_tags', ['product_id' => $product_id], $to);
        // get array of supermarket matchers
        $matchers = $this->model->getMatchers('tags', $product_id);
        if ($matchers) {
            // get new supermarket matcher ids
            foreach ($matchers as $matcher){
                // get value from supermarket id
                $value = $this->model->getValue('tags', $matcher['tag_id']);
                $new_id = $this->model->getNewMatchingId('tags', $value['value'], $to);
               // change supermarket matcher id

                $this->model->changeMatcherId(
                    'matching_tags', 
                    ['tag_id' => $new_id['id']],
                    $matcher['id']
                );
            }
        }

        // change category id

        // get current category id
        $category_id = $this->model->runQuery(
            "SELECT category_id FROM products WHERE id=:id",
            ['id' => $product_id],
            'get1'
        )['category_id'];

        // get current category value
        $category_value = $this->model->runQuery(
            "SELECT value FROM categories WHERE id = :id",
            ['id' => $category_id],
            'get1'
        )['value'];

        // get new category id
        $new_category_id = $this->model->runQuery(
            "SELECT id FROM categories WHERE country = :country AND value = :value",
            ['country' => $to, 'value' => $category_value],
            'get1'
        )['id'];

        // set new category id
        $this->model->runQuery(
            "UPDATE products SET category_id = :category_id WHERE id = :product_id",
            ['category_id' => $new_category_id, 'product_id' => $product_id],
            'post'
        );

        // change country in products
        $this->model->runQuery(
            "UPDATE products SET country = :country WHERE id = :product_id",
            ['country' => $to, 'product_id' => $product_id],
            'post'
        );

        // change country in images
        $this->model->runQuery(
            "UPDATE images SET country = :country WHERE product_id = :product_id",
            ['country' => $to, 'product_id' => $product_id],
            'post'
        );

        // change country in image table
        $this->model->changeCountryImage($product_id, $to);
        // get image names
        $images = $this->model->getProductImages($product_id);
        // move images
        if ($images) {
            foreach ($images as $image) {
                if (file_exists(ROOT.UPLOADS.DS.'products'.DS. $from . DS .  $image['filename'])) {
                   rename(
                        ROOT.UPLOADS.DS.'products'.DS. $from . DS .  $image['filename'],
                        ROOT.UPLOADS.DS.'products'.DS. $to . DS . $image['filename']
                    );
                    rename(
                        ROOT.UPLOADS.DS.'products'.DS.$from . DS . '450x450' . DS . $image['filename'],
                        ROOT.UPLOADS.DS.'products'.DS.$to . DS . '450x450' . DS . $image['filename']
                    );
                    rename(
                        ROOT.UPLOADS.DS.'products'.DS.$from . DS . '150x150' . DS . $image['filename'],
                        ROOT.UPLOADS.DS.'products'.DS.$to . DS . '150x150' . DS . $image['filename']
                    );
                }

            } 
        }

        redirect('/admin/produkty/ziadosti');
             
    }
}