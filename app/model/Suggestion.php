<?php

namespace app\model;

use app\core\Model;

/* 
 * Created by Matej Vrzala 07.5.2017
 */

class Suggestion extends Model {

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

    public function getAll()
    {
      $sql = "SELECT s.id, u.user_id, u.username, s.product_id, s.type, s.body, s.created_at
              FROM suggestions s
              INNER JOIN users u ON u.user_id = s.user_id
              WHERE s.country = :country AND s.state = 3";
      $args = ['country' => COUNTRY_CODE];
      return $this->runQuery($sql, $args, 'get');
    }

    public function getByUser($id)
    {
        $sql = "SELECT * from suggestions where user_id = :id";
        return $this->runQuery($sql, ['id' => $id], 'get');
    }

    public function checkForDuplicate ($data)
    {
        $sql = "SELECT * FROM suggestions WHERE user_id = :author_id and product_id = :product_id and body = :body and type = :reason and country = :country";
        return $this->runQuery($sql, $data, 'get');
    }

    public function updateState($id, $state)
    {
        $sql = "UPDATE `suggestions` SET `state` = :state WHERE id = :id";
        return $this->runQuery($sql, ['id' => $id, 'state' => $state], 'post');
    }


}