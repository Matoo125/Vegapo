<?php

namespace app\model;

use app\core\Model;

class Dashboard extends Model {

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