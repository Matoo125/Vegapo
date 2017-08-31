<?php

namespace app\core;
use m4\m4mvc\core\Model as FrameworkModel;
use app\controllers\api\Users;

/*
 * Core Model extends Framework Model
 * is extended by other models
 */

abstract class Model extends FrameworkModel
{

    /*
     * runQuery function is static 
     * but I already call it as non static
     * from many places
     * so this fixes it
     */
    public function __call ($name, $arguments)
    {
        if ($name === 'runQuery') {
            call_user_func(self::runQuery, $arguments);
        } 
    }

    public static function runQuery($query, $args, $type) {
        // little compatibility helper
        if (is_string($type)) {
            if($type == "get") {
                $type = 2;
            }
            if ($type == "get1") {
                $type = 1;
            }
            if($type == "post"){
                $type = 3;
            }
        }

        return parent::runQuery($query, $args, $type);
    }

    public static function paginate ($query, $start, $per_page)
    {
      return $query . " LIMIT " . $start . ", " . $per_page;
    }

    /*
     * delete function used with categories / tags / supermarkets
     * but better if not used.
     * @params string   Target to be deleted       
     * @params string   Column name to delete from 
     * @params string   Image name to delete     
     * @return boolean  
     */
    public function delete($target, $column_name = "id", $image = null) {
        if (!Users::check_premission(35)) redirect('');
        $sql = "DELETE FROM " . static::$table . " WHERE " . $column_name ." = :target";
        $stmt = self::getDB()->prepare($sql);
        $stmt->execute(array(
            "target"    =>      $target
        ));

        // delete image if exists
        delete_image($image, static::$table);

        return $stmt->rowCount() ? true : false;
    }

    // count table country specific
    public function countTableCS( $table, $args = array(), $custom = '' ) {

        $db = self::getDB();

        $sql = "SELECT count(*) FROM " . $table . " WHERE country = :country";

        $params = array();
        $params['country'] = COUNTRY_CODE;

        foreach ($args as $key => $value) {
            $sql .= " AND " . $key . "=" . ":" . $key;
            $params[$key] = $value;
        }

        $sql .= $custom;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ? $result[0] : null;
    }

}
