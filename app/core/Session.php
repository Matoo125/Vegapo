<?php

namespace app\core;

class Session {

    protected static $flash_message = null;

    //  1 - cross platform session flash
    // (use if you need acess message after redirect)
    public static function setFlash($message, $style = null, $type = 0) {
        if ($type === 1) {
            self::set("message", $message);
            self::set("style", $style);
        } else {
           self::$flash_message['message'] = $message;
           self::$flash_message['style'] = $style;
        }
    }
    public static function hasFlash(){
        return !is_null(self::$flash_message) || !is_null(self::get("message"));
    }
    public static function flashStyle() {
            echo self::$flash_message['style'] . self::get('style');
            self::delete('style');
    }
    public static function flash() {
        echo self::$flash_message['message'] . self::get('message');
        self::$flash_message = null;
        self::delete('message');
    }
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }
    public static function delete($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    public static function destroy() {
        session_destroy();
    }
}
