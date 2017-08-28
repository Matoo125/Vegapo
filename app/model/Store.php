<?php

namespace app\model;

use app\core\Model;
use app\core\Session;

class Store extends Model
{
    public static $table = "supermarkets";


    public function insert($data) {
        $sql = "INSERT INTO supermarkets(name, slug, image, country, note, description) 
                VALUES(:name, :slug, :image, :country, :note, :description)";
        $array = array(
            ":name"         => $data['name'], 
            ":slug"         => slugify($data['name']), 
            ":image"        => $data['image'], 
            ":country"      => COUNTRY_CODE,
            ":note"         =>  $data['note'],
            ":description"  =>  $data['description']
        );
        return $this->runQuery($sql, $array, "post");
    }

    public function update($data, $id) {
        $sql = "UPDATE supermarkets 
                SET name        = :name, 
                    slug        = :slug, 
                    image       = :image,
                    note        = :note,
                    description = :description
                WHERE id = :id";
        $args = array(
            ":name"         =>  $data['name'], 
            ":slug"         =>  slugify($data['name']), 
            ":image"        =>  $data['image'], 
            ":id"           =>  $id,
            ":note"         =>  $data['note'],
            ":description"  =>  $data['description']
        );
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