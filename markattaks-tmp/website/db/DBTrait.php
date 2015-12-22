<?php

namespace website\db;


trait DBTrait
{
    public static function db()
    {
        return DBInst::getInstance();
    }
}