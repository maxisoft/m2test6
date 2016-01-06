<?php

namespace website\tests\tool;


use website\db\DBTrait;

class DB
{
    use DBTrait;

    public static final function init($dbname='m2test6-utest')
    {
        putenv("MYSQL_DB_NAME=$dbname");
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