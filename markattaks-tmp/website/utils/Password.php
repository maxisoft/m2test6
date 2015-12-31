<?php

namespace website\utils;
include_once 'lib/passwordLib.php';
require_once 'autoload.php';

class Password
{
    const MIN_LEN = 8;
    const MAX_LEN = 254;
    private static $hash_options = array();

    public static function validate($password)
    {
        is_string($password) or self::badPassword("not a string");
        $strlen = strlen($password);
        $strlen >= self::MIN_LEN or self::badPassword("too short");
        $strlen <= self::MAX_LEN or self::badPassword("too long");
        return true;
    }


    public static function hash($password, $validate=true)
    {
        $validate and self::validate($password);
        return \password_hash($password, PASSWORD_DEFAULT, self::$hash_options);
    }

    public static function verify($password, $hash)
    {
        return \password_verify($password, $hash);
    }

    protected static function badPassword($message, $code=0)
    {
        throw new PasswordException($message, $code);
    }
}