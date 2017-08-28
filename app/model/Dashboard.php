<?php

namespace app\model;

use app\core\Model;

class Dashboard extends Model {

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

    public function insertMessage($data)
    {

        $sql = "SELECT * FROM `contact` WHERE author = :author and subject = :subject and email = :email and message = :message";
        if ($this->runQuery($sql, $data, 'get')) {
            echo 'this is duplicate';exit;
        }

        $sql = "INSERT INTO `contact` (`author`, `email`, `subject`, `message`, `country`, `state`) VALUES (:author, :email, :subject, :message, :country, :state)";
        $params = [
            "author" => $data['author'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'message' => $data['message'],
            'country' => COUNTRY_CODE,
            'state' => 'new'
        ];

        return $this->runQuery($sql, $params, 'post');
    }


}