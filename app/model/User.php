<?php

namespace app\model;

use app\core\Model;
use app\core\Session;

class User extends Model
{
    private $db;

    function __construct()
    {
        $this->db = static::getDB();
    }

    public function getAll() {
        $stmt = $this->db->prepare("select * from users");
        $stmt->execute();
        if ($results = $stmt->fetchAll()) {
            return $results;
        }
        return null;
    }

    public function getByEmail($email) {
        $stmt = $this->db->prepare("select * from users where email = :email LIMIT 1");
        $stmt->execute(array(':email' => $email));
        if ($results = $stmt->fetch()) {
            return $results;
        }
        return null;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("select * from users where user_id = :id LIMIT 1");
        $stmt->execute(array(':id' => $id));
        if ($results = $stmt->fetch()) {
            return $results;
        }
        return null;
    }

    public function register($data) {
        $sql = "INSERT INTO users (username, email, password, country, role) VALUES(:username,:email,:password,:country,:role)";
        $params = array("username" => $data['username'], "email" => $data['email'], "password" => $data['password'], "country" => COUNTRY_CODE, "role" => 4 );
        return $this->runQuery($sql, $params, "post");
    }

    public function getUserPassword(){
        $stmt = $this->db->prepare("select password from users where user_id = :id LIMIT 1");
        $stmt->execute(array(':id' => Session::get('user_id')));
        if ($results = $stmt->fetch()) {
            return $results;
        }
        return null;
    }

    public function updatePassword($password, $session = null) {

        if (!$session) {
            Session::get('user_id');
        }
        $stmt = $this->db->prepare("UPDATE users SET password = :password, updated_at = now() WHERE user_id = :id");
        $stmt->execute(array(
            ':password' => $password,
            ':id'       => $session
        ));
        return $stmt->rowCount() ? true : null;
    }

    public function update($data) {

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
        $sql = "SELECT u.user_id, u.username, COUNT(p.id) numberOfProducts, u.country from users u 
                LEFT JOIN products p ON p.author_id = u.user_id
                WHERE p.visibility = 1
                GROUP BY u.user_id
                ORDER BY numberOfProducts DESC
                ";
        return $this->runQuery($sql, [], 'get');

    }

}