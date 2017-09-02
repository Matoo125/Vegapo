<?php

namespace app\model;

use app\core\Model;
use app\core\Session;

class Edit extends Model
{
    // private $db;
    //
    // function __construct()
    // {
    //   $this->db = static::getDB();
    // }

    public function newEdit($data)
    {
      $edit_id = $this->save(
        "insert into edits(type, user_id, comment, diff, country) values (:type, :user_id, :comment, :diff, :country)",
        [":type" => $data['type'],
        ":user_id" => Session::get('user_id'),
        ":comment" => isset($data['comment']) ? $data['comment'] : null,
        ":diff" => isset($data['diff']) ? $data['diff'] : null,
        ":country"  => COUNTRY_CODE
      ], True);
      $this->save(
        "insert into changes(edit_id, object_type, object_id) values (:edit_id, :object_type, :object_id)",
        [":edit_id" => $edit_id,
        ":object_type" => $data['object_type'],
        ":object_id" => isset($data['object_id']) ? $data['object_id'] : null ]);
      return $edit_id;
    }

    public function newLocaleEdit()
    {
      $data['type'] = "update";
      $data['object_type'] = "locale";
      return $this->newEdit($data);
    }

    public function closeEdit($edit_id, $comment = null, $diff = null)
    {
      return $this->save("update edits set state = 'closed', updated_at = now() where edit_id = :edit_id", [":edit_id" => $edit_id]);
    }

    public function getEditsByObject($object_type, $object_id = null, $edit_state = null)
    {
      return $this->fetchAll(
        "select q.* from edits q, changes w where q.state = isnull(:edit_state, q.state) and q.id = w.edit_id and w.object_type = :object_type and w.object_id = isnull(:object_id, w.object_id)"
        ,[":object_type" => $object_type, ":object_id" => $object_id, ":edit_state" => $edit_state]);
    }
    public function getUserEditsByObject($object_type, $object_id = null, $edit_state = null)
    {
      return $this->fetchAll(
        "select q.* from edits q, changes w where q.state = isnull(:edit_state, q.state) and q.user_id = :user_id and q.id = w.edit_id and w.object_type = :object_type and w.object_id = isnull(:object_id, w.object_id)"
        ,[":user_id" => Session::get('user_id'), ":object_type" => $object_type, ":object_id" => $object_id, ":edit_state" => $edit_state]);
    }

    // public function update($data)
    // {
    //   $sd = $data;
    //   array_walk($sd, function(&$value, $key) {
    //     $value = $key." = :".$key;
    //   });
    //   $sql = "update edits set updated_at = now(), ".implode(", ",$sd)." where edit_id = :edit_id";
    //   return $this->save($sql, $data);
    // }

    public function getEditById($id)
    {
      return $this->save("select * from edits where edit_id = :id limit 1", [":id" => $id]);
    }

    public function getEditByType($type, $state = null, $user_id = null)
    {
      return $this->fetchAll("select * from edits where type = :type and state = ifnull(:state, state) and user_id = ifnull(:user_id, user_id)",
      [":type" => $type,
      ":state" => isset($state) ? $state : null,
      ":user_id" => isset($user_id) ? $user_id : null
      ], "get");
    }

}
