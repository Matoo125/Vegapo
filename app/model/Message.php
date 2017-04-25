<?php

namespace app\model;

use app\core\Model;

/* 
 * Created by Matej Vrzala 25.4.2017
 */

class Message extends Model {

    private $db;

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM contact WHERE country = :country";
        return $this->runQuery($sql, array("country" => COUNTRY_CODE), "get");
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM contact WHERE id = :id LIMIT 1";
        return $this->runQuery($sql, ['id' => $id], "get1");
    }




}