<?php

class Dashboard extends Model {

    private $db;

    function __construct()
    {
        $this->db = static::getDB();
    }


}