<?php

namespace app\model;

use app\core\Model;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Str;

class Tag extends Model
{
    public static $table = "tags";

    public function insert($data) {
        $sql = "INSERT INTO tags(name, slug, image, country, note, description) 
                VALUES(:name, :slug, :image, :country, :note, :description)";
        $array = array(
            ":name"         => $data['name'], 
            ":slug"         => Str::slugify($data['name']), 
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
            ":slug"  =>  Str::slugify($data['name']), 
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

    public function list() {
        $sql = "select * from tags WHERE country = :country";
        $args = array(':country'  => COUNTRY_CODE);
        return $this->fetchAll($sql, $args);
    }

}