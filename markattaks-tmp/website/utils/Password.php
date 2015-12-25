<?php

namespace website\utils;
include 'autoload.php';

class Password
{
    const MIN_LEN = 8;
    const MAX_LEN = 254;

    public static function validate($password)
    {
        is_string($password) or self::badPassword("not a string");
        $strlen = strlen($password);
        $strlen >= self::MIN_LEN or self::badPassword("too short");
        $strlen <= self::MAX_LEN or self::badPassword("too long");
        return true;
    }

    protected static function badPassword($message)
    {
        throw new PasswordException($message);
    }
}