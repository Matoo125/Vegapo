<?php

namespace app\model;

use app\core\Model;
use m4\m4mvc\helper\Session;
use m4\m4mvc\helper\Redirect;

class User extends Model
{

    public function getAll() 
    {
        return $this->fetchAll("select * from users");
    }

    public function getByEmail($email) 
    {
        $query = "select * from users where email = :email LIMIT 1";
        return $this->save($sql, array(':email' => $email));
    }

    public function getById($id, $columns = '*') 
    {
        $sql = "select ".$columns." from users where user_id = :id LIMIT 1";
        return $this->fetch($sql, [':id' => $id]);
    }

    public function getByFacebookId($id) 
    {
        $stmt = $this->db->prepare("select * from users where facebook_id = :id LIMIT 1");
        $stmt->execute(array(':id' => $id));
        if ($results = $stmt->fetch()) {
            return $results;
        }
        return null;
    }

    public function register($data) 
    {
        $sql = "INSERT INTO users (username, email, password, country, role) VALUES(:username,:email,:password,:country,:role)";
        $params = array("username" => $data['username'], "email" => $data['email'], "password" => $data['password'], "country" => COUNTRY_CODE, "role" => 4 );
        return $this->runQuery($sql, $params, "post");
    }

    // create new user from Facebook data
    public function registerFacebookUser($data)
    {
      $this->save("INSERT INTO users (username, first_name, last_name, email, password, country, role, facebook_id) VALUES (:username, :first_name, :last_name, :email, :password, :country, :role, :facebook_id)",
        ["username" => $data['username'],
         "first_name" => $data['first_name'],
         "last_name" => $data['last_name'],
         "email" => $data['email'],
         "password" => $data['password'],
         "country" => COUNTRY_CODE,
         "role" => 4,
         "facebook_id" =>  $data['facebook_id']]);
    }

    // update users Facebook id
    public function changeFacebookId($userId, $facebookId)
    {
      $this->save("UPDATE `users` SET updated_at = now(), facebook_id = :facebook_id WHERE user_id=:id",
      ["id" => $userId, "facebook_id" => $facebookId]);
    }

    // user session login
    public function loginUser($user)
    {
      Session::set('user_id', $user['user_id']);
      Session::set('user_role', $user['role']);
      Session::set('user_country', $user['country']);
      Session::set('username', $user['username']);
      $this->runQuery("UPDATE `users` SET last_activity = now() WHERE user_id=:id", array("id" => $user['user_id']), "post");
    }

    // user session logout
    public static function logoutUser()
    {
        Session::destroy();
        Redirect::toURL("LOGIN");
    }

    public function getUserPassword()
    {
        $stmt = $this->db->prepare("select password from users where user_id = :id LIMIT 1");
        $stmt->execute(array(':id' => Session::get('user_id')));
        if ($results = $stmt->fetch()) {
            return $results;
        }
        return null;
    }

    public function updatePassword($password, $session = null) 
    {

        if (!$session) {
            // bug?
            $session = Session::get('user_id');
        }
        $stmt = $this->db->prepare("UPDATE users SET password = :password, updated_at = now() WHERE user_id = :id");
        $stmt->execute(array(
            ':password' => $password,
            ':id'       => $session
        ));
        return $stmt->rowCount() ? true : null;
    }

    public function update($data) 
    {
        if (isset($data['newsletter'])) {
            Newsletter::insert($data['email']);
        } else {
            Newsletter::remove($data['email']);
        }

        $sql = "UPDATE users SET email = :email, username = :username, about_me = :about_me, first_name = :first_name, last_name = :last_name, updated_at = now() WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
           ':email'         =>      $data['email'],
           ':username'      =>      $data['username'],
           ':about_me'      =>      $data['about-me'],
           ':first_name'    =>      $data['first-name'],
           ':last_name'     =>      $data['last-name'],
           ':user_id'       =>      Session::get('user_id')
        ));
        return $stmt->rowCount() ? true : null;
    }

    public function changeRole($id, $role)
    {
        $sql = "UPDATE users SET role=:role WHERE user_id=:id LIMIT 1";
        $params = array("role" => $role, "id" => $id);
        return $this->runQuery($sql, $params, "post");
    }

    public function set_forgotten_password_token($user_id, $token)
    {
        $sql = "INSERT INTO forgotten_password (user_id, token) VALUES (:user_id, :token)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            'user_id'   =>      $user_id,
            'token'     =>      $token
        ));
    }

    public function check_forgotten_password_token($user_id, $token)
    {
        // Delete all old tokens
        $sql = "DELETE FROM `forgotten_password` WHERE `created_at` < ADDDATE(NOW(), INTERVAL -1 HOUR)";
        $this->runQuery($sql, [], 'post');

        // check token
        $sql = "SELECT * FROM forgotten_password WHERE user_id = :user_id AND token = :token";
        $params = array(
            'user_id'   =>      $user_id,
            'token'     =>      $token
        );
        return $this->runQuery($sql, $params, 'get1');
    }

    public function delete_forgotten_password_token($user_id, $token)
    {
        $sql = "DELETE FROM forgotten_password WHERE user_id = :user_id AND token = :token";
        $params = ['user_id'=>$user_id, ':token'=>$token];
        return $this->runQuery($sql, $params, 'post');
    }

    public function getList()
    {
        $sql = "SELECT u.user_id, u.username, COUNT(p.id) numberOfProducts, 
                       u.country from users u
                LEFT JOIN products p ON p.author_id = u.user_id
                WHERE p.visibility = 1
                GROUP BY u.user_id
                ORDER BY numberOfProducts DESC
                ";
        return $this->fetchAll($sql);

    }

}
