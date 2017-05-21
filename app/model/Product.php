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

        $sql = "INSERT INTO products(name, slug, category_id, expected_price, author_id, visibility, country, barcode, note)
                VALUES(:pn, :s, :ci, :ep, :ai, :vi, :c, :b, :n)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            ":pn" => $data['name'],
            ":s"  => slugify($data['name']),
            ":ci" => $data['category_id'],
            ":ep" => $data['price'],
            ":ai" => Session::get("user_id"),
            ":vi" => $visibility,
            ":c"  => COUNTRY_CODE,
            ":b"  => $data['barcode'],
            ":n"  => $data['note']
        ));

        if($last_inserted_id) return $this->db->lastInsertId();

        return $stmt->rowCount() ? true : null;

    }

    public function update($data) {

     $sql = "UPDATE products SET name = :name, slug = :slug, barcode = :barcode ,category_id = :c_id, expected_price = :price, note = :note WHERE id = :id";
     $args = array(
        "name"      => $data['name'],
        "slug"      => slugify($data['name']),
        "barcode"   => $data['barcode'],
        "c_id"      => $data['category_id'],
        "price"     => $data['price'],
        "id"        => $data['id'],
        "note"      => $data['note']
    );

     return $this->runQuery($sql, $args, "post");

    }

    public function setVisibility($action, $id) {
        $sql = "UPDATE products SET visibility = :visibility WHERE id = :id";
        $array = array("visibility" => $action, "id" => $id);
        $this->runQuery($sql, $array, "post");
    }

    public function getProductById($id)
    {

        $sql = "SELECT p.id AS id,
                       p.name AS name,
                       p.expected_price AS price,
                       p.barcode,
                       p.note,
                       c.name AS category_name,
                       c.id AS category_id,
                       GROUP_CONCAT(DISTINCT i.filename) AS image,
                       GROUP_CONCAT(DISTINCT i2.filename) AS image2,
                       GROUP_CONCAT(DISTINCT i3.filename) AS image3,
                       GROUP_CONCAT(DISTINCT s.name) AS supermarket_names,
                       GROUP_CONCAT(DISTINCT s.id) AS supermarket_ids,
                       GROUP_CONCAT(DISTINCT t.name) AS tag_names,
                       GROUP_CONCAT(DISTINCT t.id) AS tag_ids,
                       u.username,
                       u.user_id
                FROM products AS p
                INNER JOIN categories AS c ON p.category_id = c.id
                LEFT JOIN images AS i ON i.product_id = p.id AND i.role = 1
                LEFT JOIN images AS i2 ON i2.product_id = p.id AND i2.role = 2
                LEFT JOIN images AS i3 ON i3.product_id = p.id AND i3.role = 3
                LEFT JOIN matching_supermarkets AS ms ON ms.product_id = p.id
                LEFT JOIN supermarkets AS s ON s.id = ms.supermarket_id
                LEFT JOIN matching_tags AS mt ON mt.product_id = p.id
                LEFT JOIN tags AS t ON t.id = mt.tag_id
                LEFT JOIN users AS u ON u.user_id = p.author_id
                WHERE p.id = :id AND p.country = :cc
";

        return $this->runQuery($sql, array("id" => $id, "cc" => COUNTRY_CODE), "get1");


    }

    public function getProductBySlug($slug)
    {
          $sql = "SELECT p.id AS id,
                         p.name AS name,
                         p.slug AS slug,
                         p.expected_price AS price,
                         p.barcode,
                         p.note,
                         c.name AS category,
                         GROUP_CONCAT(DISTINCT i.filename) AS image,
                         GROUP_CONCAT(DISTINCT i2.filename) AS image2,
                         GROUP_CONCAT(DISTINCT i3.filename) AS image3,
                         GROUP_CONCAT(DISTINCT s.name) AS supermarkets,
                         GROUP_CONCAT(DISTINCT t.name) AS tags,
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
                    LEFT JOIN users AS u ON u.user_id = p.author_id
                    WHERE p.slug = :slug AND p.country = :cc
                    GROUP BY p.id LIMIT 1
";

        return $this->runQuery($sql, array("slug" => $slug, "cc" => COUNTRY_CODE), "get1");
    }

    public function getProducts($category_slug = null, $supermarket_slug = null, $tag_slug = null, $current_page,
                                $visibility = null, $author_id = null, $searchTerm = null, $favourites = null)
    {
        $array = array();
        $array['country'] = COUNTRY_CODE;



       if ($visibility) {
            $array['visibility'] = $visibility;
       } else {
           $array['visibility'] = '1';
       }

        $sql = "SELECT p.id AS id, p.name AS name, p.slug AS slug, p.expected_price AS price, c.name AS category, GROUP_CONCAT(DISTINCT i.filename) AS image, GROUP_CONCAT(DISTINCT s.name) AS supermarkets, GROUP_CONCAT(DISTINCT t.name) AS tags FROM products AS p INNER JOIN categories AS c ON p.category_id = c.id LEFT JOIN images AS i ON i.product_id = p.id AND i.role=1 LEFT JOIN matching_supermarkets AS ms ON ms.product_id = p.id LEFT JOIN supermarkets AS s ON s.id = ms.supermarket_id LEFT JOIN matching_tags AS mt ON mt.product_id = p.id LEFT JOIN tags AS t ON t.id = mt.tag_id";

        // WHERE p.country = :country AND p.visibility = :visibility

        $where = [];
        $where[] = " p.country = :country";
        $where[] = " p.visibility = :visibility";

        if($category_slug){
            $where[] = "c.slug = :category_slug";
            $array['category_slug'] = $category_slug;
        }

        if ($supermarket_slug) {
            $where[] ="s.slug = :supermarket";
            $array['supermarket'] = $supermarket_slug;
        }

        if ($tag_slug) {
            $where[]  ="t.slug = :tag_slug";
            $array['tag_slug'] = $tag_slug;
        }

        if ($author_id) {
           $where[] = "p.author_id = :author_id";
            $array['author_id'] = $author_id;
        }

        if ($searchTerm) {
            $where[] = "p.name LIKE :searchTerm";
            $array['searchTerm'] = "%".$searchTerm."%";
        }

        if ($favourites) {
            $sql .= " INNER JOIN favourite_products AS fp ON p.id = fp.product_id";
            $where[] = "fp.user_id = :user_id";
            $array['user_id'] = $favourites;
        }

        $sql = $sql . " WHERE" . implode(" AND ", $where);

        $sql .= " GROUP BY p.id, p.name ORDER BY p.id DESC";

        $sql = paginate($sql, $current_page, 20);
        return $this->runQuery($sql, $array, "get");

    }

    public function count($category = null, $supermarket = null, $tag = null, $visibility = 1, $author_id = null, $searchTerm = null, $favourites = null)
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

        if ($searchTerm) {
            $where[] = " p.name LIKE :searchTerm";
            $args['searchTerm'] = "%".$searchTerm."%";
        }

        if ($favourites) {
            $sql .= " INNER JOIN favourite_products fp ON p.id = fp.product_id";
            $where[] = "fp.user_id = :user_id";
            $args['user_id'] = $favourites;
        }

        $sql = $sql . " WHERE" . implode(" AND ", $where);

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


    public function addToFavourites($product_id, $user_id)
    {
        return $this->runQuery(
            "INSERT INTO favourite_products (user_id, product_id) VALUES (:user_id, :product_id)",
            ["user_id" => $user_id, "product_id" => $product_id],
            "post"
        );

    }

    public function removeFromFavourites($id)
    {
        return $this->runQuery("DELETE FROM favourite_products WHERE id = :id", ['id' => $id], "post");
    }

    public function isProductFavourite($product_id, $user_id)
    {
        return $this->runQuery("SELECT id FROM favourite_products WHERE product_id = :product_id AND user_id = :user_id", ['product_id' => $product_id, 'user_id' => $user_id], 'get1');
    }

    public function changeCountry($product_id, $country_code)
    {
        return $this->runQuery(
            "UPDATE products SET country = :country where id = :id",
            ['country' => $country_code, 'id' => $product_id],
            'post'
            );
    }

    public function changeCountryImage($product_id, $country_code)
    {
        return $this->runQuery(
            "UPDATE images SET country = :country where product_id = :id",
            ['country' => $country_code, 'id' => $product_id],
            'post'
            );
    }

    public function changeCountryCode($table, $id, $cc)
    {
        return $this->runQuery(
            "UPDATE ".$table." SET country = :country where ".key($id)." = :id",
            ['country' => $cc, 'id' => $id[key($id)]],
            'post'
        );
    }

    public function getMatchers($table, $product_id)
    {
        return $this->runQuery("SELECT * FROM matching_".$table." WHERE product_id = :product_id", ['product_id' => $product_id], 'get');

    }

    public function changeMatcherId($table,array $change, $id)
    {
        return $this->runQuery(
            "UPDATE ".$table." SET ".key($change)." = :matcher where id = :id",
            ['matcher' => $change[key($change)], 'id' => $id],
            'post'
        );
    }

    public function getValue($table, $id)
    {
        return $this->runQuery(
            "SELECT value FROM ".$table." WHERE id = :id",
            ['id' => $id],
            'get1'
        );
    }

    public function getNewMatchingId($table, $value, $country)
    {
        return $this->runQuery(
            "SELECT id FROM ".$table." WHERE value = :value AND country = :country",
            ['value' => $value, 'country' => $country],
            'get1'
        );
    }

    public function getProductImages($product_id)
    {
        return $this->runQuery("SELECT * FROM images WHERE product_id = :product_id", ['product_id' => $product_id], 'get');
    }

    public function getUsername($id)
    {
        $sql = "SELECT username FROM users WHERE user_id = :id";
        return $this->runQuery($sql, ['id' => $id], 'get1')['username'];
    }

    public function checkProduct($barcode, $slug)
    {
        $sql = "SELECT slug FROM products WHERE slug = :slug";
        $args['slug'] = $slug;
        if ($barcode) {
          $sql .= "|| barcode = :barcode";
          $args['barcode'] = $barcode;
        }
        return $this->runQuery($sql, $args,'get1');
    }


}
