<?php

namespace app\model;

use app\core\Model;

/* 
 * Created by Matej Vrzala 25.4.2017
 */

class Message extends Model {


    public function getAll()
    {
        $sql = "SELECT * FROM contact WHERE country = :country ORDER BY created_at DESC";
        return $this->runQuery($sql, array("country" => COUNTRY_CODE), "get");
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM contact WHERE id = :id LIMIT 1";
        return $this->runQuery($sql, ['id' => $id], "get1");
    }

    public function getAnswerById($id)
    {
        $sql = 'SELECT answers.message, answers.created_at, users.username 
                FROM answers 
                LEFT JOIN users 
                ON users.user_id = answers.user_id 
                WHERE message_id = :id
                LIMIT 1';

        return $this->runQuery($sql, ['id' => $id], 'get1');
    }

    public function insertAnswer($message_id, $message, $user_id)
    {
        $sql = "INSERT INTO answers(user_id, message, message_id) VALUES(:user_id, :message, :message_id)";
        return $this->runQuery($sql, ['user_id' => $user_id, 'message' => $message, 'message_id' => $message_id], 'post');
    }

    public function toggleMessageAnswer($message_id)
    {
        $sql = "UPDATE `contact` SET type = 1 WHERE id = :id";
        return $this->runQuery($sql, ['id' => $message_id], 'post');
    }

}