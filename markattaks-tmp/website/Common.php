<?php

namespace website;
require_once 'autoload.php';
use website\db\DBTrait;

class Common
{
    use DBTrait;
    private static $instance = null;

    public static function setup()
    {
        ini_set('display_errors', 'On');
        error_reporting(E_ALL | E_STRICT);
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}


Common::setup();