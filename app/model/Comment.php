<?php

namespace app\model;

use app\core\Model;

class Comment extends Model {

  public function insert ($data)
  {
    if ($this->find($data)) return false;

    $sql = $this->query->insert('author_id', 'product_id', 'body')
                       ->into('comments')
                       ->build();
    return $this->save($sql, $data);
  }

  public function find ($data) 
  {
    $where = '';
    foreach ($data as $key => $value) {
      $where .= $key . ' = :' . $key . ' AND ';
    }
    $where = substr($where, 0, -4);


    $sql = $this->query->select()
                       ->from('comments')
                       ->where($where)
                       ->build();

    return $this->fetch($sql, $data);
  }

  public function list ($filters)
  {
    $sql = $this->query
      ->select('c.body, c.created_at, c.id, u.username, u.avatar, c.author_id')
      ->join('left', 'users u', 'c.author_id = u.user_id')
      ->from('comments c');
    
    foreach($filters as $fKey => $fVal) {
      $sql->where($fKey . ' = :' . $fKey);
    }

    $sql->orderby('c.created_at', 'DESC');

    return $this->fetchAll($sql->build(), $filters);

  }


}