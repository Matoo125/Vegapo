<?php

namespace app\model;

use app\core\Model;
use m4\m4mvc\helper\Session;

class Edit extends Model
{

  public function newEdit($data)
  {
    $sql = "INSERT INTO edits
    (type, user_id, comment, diff, country, object_type, object_id)
            VALUES
    (:type, :user_id, :comment, :diff, :country, :object_type, :object_id)";

    $bind = [
      ":type"         =>  $data['type'],
      ":user_id"      =>  Session::get('user_id'),
      ":comment"      =>  $data['comment']      ??    null,
      ":diff"         =>  $data['diff']         ??    null,
      ":country"      =>  COUNTRY_CODE,
      ":object_type"  =>  $data['object_type'],
      ":object_id"    =>  $data['object_id']    ??    null
    ];

    return $this->save($sql, $bind, True);
  }

  // close edit with optional edit comment and diff
  public function closeEdit($edit_id, $comment = null, $diff = null)
  {
    $sql = "UPDATE edits
            SET state = 'closed',
                updated_at = now(),
                COMMENT = :comment,
                diff = :diff
            WHERE id = :edit_id";

    $bind = [
      ":edit_id" => $edit_id,
      ":comment" => $comment,
      ":diff" => $diff
    ];

    return $this->save($sql, $bind);
  }

  public function getEditsByObject($type, $id = null, $state = null)
  {
    $sql = "SELECT *
            FROM edits
            WHERE state = ifnull(:edit_state, state)
              AND object_type = :object_type
              AND ifnull(object_id,-1) = ifnull(:object_id, -1)";
    $bind = [
      ":object_type" => $type,
      ":object_id" => $id,
      ":edit_state" => $state
    ];

    return $this->fetchAll($sql, $bind);
  }

  // return current user edits for concrete object
  public function getUserEditsByObject($type, $id = null, $state = null)
  {
    $sql = "SELECT *
            FROM edits
            WHERE state = ifnull(:edit_state, state)
              AND user_id = :user_id
              AND object_type = :object_type
              AND ifnull(object_id,-1) = ifnull(:object_id, -1)";
    $bind = [
      ":user_id" => Session::get('user_id'),
      ":object_type" => $type,
      ":object_id" => $id,
      ":edit_state" => $state
    ];

    return $this->fetchAll($sql, $bind);
  }

  public function list($limit, $state = null)
  {
    $sql = "SELECT *
            FROM edit_details
            WHERE edit_state = ifnull(:state, edit_state)
            ORDER BY edit_id DESC limit ".$limit;

    return $this->fetchAll($sql, [":state" => $state]);
  }

  public function getById($id)
  {
    return $this->fetch(
      "select * from edits where id = :id limit 1",
      [":id" => $id]
    );
  }

  public function getEditDetailsById($id)
  {
    return $this->fetch(
      "select * from edit_details where edit_id = :id limit 1",
      [":id" => $id]
    );
  }

  // returns all edits for given object
  public function getObjectEdits($type, $id)
  {
    return $this->fetchAll(
      "select * from edit_details where object_type = :type
        and ifnull(object_id,-1) = ifnull(:id,-1) order by edit_id desc",
      [":type" => $type, ":id" => $id]
    );
  }
}
