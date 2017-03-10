<?php

class Store extends Model
{
    private $db;
    public static $table = "supermarkets";

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function insert($name, $image) {
        $sql = "INSERT INTO supermarkets(name, slug, image, country) VALUES(:name, :slug, :image, :country)";
        $array = array(":name" => $name, ":slug" => slugify($name), ":image" => $image, ":country" => COUNTRY_CODE);
        return $this->runQuery($sql, $array, "post");
    }

    public function update($name, $image, $id) {
        $sql = "UPDATE supermarkets SET name   = :name, slug   = :slug, image  = :image WHERE id   = :id";
        $args = array(":name"  =>  $name, ":slug"  =>  slugify($name), ":image" =>  $image, ":id"    =>  $id);
        return $this->runQuery($sql, $args, "post");
    }

    public function getSupermarketById($id) {
        $sql = "select * from supermarkets WHERE id = :id LIMIT 1";
        return $this->runQuery($sql, array(":id" => $id), "get1");
    }

    public function getSupermarkets() {
        $sql = "select * from supermarkets WHERE country = :country";
        $args = array(':country'  => COUNTRY_CODE);
        return $this->runQuery($sql, $args, "get");
    }

}