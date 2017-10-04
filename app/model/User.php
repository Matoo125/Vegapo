<?php

namespace app\model;

use app\core\Model;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Redirect;

class User extends Model
{

  public function login ($user)
  {
    Session::set('user_id', $user['user_id']);
    Session::set('user_role', $user['role']);
    Session::set('user_country', $user['country']);
    Session::set('username', $user['username']);
    Session::set('avatar', $user['avatar']);

    $this->save(
      "UPDATE users
       SET last_activity = now()
       WHERE user_id = :user_id",
      ['user_id' =>  $user['user_id']]
    );
  }

  public function getAll ()
  {
    return $this->fetchAll("select * from users");
  }

  public function getList ()
  {
    $sql = "SELECT u.user_id, u.username,
                   COUNT(p.id) numberOfProducts, u.country
            FROM users u
            LEFT JOIN products p ON p.author_id = u.user_id
            WHERE p.visibility = 1
            GROUP BY u.user_id
            ORDER BY numberOfProducts DESC
        ";

    return $this->fetchAll($sql);

  }

  public function find ($column, $value, $items = '*')
  {
    $sql = "SELECT {$items} FROM users WHERE {$column} = :{$column} LIMIT 1";
    return $this->fetch($sql, [$column => $value]);
  }

  public function register ($data)
  {
    $sql = "INSERT INTO users
            (username, first_name, last_name,
             email, password, country, role, facebook_id)
            VALUES
            (:username, :first_name, :last_name,
             :email, :password, :country, :role, :facebook_id)";

     $bind = [
       "username"     => $data['username'],
       "first_name"   => $data['first_name'] ?? NULL,
       "last_name"    => $data['last_name'] ?? NULL,
       "email"        => $data['email'],
       "password"     => $data['password'],
       "country"      => COUNTRY_CODE,
       "role"         => 4,
       "facebook_id"  => $data['facebook_id'] ?? NULL
     ];

    $id = $this->save($sql, $bind, true);
    if (!$id) return false;

    // pick random avatar
    $path = ROOT . '/images/avatars/';
    $avatars = array_diff(scandir($path), ['.','..']);
    $avatar_id = array_rand($avatars);
    $avatar = $avatars[$avatar_id];

    copy(
      $path . $avatar,
      ROOT . '/uploads/users/' . $id . '.svg'
    );
    return true;
  }

  public function update ($set, $where)
  {
    if (isset($set['email'])) {
      if (isset($set['newsletter'])) {
        Newsletter::insert($set['email']);
        unset($set['newsletter']);
      } else {
        Newsletter::remove($set['email']);
      }
    }

    $sql = "UPDATE users SET ";

    foreach ($set as $key => $value) {
      $sql .= " {$key} = :{$key},";
    }

    $sql .= ' updated_at = now()' . " WHERE ";

    foreach ($where as $key => $value) {
      $sql .= " {$key} = :{$key} AND";
    }

    $sql = substr($sql, 0, -4);

    return $this->save($sql, array_merge($where, $set));
  }

  public function delete_token ($token)
  {
    list($selector, $authenticator) = explode(':', $token);
    $sql = "DELETE FROM auth_tokens WHERE selector = :selector";
    return $this->save($sql, ['selector' => $selector]);
  }

  public function add_token ($selector, $authenticator, $expire, $user_id)
  {

    $sql = "INSERT INTO auth_tokens (selector, hash, user_id, expires)
            VALUES (:selector, :hash, :user_id, :expires)";

    $bind = [
      'selector'  =>  $selector,
      'hash'     =>  hash('sha256', $authenticator),
      'user_id'   =>  $user_id,
      'expires'   =>  date('Y-m-d\TH:i:s', $expire)
    ];

    return $this->save($sql, $bind);
  }

  public function find_by_token ($token)
  {
    list($selector, $authenticator) = explode(':', $token);
    $sql = "SELECT * FROM auth_tokens WHERE selector = :selector";
    $row = $this->fetch($sql, ['selector' => $selector]);

    if (!$row) return false;

    $hash = hash('sha256', base64_decode($authenticator));
    $valid =  hash_equals($row['hash'], $hash);

    if (!$valid) return false;

    return $this->find('user_id', $row['user_id']);

  }

  public function createEdit($user_id, $reason)
  {
    $edit = new Edit();
    $data['type'] = $reason;
    $data['object_type'] = "user";
    $data['object_id'] = $user_id;
    $edit_id = $edit->newEdit($data);
    $edit->closeEdit($edit_id);
  }

}
