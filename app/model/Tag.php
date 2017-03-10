<?php

class Tag extends Model
{
    private $db;
    public static $table = "tags";

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function insert($name, $image) {
        $sql = "INSERT INTO tags(name, slug, image, country) VALUES(:name, :slug, :image, :country)";
        $array = array(":name" => $name, ":slug" => slugify($name), ":image" => $image, ":country" => COUNTRY_CODE);
        return $this->runQuery($sql, $array, "post");
    }

    public function update($name, $image, $id) {
        $sql = "UPDATE tags SET name   = :name, slug   = :slug, image  = :image WHERE id   = :id";
        $args = array(":name"  =>  $name, ":slug"  =>  slugify($name), ":image" =>  $image, ":id"    =>  $id);
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