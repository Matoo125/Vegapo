<?php

namespace app\model;

use app\core\Model;
use m4\m4mvc\helper\Session;

class Edit extends Model
{
    // create new arbitrary edit from $data params
    public function newEdit($data)
    {
      return $this->save(
        "insert into edits(type, user_id, comment, diff, country, object_type, object_id) values (:type, :user_id, :comment, :diff, :country, :object_type, :object_id)",
        [":type" => $data['type'],
        ":user_id" => Session::get('user_id'),
        ":comment" => isset($data['comment']) ? $data['comment'] : null,
        ":diff" => isset($data['diff']) ? $data['diff'] : null,
        ":country"  => COUNTRY_CODE,
        ":object_type" => $data['object_type'],
        ":object_id" => isset($data['object_id']) ? $data['object_id'] : null]);
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
        "select * from edits where state = ifnull(:edit_state, state) and object_type = :object_type and ifnull(object_id,-1) = ifnull(:object_id, -1)"
        ,[":object_type" => $object_type, ":object_id" => $object_id, ":edit_state" => $edit_state]);
    }

    // return current user edits for concrete object
    public function getUserEditsByObject($object_type, $object_id = null, $edit_state = null)
    {
      return $this->fetchAll(
        "select * from edits where state = ifnull(:edit_state, state) and user_id = :user_id and object_type = :object_type and ifnull(object_id,-1) = ifnull(:object_id, -1)"
        ,[":user_id" => Session::get('user_id'), ":object_type" => $object_type, ":object_id" => $object_id, ":edit_state" => $edit_state]);
    }

    public function getEdits($state = null)
    {
      return $this->fetchAll("select * from edit_details where edit_state = ifnull(:state, edit_state) order by edit_id desc", [":state" => $state]);
    }

    public function getEditById($id)
    {
      return $this->fetch("select * from edits where id = :id limit 1", [":id" => $id]);
    }

    public function getEditDetailsById($id)
    {
      return $this->fetch("select * from edit_details where edit_id = :id limit 1", [":id" => $id]);
    }
}
