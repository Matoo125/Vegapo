<?php

namespace app\model;

use app\core\Model;

class Dashboard extends Model {

    private $db;

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function findEmailInNewsletterList($email)
    {
        $sql = "SELECT * FROM newsletter WHERE email = :email AND country = :country LIMIT 1";
        return $this->runQuery($sql, array("email" => $email, "country" => COUNTRY_CODE), "get1");
    }

    public function insertEmailToNewsletter($email)
    {
        $sql = "INSERT INTO newsletter (email, country) VALUES (:email, :country)";
        return $this->runQuery($sql, array("email" => $email, "country" => COUNTRY_CODE), "post");
    }


}