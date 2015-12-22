<?php

namespace website;
require_once 'autoload.php';
use website\db\DBTrait;

class Common
{
    use DBTrait;

    public static function setup()
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL | E_STRICT);
    }
}

$common = new Common();
$common->setup();
return $common;