<?php
/*
 * Product model class
 * extends core model class
 * written by Matej Vrzala
 * created at: 11.1.2017
 * last update: 26.3.2017
 */
namespace app\model;

use app\core\Model;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Str;

class Product extends Model
{
  public static $table = "products";

  public function insert($data, $last_inserted_id = null, $visibility) {

    $sql = "INSERT INTO products(
            name, slug, category_id, expected_price, 
            author_id, visibility, country, barcode, note, type)
            VALUES(:pn, :s, :ci, :ep, :ai, :vi, :c, :b, :n, :t)";
    $binder = [
      ":pn" => $data['name'],
      ":s"  => Str::slugify($data['name']),
      ":ci" => $data['category_id'],
      ":ep" => $data['price'],
      ":ai" => Session::get("user_id"),
      ":vi" => $visibility,
      ":c"  => COUNTRY_CODE,
      ":b"  => $data['barcode'],
      ":n"  => $data['note'],
      ":t"  => $data['type']
    ];

    return $this->save($sql, $binder, $last_inserted_id);

  }

  public function update($data) {

   $sql = "UPDATE products 
           SET name = :name, 
               slug = :slug, 
               barcode = :barcode,
               category_id = :c_id, 
               expected_price = :price, 
               note = :note,
               type = :type
           WHERE id = :id";
   $binder = array(
    "name"      => $data['name'],
    "slug"      => Str::slugify($data['name']),
    "barcode"   => $data['barcode'],
    "c_id"      => $data['category_id'],
    "price"     => $data['price'],
    "id"        => $data['id'],
    "note"      => $data['note'],
    "type"      => $data['type']
  );

   return $this->save($sql, $binder);

  }

  public function setVisibility($action, $id) 
  {
    $sql = "UPDATE products SET visibility = :visibility WHERE id = :id";
    $binder = array("visibility" => $action, "id" => $id);
    $this->save($sql, $binder);
  }


  public function single($type, $value)
  {
      $sql = "SELECT p.id AS id,
             p.name AS name,
             p.slug AS slug,
             p.expected_price AS price,
             p.barcode,
             p.note,
             p.type,
             c.name AS category,
             c.id AS category_id,
             COUNT(fp.id) AS favourites,
             GROUP_CONCAT(DISTINCT i.filename) AS image,
             GROUP_CONCAT(DISTINCT i2.filename) AS image2,
             GROUP_CONCAT(DISTINCT i3.filename) AS image3,
             GROUP_CONCAT(DISTINCT s.name) AS supermarkets,
             GROUP_CONCAT(DISTINCT s.id) AS supermarket_ids,
             GROUP_CONCAT(DISTINCT t.name) AS tags,
             GROUP_CONCAT(DISTINCT t.id) AS tag_ids,
             u.username,
             u.user_id
          FROM products AS p
          INNER JOIN categories AS c ON p.category_id = c.id
          LEFT JOIN images AS i ON i.product_id = p.id AND i.role=1
          LEFT JOIN images AS i2 ON i2.product_id = p.id AND i2.role=2
          LEFT JOIN images AS i3 ON i3.product_id = p.id AND i3.role=3
          LEFT JOIN matching_supermarkets AS ms ON ms.product_id = p.id
          LEFT JOIN supermarkets AS s ON s.id = ms.supermarket_id
          LEFT JOIN matching_tags AS mt ON mt.product_id = p.id
          LEFT JOIN tags AS t ON t.id = mt.tag_id
          LEFT JOIN favourite_products AS fp on p.id = fp.product_id
          LEFT JOIN users AS u ON u.user_id = p.author_id
          ";

    switch ($type) {
      case 'slug':
        $sql .= "WHERE p.slug = :slug";
        $params['slug'] = $value;
        break;
      
      default:
        $sql .= "WHERE p.id = :id";
        $params['id'] = $value;
        break;
    }

    $params['cc'] = COUNTRY_CODE;

    $sql .= " AND p.country = :cc
        GROUP BY p.id LIMIT 1";

    return $this->fetch($sql, $params);
  }

  public function list(array $filters = null)
  {

    $category      =  $filters['category']     ??  null;
    $supermarket   =  $filters['supermarket']  ??  null;
    $tags          =  $filters['tags']         ??  null;
    $start         =  $filters['start']        ??  0;
    $visibility    =  $filters['visibility']   ??  1;
    $author        =  $filters['author']       ??  null;
    $search        =  $filters['search']       ??  null;
    $favourites    =  $filters['favourites']   ??  null;
    $sort          =  $filters['sort']         ??  null;
    $type          =  $filters['type']         ??  null;
    $country       =  $filters['country']      ??  COUNTRY_CODE;

    $selectPlus = "";
    if ($sort AND $sort === 'fav') {
      $selectPlus = ", COUNT(DISTINCT fp.id) favourites";
    }

    $sql = "SELECT p.id AS id,
                   p.name AS name,
                   p.slug AS slug,
                   p.expected_price AS price,
                   c.name AS category,
                   GROUP_CONCAT(DISTINCT i.filename) AS image,
                   GROUP_CONCAT(DISTINCT s.name) AS supermarkets,
                   GROUP_CONCAT(DISTINCT t.name) AS tags
                   {$selectPlus}
            FROM products AS p
            INNER JOIN categories AS c ON p.category_id = c.id
            LEFT JOIN images AS i ON i.product_id = p.id
            AND i.role=1
            LEFT JOIN matching_supermarkets AS ms ON ms.product_id = p.id
            LEFT JOIN supermarkets AS s ON s.id = ms.supermarket_id
            LEFT JOIN matching_tags AS mt ON mt.product_id = p.id
            LEFT JOIN tags AS t ON t.id = mt.tag_id";


    $params['country'] = $country;
    $params['visibility'] = $visibility;

    $where = [
      ' p.country = :country',
      ' p.visibility = :visibility'
    ];

    if ($type) {
      $types = array_filter($type, function ($n) { return $n; });
      if (!$types)  $types = [0];
      $where[] = "p.type IN(".implode(',',array_keys($types)).")";
    }

    if($category){
      $where[] = "c.slug = :category";
      $params['category'] = $category;
    }

    if ($supermarket) {
      $where[] ="s.slug = :supermarket";
      $params['supermarket'] = $supermarket;
    }

    /* shows only selected tag */
    if ($tags) {
      $tags = implode(', ', $tags) ;
      $where[]  ="t.slug IN(:tags)";
      $params['tags'] = $tags;
    }

    if ($author) {
      $where[] = "p.author_id = :author";
      $params['author'] = $author;
    }

    if ($search) {
      $where[] = "p.name LIKE :search";
      $params['search'] = "%".$search."%";
    }


    if ($favourites || $sort === 'fav'){
      $sql .= " LEFT JOIN favourite_products AS fp ON p.id = fp.product_id";
    }

    if ($favourites) {
      $where[] = "fp.user_id = :user_id";
      $params['user_id'] = $favourites;
    }

    $sql = $sql . " WHERE" . implode(" AND ", $where);

    $sql .= " GROUP BY p.id, p.name ";

    switch($sort) {
      case "namea":
        $sql.= "ORDER BY p.name ASC";
        break;
      case "named":
        $sql .= "ORDER BY p.name DESC";
        break;
      case "priced":
        $sql.= "ORDER BY p.expected_price DESC";
        break;
      case "pricea":
        $sql.= "ORDER BY p.expected_price ASC";
        break;
      case "dated":
        $sql.= "ORDER BY p.id DESC";
        break;
      case "datea":
        $sql.= "ORDER BY p.id ASC";
        break;
      case "rand":
        $sql .= "ORDER BY rand()";
        break;
      case "fav":
        $sql .= "ORDER BY favourites DESC";
        break;
      default :
        $sql .= "ORDER BY p.updated_at DESC";
        break;
    }

  //  echo $sql;die;
    $sql = self::paginate($sql, $start, 20);
    return $this->fetchAll($sql, $params);

  }

  public function count($filters)
  {
    $category     =  $filters['category']     ?? null;
    $supermarket  =  $filters['supermarket']  ?? null;
    $tags         =  $filters['tags']         ?? null;
    $visibility   =  $filters['visibility']   ?? 1;
    $author       =  $filters['author']       ?? null;
    $search       =  $filters['search']       ?? null;
    $favourite    =  $filters['favourite']    ?? null;
    $type         =  $filters['type']         ?? null;

    $sql = "SELECT COUNT(p.id) AS numberOfProducts FROM products AS p";

    $where[] = " p.country = :country";
    $args['country'] = COUNTRY_CODE;

    if ($type) {
      $types = array_filter($type, function ($n) { return $n; });
      if (!$types)  return null; 
      $where[] = "p.type IN(".implode(',',array_keys($types)).")";
    }

    if ($visibility) {
       $where[] = " p.visibility = :visibility";
       $args['visibility'] = $visibility;
    }

    if ($author) {
      $where[] = " p.author_id = :author_id";
      $args['author_id'] = $author;
    }

    if ($supermarket) {
      $sql .= " LEFT JOIN matching_supermarkets AS ms ON p.id = ms.product_id";
      $where[] = " ms.supermarket_id = :supermarket_id";
      $args['supermarket_id'] = $supermarket;
    }

    if ($category) {
      $where[] = " category_id = :category_id";
      $args['category_id'] = $category;

    }

    if ($tags) { 
      $sql .= " LEFT JOIN matching_tags AS mt ON p.id = mt.product_id";
      $tags = implode(",", array_column($tags, 'id'));
      $where[]  ="mt.tag_id IN(:ids)";
      $args['ids'] = $tags;
    }

    if ($search) {
      $where[] = " p.name LIKE :searchTerm";
      $args['searchTerm'] = "%".$search."%";
    }

    if ($favourite) {
      $sql .= " INNER JOIN favourite_products fp ON p.id = fp.product_id";
      $where[] = "fp.user_id = :user_id";
      $args['user_id'] = $favourite;
    }

    $sql = $sql . " WHERE" . implode(" AND ", $where);

    return $this->fetch($sql, $args);

  }

  /* this function will be replaced */
  public function insertImage($id, $role, $filename)
  {
    $this->save(
      "INSERT INTO images(product_id, filename, role, country) 
       VALUES(:id, :name, :role, :country)",
      ['id' => $id, 
       'name' => $filename, 
       'role' => $role, 
       'country' => COUNTRY_CODE]
    );
  }

  /* this function will be replaced */
  public function deleteImages($id = null, $product_id = null, $role = null)
  {
    $sql = "DELETE FROM images WHERE country = :country";
    $params["country"] = COUNTRY_CODE;

    if ($id) {
      $sql .= " AND id = :id";
      $params['id'] = $id;
    }

    if ($product_id) {
      $sql .= " AND product_id = :product_id";
      $params["product_id"] = $product_id;
    }

    if ($role) {
      $sql .= " AND role = :role";
      $params['role'] = $role;
    }

    return $this->save($sql, $params);
  }

  public function checkProduct($barcode, $slug)
  {
    $sql = "SELECT slug FROM products WHERE (slug = :slug";
    $args['slug'] = $slug;
    if ($barcode) {
      $sql .= " || barcode = :barcode";
      $args['barcode'] = $barcode;
    }
    $sql .= ") and country = :country";
    $args['country'] = COUNTRY_CODE;
    return $this->fetch($sql, $args);
  }


  /* favourites related methods */
  public function addToFavourites($product_id, $user_id)
  {
    $sql = "INSERT INTO favourite_products (user_id, product_id) 
            VALUES                        (:user_id, :product_id)";

    $bind = ["user_id" => $user_id, "product_id" => $product_id];

    return $this->save($sql, $bind);

  }

  public function removeFromFavourites($id)
  {
    return $this->save(
      "DELETE FROM favourite_products WHERE id = :id", 
      ['id' => $id]
    );
  }

  public function isProductFavourite($product_id, $user_id)
  {
    $sql = "SELECT id 
            FROM favourite_products 
            WHERE product_id = :product_id 
              AND user_id = :user_id";

    $bind = ['product_id' => $product_id, 'user_id' => $user_id];

    $favid = $this->fetch($sql, $bind);

    return $favid['id'] ?? false;

  }

  /* most of the code below this should be in different files */

  public function matching_supermarkets($id, $added, $removed = []) 
  {
    foreach ($removed as $supermarket_id) {
        $sql = "DELETE FROM matching_supermarkets 
                WHERE product_id=:product_id 
                AND supermarket_id=:supermarket_id";

        $bind = array(
          ":product_id"       =>  $id,
          ":supermarket_id"   =>  $supermarket_id
        );

        $this->save($sql, $bind);

      }

    foreach ($added as $supermarket) {
      $sql = "INSERT INTO matching_supermarkets 
                     (product_id, supermarket_id, country) 
              VALUES (:product_id,:supermarket_id,:country)";

      $bind = array(
        ":product_id"       =>  $id,
        ":supermarket_id"   =>  $supermarket,
        ":country"          =>  COUNTRY_CODE
      );

      $this->save($sql, $bind);

    }

      return true;

  }

  public function matching_tags($product_id, $added, $removed = []) 
  {

    foreach ($removed as $tag_id) {
      $sql = "DELETE from matching_tags 
              WHERE product_id = :product_id AND tag_id = :tag_id";
      $bind = array(
        ":product_id"       =>  $product_id,
        ":tag_id"               =>  $tag_id
      );
      $this->save($sql, $bind);

    }

    foreach ($added as $tag_id) {
      $sql = "INSERT INTO matching_tags (product_id, tag_id, country) 
              VALUES (:product_id,:tag_id,:country)";
      $bind = array(
        ":product_id"       =>  $product_id,
        ":tag_id"   =>  $tag_id,
        ":country"         => COUNTRY_CODE
      );
      $this->save($sql, $bind);

    }

    return true;

  }

}
