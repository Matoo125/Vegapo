<?php 

namespace app\helper;

use app\core\Model;

/* move product from sk to cz or the other way */
class Move extends Model
{

  public function __construct($product_id, $from, $to)
  {
    // change supermarket matchers country code
    $this->changeCountryCode(
      'matching_supermarkets', ['product_id' => $product_id], $to
    );

    // get array of supermarket matchers
    $matchers = $this->getMatchers('supermarkets', $product_id);

    if ($matchers) {
      // get new supermarket matcher ids
      foreach ($matchers as $matcher){
        // get value from supermarket id
        $value = $this->getValue('supermarkets', $matcher['supermarket_id']);
        $new_id = $this->getNewMatchingId('supermarkets', $value['value'], $to);
         // change supermarket matcher id
        if ($new_id) {
          $this->changeMatcherId(
            'matching_supermarkets',
            ['supermarket_id' => $new_id['id']],
            $matcher['id']
          );
        } else {
          $this->save(
            "DELETE FROM matching_supermarkets WHERE id = :id",
            ['id' => $matcher['id']]
          );
        }

      }
    }

    // change tags matchers ids
    $this->changeCountryCode('matching_tags', ['product_id' => $product_id], $to);
    // get array of supermarket matchers
    $matchers = $this->getMatchers('tags', $product_id);
    if ($matchers) {
      // get new supermarket matcher ids
      foreach ($matchers as $matcher){
        // get value from supermarket id
        $value = $this->getValue('tags', $matcher['tag_id']);
        $new_id = $this->getNewMatchingId('tags', $value['value'], $to);
         // change supermarket matcher id

        $this->changeMatcherId(
          'matching_tags',
          ['tag_id' => $new_id['id']],
          $matcher['id']
        );
      }
    }

    /* change category id*/

    // get current category id
    $category_id = $this->fetch(
      "SELECT category_id FROM products WHERE id=:id",
      ['id' => $product_id]
    )['category_id'];

    // get current category value
    $category_value = $this->fetch(
      "SELECT value FROM categories WHERE id = :id",
      ['id' => $category_id]
    )['value'];

    // get new category id
    $new_category_id = $this->fetch(
      "SELECT id FROM categories WHERE country = :country AND value = :value",
      ['country' => $to, 'value' => $category_value]
    )['id'];

    // set new category id
    $this->save(
      "UPDATE products SET category_id = :category_id WHERE id = :product_id",
      ['category_id' => $new_category_id, 'product_id' => $product_id]
    );

    // change country in products
    $this->save(
      "UPDATE products SET country = :country WHERE id = :product_id",
      ['country' => $to, 'product_id' => $product_id]
    );

    // change country in images
    $this->save(
      "UPDATE images SET country = :country WHERE product_id = :product_id",
      ['country' => $to, 'product_id' => $product_id]
    );

    // change country in image table
    $this->changeCountryImage($product_id, $to);
    // get image names
    $images = $this->getProductImages($product_id);
    // move images
    if ($images) {
      $path = ROOT.UPLOADS.DS.'products'.DS;
      foreach ($images as $image) {
        if (file_exists($path . $from . DS .  $image['filename'])) {
           rename(
            $path . $from . DS .  $image['filename'],
            $path . $to . DS . $image['filename']
          );
          rename(
            $path . $from . DS . '450x450' . DS . $image['filename'],
            $path . $to . DS . '450x450' . DS . $image['filename']
          );
          rename(
            $path . $from . DS . '150x150' . DS . $image['filename'],
            $path . $to . DS . '150x150' . DS . $image['filename']
          );
        }

      }
    }

    redirect('/admin/produkty/ziadosti');

  }


  public function changeCountry($product_id, $country_code)
  {
    return $this->save(
      "UPDATE products SET country = :country where id = :id",
      ['country' => $country_code, 'id' => $product_id]
    );
  }

  public function changeCountryImage($product_id, $country_code)
  {
    return $this->save(
      "UPDATE images SET country = :country where product_id = :id",
      ['country' => $country_code, 'id' => $product_id]
    );
  }

  public function changeCountryCode($table, $id, $cc)
  {
    return $this->save(
      "UPDATE ".$table." SET country = :country where ".key($id)." = :id",
      ['country' => $cc, 'id' => $id[key($id)]]
    );
  }

  public function getMatchers($table, $product_id)
  {
    return $this->fetchAll(
      "SELECT * FROM matching_".$table." WHERE product_id = :product_id", 
      ['product_id' => $product_id]
    );
  }

  public function changeMatcherId($table,array $change, $id)
  {
    return $this->save(
      "UPDATE ".$table." SET ".key($change)." = :matcher where id = :id",
      ['matcher' => $change[key($change)], 'id' => $id]
    );
  }

  public function getValue($table, $id)
  {
    return $this->fetch(
      "SELECT value FROM ".$table." WHERE id = :id",
      ['id' => $id]
    );
  }

  public function getNewMatchingId($table, $value, $country)
  {
    return $this->fetch(
      "SELECT id FROM ".$table." WHERE value = :value AND country = :country",
      ['value' => $value, 'country' => $country]
    );
  }

  public function getProductImages($product_id)
  {
    return $this->fetchAll(
      "SELECT * FROM images WHERE product_id = :product_id", 
      ['product_id' => $product_id]
    );
  }
}