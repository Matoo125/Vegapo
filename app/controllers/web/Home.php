<?php 

namespace app\controllers\web;

use app\controllers\api\Home as HomeApiController;

/*
 * Public API Controller
 * extends Home API Controller
 * use Dashboard model
 */
class Home extends HomeApiController
{

  public function index($product_slug = null)
  {
    if (!$product_slug) {
        $testimonial = $this->model('Testimonial');
        $this->data['testimonials'] = $testimonial->getAll();
        return;
    }

    $products = new Produkty();
    $products->produkt($product_slug);
    $this->data = $products->data;
    $this->view = 'produkty/produkt.twig';
    if (!$this->data['product']) {
      $this->view = '404.twig';
    }
  }

}