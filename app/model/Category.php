<?php

class Category extends Model
{

    private $db;
    public static $table = "categories";

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function insert($categoryName, $categoryParent, $image) {

        $slug = slugify($categoryName);

        $sql = "INSERT INTO categories(name, slug, parent, image, country)
                VALUES(:cn, :s, :cp, :img, :c)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            ":cn"  => $categoryName,
            ":s"   => $slug,
            ":cp"  => $categoryParent,
            ":img" => $image,
            ":c"   => COUNTRY_CODE
        ));

        return $stmt->rowCount() ? true : null;
    }

    public function update($categoryName, $categoryParent, $image, $id) {

        $slug = slugify($categoryName);

        $sql = "UPDATE categories
                SET name   = :cn,
                    slug            = :s,
                    parent = :cp,
                    image  = :img
                WHERE id   = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            ":cn"    =>  $categoryName,
            ":s"     => $slug,
            ":cp"    =>  $categoryParent,
            ":img"   =>  $image,
            ":id"    =>  $id
        ));

        return $stmt->rowCount() ? true : null;

    }

    public function getCategoryById($id) {
        $stmt = $this->db->prepare("select * from categories WHERE id = " . $id . " LIMIT 1");
        $stmt->execute();
        return $stmt->fetch();

    }

    public function getCategories() {
        $stmt = $this->db->prepare("select * from categories WHERE country = :country");
        $stmt->execute(array(
            ":country"  => COUNTRY_CODE
        ));
        if ($results = $stmt->fetchAll()) {
            return $results;
        }

        return null;
    }

}