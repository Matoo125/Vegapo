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

    public function updatePassword($password) {
        $stmt = $this->db->prepare("UPDATE users SET password = :password, updated_at = now() WHERE user_id = :id");
        $stmt->execute(array(
            ':password' => $password,
            ':id'       => Session::get('user_id')
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

}