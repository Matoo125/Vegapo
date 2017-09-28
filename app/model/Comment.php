<?php

namespace app\model;

use app\core\Model;

class Comment extends Model {

  public function insert($data)
  {
    $sql = $this->query->insert('author_id', 'product_id', 'body')
                       ->into('comments')
                       ->build();
    $this->save($sql, $data);
  }

  public function list ($filters)
  {
    $sql = $this->query->select('*')
                       ->from('comments');
    
    foreach($filters as $fKey => $fVal) {
      $sql->where($fKey . ' = :' . $fKey);
    }

    return $this->fetchAll($sql->build(), $filters);

  }


}