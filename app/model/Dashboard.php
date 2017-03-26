<?php

namespace app\model;

use app\core\Model;

class Dashboard extends Model {

    private $db;

    function __construct()
    {
        $this->db = static::getDB();
    }


}