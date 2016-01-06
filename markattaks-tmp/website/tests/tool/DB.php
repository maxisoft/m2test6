<?php

namespace website\tests\tool;


use website\db\DBTrait;

class DB
{
    use DBTrait;
    //private static $done = array();

    public static final function init($dbname='m2test6-utest')
    {
        /*if(!isset(self::$done[$dbname])) {
            putenv("MYSQL_DB_NAME=$dbname");
            $sqlScript = file_get_contents('..' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'db.sql');
            $sqlScript = str_replace('m2test6', $dbname, $sqlScript);
            self::db()->exec($sqlScript);
            self::$done[$dbname] = true;
        }*/
        self::db()->beginTransaction();
    }

    public static final function end($save=false)
    {
        if ($save) {
            self::db()->commit();
        }else{
            self::db()->rollBack();
        }
    }

}