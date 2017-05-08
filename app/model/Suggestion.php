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
        $sql = "INSERT INTO `suggestions`(`user_id`, `product_id`, `state`, `type`, `body`, `country`) VALUES (:user_id, :product_id, :state, :type, :body, :country)";
        $args = [
            'user_id'    => $data['author_id'],
            'product_id' => $data['product_id'],
            'state'      => 3,
            'type'       => $data['reason'],
            'body'       =>  $data['body'],
            'country'    => $data['country']
        ];

        return $this->runQuery($sql, $args, "post");
    }

}