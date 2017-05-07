<?php

namespace app\model;

use app\core\Model;

/* 
 * Created by Matej Vrzala 07.5.2017
 */

class Suggestion extends Model {

    private $db;

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function save($data)
    {
        $sql = "INSERT INTO `suggestions`(`user_id`, `product_id`, `state`, `type`, `body`) VALUES (:user_id, :product_id, :state, :type, :body)";
        $args = [
            'user_id' => $data['author_id'],
            'product_id' => $data['product_id'],
            'state' => 3,
            'type' => $data['reason'],
            'body' =>  $data['body']
        ];

        return $this->runQuery($sql, $args, "post");
    }

}