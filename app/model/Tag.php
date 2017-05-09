<?php

namespace app\model;

use app\core\Model;
use app\core\Session;

class Tag extends Model
{
    private $db;
    public static $table = "tags";

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function insert($data) {
        $sql = "INSERT INTO tags(name, slug, image, country, note, description) 
                VALUES(:name, :slug, :image, :country, :note, :description)";
        $array = array(
            ":name"         => $data['name'], 
            ":slug"         => slugify($data['name']), 
            ":image"        => $data['image'], 
            ":note"         => $data['note'],
            ":description"  => $data['description'],
            ":country"      => COUNTRY_CODE
        );
        return $this->runQuery($sql, $array, "post");
    }

    public function update($data, $id) {
        $sql = "UPDATE tags 
                SET name = :name, 
                    slug = :slug, 
                    image  = :image,
                    note = :note,
                    description = :description
                WHERE id   = :id";
        $args = array(
            ":name"  =>  $data['name'], 
            ":slug"  =>  slugify($data['name']), 
            ":image" =>  $data['image'], 
            ":id"    =>  $id,
            ":note"  =>  $data['note'],
            ":description" => $data['description']
        );
        return $this->runQuery($sql, $args, "post");
    }

    public function getTagById($id) {
        $sql = "select * from tags WHERE id = :id LIMIT 1";
        return $this->runQuery($sql, array(":id" => $id), "get1");
    }

    public function getTags() {
        $sql = "select * from tags WHERE country = :country";
        $args = array(':country'  => COUNTRY_CODE);
        return $this->runQuery($sql, $args, "get");
    }

}