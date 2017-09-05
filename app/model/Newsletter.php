<?php

namespace app\model;

use app\core\Model;

class Newsletter extends Model 
{

    public static function findByEmail ($email)
    {
        $sql = "SELECT * FROM newsletter WHERE email = :email LIMIT 1";
        return self::runQuery($sql, array("email" => $email), "get1");
    }

    public static function insert ($email)
    {
        if (self::findByEmail($email)) return false;

        $sql = "INSERT INTO newsletter (email, country) VALUES (:email, :country)";
        return self::runQuery($sql, array("email" => $email, "country" => COUNTRY_CODE), "post");
    }

    public static function remove ($email, $id = null)
    {
       $sql = "DELETE FROM newsletter WHERE email = :email ";
       $params['email'] = $email;
       if ($id) {
        $sql .= " and id = :id";
        $params['id'] = $id;
       } 

       return self::runQuery($sql, $params, "post"); 
    }

    public static function notReceived ($newsletter)
    {
        $sql = "SELECT id, email, country FROM newsletter n WHERE not exists ";
        $sql .= "(SELECT 1 FROM newsletter_history nh WHERE nh.email = n.email AND nh.newsletter = :newsletter)";
        return self::runQuery($sql, ['newsletter' => $newsletter], 'get');
    }

    public static function addToHistory ($newsletter, $email)
    {
        $sql = "INSERT INTO newsletter_history (newsletter, email) VALUES (:newsletter, :email)";
        return self::runQuery($sql, ['newsletter' => $newsletter, 'email' => $email], 'post');
    }

    public static function history ($newsletter)
    {
        $sql = "SELECT email FROM newsletter_history WHERE newsletter = :newsletter";
        return self::runQuery($sql, ['newsletter' => $newsletter], 'get');
    }


}