<?php

namespace website\db;


trait DBTrait
{
    /**
     * @return \PDO
     */
    public static function db()
    {
        return DBInst::getInstance();
    }
}