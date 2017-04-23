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
use app\core\Session;

class Product extends Model
{
    private $db;
    public static $table = "products";

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function insert($data, $last_inserted_id = null, $visibility) {

        $sql = "INSERT INTO products(name, slug, category_id, expected_price, author_id, visibility, country, barcode)
                VALUES(:pn, :s, :ci, :ep, :ai, :vi, :c, :b)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            ":pn" => $data['name'],
            ":s"  => slugify($data['name']),
            ":ci" => $data['category_id'],
            ":ep" => $data['price'],
            ":ai" => Session::get("user_id"),
            ":vi" => $visibility,
            ":c"  => COUNTRY_CODE,
            ":b"  => $data['barcode']
        ));

        if($last_inserted_id) return $this->db->lastInsertId();

        return $stmt->rowCount() ? true : null;

    }

    public function update($data) {

     $sql = "UPDATE products SET name = :name, slug = :slug, barcode = :barcode ,category_id = :c_id, expected_price = :price WHERE id = :id";
     $args = array("name" => $data['name'],"slug" => slugify($data['name']),"barcode" => $data['barcode'],"c_id" => $data['category_id'],"price" => $data['price'],"id" => $data['id']);

     return $this->runQuery($sql, $args, "post");

    }

    public function setVisibility($action, $id) {
        $sql = "UPDATE products SET visibility = :visibility WHERE id = :id";
        $array = array("visibility" => $action, "id" => $id);
        $this->runQuery($sql, $array, "post");
    }

    public function getProductById($id) 
    {

        $sql = "SELECT p.id AS id, p.name AS name, p.expected_price AS price, p.barcode, c.name AS category_name, c.id AS category_id, GROUP_CONCAT(DISTINCT i.filename) AS image, GROUP_CONCAT(DISTINCT i2.filename) AS image2, GROUP_CONCAT(DISTINCT i3.filename) AS image3, GROUP_CONCAT(DISTINCT s.name) AS supermarket_names, GROUP_CONCAT(DISTINCT s.id) AS supermarket_ids, GROUP_CONCAT(DISTINCT t.name) AS tag_names, GROUP_CONCAT(DISTINCT t.id) AS tag_ids FROM products AS p INNER JOIN categories AS c ON p.category_id = c.id LEFT JOIN images AS i ON i.product_id = p.id AND i.role = 1 LEFT JOIN images AS i2 ON i2.product_id = p.id AND i2.role = 2 LEFT JOIN images AS i3 ON i3.product_id = p.id AND i3.role = 3 LEFT JOIN matching_supermarkets AS ms ON ms.product_id = p.id LEFT JOIN supermarkets AS s ON s.id = ms.supermarket_id LEFT JOIN matching_tags AS mt ON mt.product_id = p.id LEFT JOIN tags AS t ON t.id = mt.tag_id WHERE p.id = :id AND p.country = :cc
";

        return $this->runQuery($sql, array("id" => $id, "cc" => COUNTRY_CODE), "get1");


    }

    public function getProductBySlug($slug)
    {
          $sql = "SELECT p.id AS id, p.name AS name, p.slug AS slug, p.expected_price AS price, c.name AS category, GROUP_CONCAT(DISTINCT i.filename) AS image, GROUP_CONCAT(DISTINCT i2.filename) AS image2, GROUP_CONCAT(DISTINCT i3.filename) AS image3, GROUP_CONCAT(DISTINCT s.name) AS supermarkets, GROUP_CONCAT(DISTINCT t.name) AS tags FROM products AS p INNER JOIN categories AS c ON p.category_id = c.id LEFT JOIN images AS i ON i.product_id = p.id AND i.role=1 LEFT JOIN images AS i2 ON i2.product_id = p.id AND i2.role=2 LEFT JOIN images AS i3 ON i3.product_id = p.id AND i3.role=3 LEFT JOIN matching_supermarkets AS ms ON ms.product_id = p.id LEFT JOIN supermarkets AS s ON s.id = ms.supermarket_id LEFT JOIN matching_tags AS mt ON mt.product_id = p.id LEFT JOIN tags AS t ON t.id = mt.tag_id WHERE p.slug = :slug AND p.country = :cc GROUP BY p.id LIMIT 1
";

        return $this->runQuery($sql, array("slug" => $slug, "cc" => COUNTRY_CODE), "get1");      
    }

    public function getProducts($category_slug = null, $supermarket_slug = null, $tag_slug = null, $current_page, $visibility = null, $author_id = null)
    {
        $array = array();
        $array['country'] = COUNTRY_CODE;
       


       if ($visibility) {
            $array['visibility'] = $visibility;
       } else {
           $array['visibility'] = '1';
       }

        $sql = "SELECT p.id AS id, p.name AS name, p.slug AS slug, p.expected_price AS price, c.name AS category, GROUP_CONCAT(DISTINCT i.filename) AS image, GROUP_CONCAT(DISTINCT s.name) AS supermarkets, GROUP_CONCAT(DISTINCT t.name) AS tags FROM products AS p INNER JOIN categories AS c ON p.category_id = c.id LEFT JOIN images AS i ON i.product_id = p.id AND i.role=1 LEFT JOIN matching_supermarkets AS ms ON ms.product_id = p.id LEFT JOIN supermarkets AS s ON s.id = ms.supermarket_id LEFT JOIN matching_tags AS mt ON mt.product_id = p.id LEFT JOIN tags AS t ON t.id = mt.tag_id WHERE p.country = :country AND p.visibility = :visibility";

        if($category_slug){
            $sql .= " AND c.slug = :category_slug";
            $array['category_slug'] = $category_slug;
        }

        if ($supermarket_slug) {
            $sql .= " AND s.slug = :supermarket";
            $array['supermarket'] = $supermarket_slug;
        }

        if ($tag_slug) {
            $sql .= " AND t.slug = :tag_slug";
            $array['tag_slug'] = $tag_slug;
        }

        if ($author_id) {
            $sql .= " AND p.author_id = :author_id";
            $array['author_id'] = $author_id;
        }

        $sql .= " GROUP BY p.id, p.name ORDER BY p.id DESC";

        $sql = paginate($sql, $current_page, 20);
        return $this->runQuery($sql, $array, "get");

    }

    public function count($category = null, $supermarket = null, $tag = null, $visibility = 1, $author_id = null)
    {
        $sql = "SELECT COUNT(p.id) AS numberOfProducts FROM products AS p";

        $where = [];
        $where[] = " p.country = :country";

        $args = [];
        $args['country'] = COUNTRY_CODE;

        if ($visibility) {
             $where[] = " p.visibility = :visibility"; 
             $args['visibility'] = $visibility;
        }

        if ($author_id) {
            $where[] = " p.author_id = :author_id";
            $args['author_id'] = $author_id;
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

        if ($tag) {
            $sql .= " LEFT JOIN matching_tags AS mt ON p.id = mt.product_id";
            $where[] = " mt.tag_id = :tag_id";
            $args['tag_id'] = $tag;
        }

        $sql = $sql . " WHERE" . implode(" AND", $where);

        //echo $sql; die;

        return $this->runQuery($sql, $args, "get1");


    }

    public function insertImage($id, $role, $filename)
    {
        $this->runQuery(
            "INSERT INTO images(product_id, filename, role, country) VALUES(:id, :name, :role, :country)",
            ['id' => $id, 'name' => $filename, 'role' => $role, 'country' => COUNTRY_CODE],
            'post'
        );
    }

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

        return $this->runQuery($sql, $params, 'post');
    }

    public function getCategories()
    {
        return $this->runQuery("SELECT * FROM categories WHERE country = :country", array("country" => COUNTRY_CODE) , "get");
    }

    public function getSupermarkets()
    {
        return $this->runQuery("SELECT * FROM supermarkets WHERE country = :country", array("country" => COUNTRY_CODE) , "get");
    }

    public function getTags()
    {
        return $this->runQuery("SELECT * FROM tags WHERE country = :country", array("country" => COUNTRY_CODE), "get");
    }
}