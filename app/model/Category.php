<?php

namespace app\model;

use app\core\Model;
use app\core\Session;

class Category extends Model
{

    private $db;
    public static $table = "categories";

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function insert($data) {

        $slug = slugify($data['name']);

        $sql = "INSERT INTO categories(name, slug, parent, image, country, note, description)
                VALUES(:cn, :s, :cp, :img, :c, :n, :d)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            ":cn"  => $data['name'],
            ":s"   => $slug,
            ":cp"  => $data['parent'],
            ":img" => $data['image'],
            ":c"   => COUNTRY_CODE,
            ":n"    =>  $data['note'],
            ":d"    =>  $data['description']
        ));

        return $stmt->rowCount() ? true : null;
    }

    public function update($data, $id) {

        $slug = slugify($data['name']);

        $sql = "UPDATE categories
                SET name   = :cn,
                    slug   = :s,
                    parent = :cp,
                    image  = :img,
                    note = :n,
                    description = :d
                WHERE id   = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            ":cn"    =>  $data['name'],
            ":s"     => $slug,
            ":cp"    =>  $data['parent'],
            ":img"   =>  $data['image'],
            ":id"    =>  $id,
            ":n"    =>  $data['note'],
            ":d"    =>  $data['description']
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