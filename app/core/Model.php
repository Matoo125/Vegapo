<?php

/*
 * Core Model
 * is extended by other models
 */

abstract class Model
{
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            try {
                $dns = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
                $db = new PDO($dns, DB_USER, DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $db;
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return null;
            }
        } else{
            return $db;
        }

    }

    public function runQuery($query, $args, $type) {
        $stmt = self::getDB()->prepare($query);
        $stmt->execute($args);

        if($type == "get") {
            if ($results = $stmt->fetchAll()) {
                 return $results;
            }    
        }
        if ($type == "get1") {
            if ($result = $stmt->fetch()) {
                return $result;
            }
        }
        if($type == "post"){
            return $stmt->rowCount() ? true : false;
        }
    }

    public function delete($id, $column_name = "id", $image = null) {
        if (!check_user_premission(35)) redirect('');
        $sql = "DELETE FROM " . static::$table . " WHERE " . $column_name ." = :id";
        $stmt = self::getDB()->prepare($sql);
        $stmt->execute(array(
            "id"    =>      $id
        ));
        
        // delete image if exists
       $image_path = ROOT . DS . 'uploads' . DS . static::$table . DS . $image;

        delete_image($image_path);

        return $stmt->rowCount() ? true : false;
    }

    public function uploadImage($image, $folder) {
        $name = rand(100, 1000) . "-" . $image['name'];
        $name = slugifyImage($name);
        $tmp = $image['tmp_name'];
        $location = ROOT . DS . "uploads" . DS . $folder . DS . $name;
        if(move_uploaded_file($tmp, $location)){
            $this->generateThumbnail($location, 150, 150);
            $this->generateThumbnail($location, 450, 450, "450x450");
            return $name;
        } else {
            return false;
        }

    }

    public function generateThumbnail($path, $width, $height, $prefix = "") {
        $info = getimagesize($path);
        $size = array($info[0], $info[1]);

        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($path);
        } elseif ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($path);
        } elseif ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($path);
        } else {
            return false;
        }

        $thumb = imagecreatetruecolor($width, $height);

        $src_aspect = $size[0] / $size[1];
        $thumb_aspect = $width / $height;

        if ($src_aspect < $thumb_aspect) {
            // narrower
            $scale = $width / $size[0];
            $new_size = array($width, $width / $src_aspect);
            $src_pos = array(0, ($size[1] * $scale - $height) / $scale / 2);

        } elseif ($src_aspect > $thumb_aspect) {
            // wider
            $scale = $height / $size[1];
            $new_size = array($height * $src_aspect, $height);
            $src_pos = array(($size[0] * $scale - $width) / $scale / 2, 0);
        } else {
            // same shape
            $new_size = array($width, $height);
            $src_pos = array(0, 0);
        }

        $new_size[0] = max($new_size[0], 1);
        $new_size[1] = max($new_size[1], 1);

        imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]);

        return imagepng($thumb, $path . '-thumb' . $prefix);
    }

    public function countTable( $table, $args = array() ) {

        $db = self::getDB();

        $sql = "SELECT count(*) FROM " . $table . " WHERE country = :country";

        $params = array();
        $params['country'] = COUNTRY_CODE;

        foreach ($args as $key => $value) {
            $sql .= " AND " . $key . "=" . ":" . $key;
            $params[$key] = $value;
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ? $result[0] : null;
    }

    public function matching_supermarkets($id, $added_supermarkets, $deleted_supermarkets = array()) {

        foreach ($deleted_supermarkets as $supermarket_id) {
                $sql = "DELETE from matching_supermarkets WHERE product_id=:product_id AND supermarket_id=:supermarket_id";
                $params = array(
                    ":product_id"       =>  $id,
                    ":supermarket_id"               =>  $supermarket_id
                );
                $this->runQuery($sql, $params, "post");

            }

             foreach ($added_supermarkets as $supermarket) {
                $sql = "INSERT INTO matching_supermarkets (product_id, supermarket_id, country) VALUES (:product_id,:supermarket_id,:country)";
                   $params = array(
                    ":product_id"       =>  $id,
                     ":supermarket_id"   =>  $supermarket,
                     ":country"         => COUNTRY_CODE
                );
                $this->runQuery($sql, $params, "post");

                }

                return true;

    }

    public function matching_tags($product_id, $added_tags, $deleted_tags = array()) {

        foreach ($deleted_tags as $tag_id) {
            $sql = "DELETE from matching_tags WHERE product_id=:product_id AND tag_id=:tag_id";
            $params = array(
                ":product_id"       =>  $product_id,
                ":tag_id"               =>  $tag_id
            );
            $this->runQuery($sql, $params, "post");

        }

        foreach ($added_tags as $tag_id) {
            $sql = "INSERT INTO matching_tags (product_id, tag_id, country) VALUES (:product_id,:tag_id,:country)";
            $params = array(
                ":product_id"       =>  $product_id,
                ":tag_id"   =>  $tag_id,
                ":country"         => COUNTRY_CODE
            );
            $this->runQuery($sql, $params, "post");

        }

        return true;

    }

}