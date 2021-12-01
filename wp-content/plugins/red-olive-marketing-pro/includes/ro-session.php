<?php
/**
 * Created by PhpStorm.
 * User: adamholsinger
 * Date: 2020-03-16
 * Time: 16:02
 */

class ROSession
{

    private static $session_started = false;

    public static function set($key, $value) {
        self::validateSession();
        $_SESSION[$key] = $value;
    }

    public static function has($key) {
        self::validateSession();
        if (!empty($_SESSION[$key])) {
            return true;
        }

        return false;
    }

    public static function get($key) {
        if (self::has($key)) {
            return $_SESSION[$key];
        }

        return null;
    }

    public static function arrayHas($sessionKey, $arrayValue) {
        self::validateSession();
        if (!self::has($sessionKey)) return false;

        $value = self::get($sessionKey);
        if (!is_array($value)) {
            return false;
        }

        if (!in_array($arrayValue, $value)) {
            return false;
        }

        return true;
    }

    public static function arraySetValue($sessionKey, $arrayValue) {
        self::validateSession();

        $value = self::get($sessionKey);
        if (empty($value)) {
            $value = [$arrayValue];
            self::set($sessionKey, $value);
        } else {
            array_push($value, $arrayValue);
            self::set($sessionKey, $value);
        }

        return null;
    }

    private static function validateSession() {
        if (!self::$session_started) {
            if( php_sapi_name() !== 'cli' ) {
                if( version_compare( phpversion(), '5.4.0', '>=' ) ) {
                    if( session_status() != PHP_SESSION_ACTIVE ) session_start();
                } else {
                    if( session_id() === '' ) session_start();
                }
            }
            self::$session_started = true;
        }
    }
}
