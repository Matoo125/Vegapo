<?php

namespace app\model;

use app\core\Model;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Str;

class Category extends Model
{

    public static $table = "categories";


    public function insert($data) {

        $slug = Str::slugify($data['name']);

        $sql = "INSERT INTO categories(name, slug, parent, image, country, note, description)
                VALUES(:cn, :s, :cp, :img, :c, :n, :d)";
        $array = array(
            ":cn"  => $data['name'],
            ":s"   => $slug,
            ":cp"  => $data['parent'],
            ":img" => $data['image'],
            ":c"   => COUNTRY_CODE,
            ":n"    =>  $data['note'],
            ":d"    =>  $data['description']
        );
        return $this->save($sql, $array, True);
    }

    public function update($data, $id) {

        $slug = Str::slugify($data['name']);

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
        $sql = "select * from categories WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => $id]);
    }

    public function list()
    {
        $sql = "select * from categories WHERE country = :country";
        return $this->fetchAll($sql, ['country' => COUNTRY_CODE]);
    }

    public function createEdit($category_id, $reason = null, $diff = null, $comment = null)
    {
      $edit = new Edit();
      $data['type'] = $reason ?? "new";
      $data['object_type'] = "category";
      $data['object_id'] = $category_id;
      $edit_id = $edit->newEdit($data);
      $edit->closeEdit($edit_id, $comment, $diff);
    }

}
