<?php

namespace app\model;

use app\core\Model;

/* 
 * Created by Matej Vrzala 08.10.2017
 */

class Testimonial extends Model {

    private $db;

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function create ($data)
    {
        $sql = "INSERT INTO `testimonials`(`name`, `message`) VALUES (:name, :message)";
        $args = [
            'name'    => $data['name'],
            'message' => $data['message']
        ];

        return $this->runQuery($sql, $args, "post");
    }

    public function update ($data)
    {
        $sql = "UPDATE testimonials SET `name` = :name, `message` = :message WHERE id = :id";
        $args = [
            'name'      =>  $data['name'],
            'message'   =>  $data['message'],
            'id'        =>  $data['id']
        ];

        return $this->runQuery($sql, $args, 'post');
    }

    public function remove ($id)
    {
        return $this->runQuery("DELETE FROM testimonials WHERE id = :id", ['id' => $id], 'post');
    }

    public function getAll ()
    {
      $sql = "SELECT * FROM testimonials";
      return $this->runQuery($sql, [], 'get');
    }

    public function getById ($id)
    {
        $sql = "SELECT * from testimonials where id = :id";
        return $this->runQuery($sql, ['id' => $id], 'get1');
    }




}