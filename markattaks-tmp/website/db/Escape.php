<?php

namespace website\db;


class Escape
{
    const SQL_LIKE_ESCAPE_CHAR = '/';

    public static function escapeSQLLike($str) {
        return preg_replace('/(_|%|\/)/i', self::SQL_LIKE_ESCAPE_CHAR . '$1', $str);
    }
}