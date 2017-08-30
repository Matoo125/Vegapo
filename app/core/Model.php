<?php

namespace app\core;
use m4\m4mvc\core\Model as FrameworkModel;

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

    /*
     * delete function used with categories / tags / supermarkets
     * but better if not used.
     * @params string   Target to be deleted       
     * @params string   Column name to delete from 
     * @params string   Image name to delete     
     * @return boolean  
     */
    public function delete($target, $column_name = "id", $image = null) {
        if (!check_user_premission(35)) redirect('');
        $sql = "DELETE FROM " . static::$table . " WHERE " . $column_name ." = :target";
        $stmt = self::getDB()->prepare($sql);
        $stmt->execute(array(
            "target"    =>      $target
        ));

        // delete image if exists
       $image_path = ROOT . DS . 'uploads' . DS . static::$table . DS . $image;

        delete_image($image_path);

        return $stmt->rowCount() ? true : false;
    }

    /* this should be removed and replaced with method in Image helper */
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

    /* this should be removed and replaced with method in Image helper */
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

    /* should be moved to Store model */
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

    /* should be moved to Tag model */
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
