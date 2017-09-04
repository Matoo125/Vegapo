<?php

namespace app\model;

use app\core\Model;
use m4\m4mvc\helper\Session;

class Edit extends Model
{
    // create new arbitrary edit from $data params
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
        "insert into edit_changes(edit_id, object_type, object_id) values (:edit_id, :object_type, :object_id)",
        [":edit_id" => $edit_id,
        ":object_type" => $data['object_type'],
        ":object_id" => isset($data['object_id']) ? $data['object_id'] : null ]);
      return $edit_id;
    }

    // create new 'locale' edit
    public function newLocaleEdit()
    {
      $data['type'] = "update";
      $data['object_type'] = "locale";
      return $this->newEdit($data);
    }

    // close edit with optional edit comment and diff
    public function closeEdit($edit_id, $comment = null, $diff = null)
    {
      return $this->save("update edits set state = 'closed', updated_at = now(), comment = :comment, diff = :diff where id = :edit_id", [":edit_id" => $edit_id, ":comment" => $comment, ":diff" => $diff]);
    }

    // return all edits for concrete object
    public function getEditsByObject($object_type, $object_id = null, $edit_state = null)
    {
      return $this->fetchAll(
        "select q.* from edits q, edit_changes w where q.state = ifnull(:edit_state, q.state) and q.id = w.edit_id and w.object_type = :object_type and ifnull(w.object_id,-1) = ifnull(:object_id, -1)"
        ,[":object_type" => $object_type, ":object_id" => $object_id, ":edit_state" => $edit_state]);
    }

    // return current user edits for concrete object
    public function getUserEditsByObject($object_type, $object_id = null, $edit_state = null)
    {
      return $this->fetchAll(
        "select q.* from edits q, edit_changes w where q.state = ifnull(:edit_state, q.state) and q.user_id = :user_id and q.id = w.edit_id and w.object_type = :object_type and ifnull(w.object_id,-1) = ifnull(:object_id, -1)"
        ,[":user_id" => Session::get('user_id'), ":object_type" => $object_type, ":object_id" => $object_id, ":edit_state" => $edit_state]);
    }

    public function getEdits($state = null)
    {
      return $this->fetchAll("select q.*, w.object_type from edits q, edit_changes w where q.id = w.edit_id and q.state = ifnull(:state, q.state) order by id desc", [":state" => $state]);
    }

    public function getEditById($id)
    {
      return $this->fetch("select q.*, w.object_type from edits q, edit_changes w where q.id = :id and q.id = w.edit_id limit 1", [":id" => $id]);
    }

    // public function getEditByType($type, $state = null, $user_id = null)
    // {
    //   return $this->fetchAll("select * from edits where type = :type and state = ifnull(:state, state) and user_id = ifnull(:user_id, user_id)",
    //   [":type" => $type,
    //   ":state" => isset($state) ? $state : null,
    //   ":user_id" => isset($user_id) ? $user_id : null
    //   ], "get");
    // }

}
