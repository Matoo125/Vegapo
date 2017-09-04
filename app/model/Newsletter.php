<?php

namespace app\model;

use app\core\Model;

class Newsletter extends Model 
{

    public static function findByEmail($email)
    {
        $sql = "SELECT * FROM newsletter WHERE email = :email LIMIT 1";
        return self::runQuery($sql, array("email" => $email), "get1");
    }

    public static function insert($email)
    {
        if (self::findByEmail($email)) return false;

        $sql = "INSERT INTO newsletter (email, country) VALUES (:email, :country)";
        return self::runQuery($sql, array("email" => $email, "country" => COUNTRY_CODE), "post");
    }

    public static function remove($email)
    {
       $sql = "DELETE FROM newsletter WHERE email = :email LIMIT 1";
       return self::runQuery($sql, array("email" => $email), "post"); 
    }

}