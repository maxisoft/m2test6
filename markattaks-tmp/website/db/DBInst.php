<?php

namespace website\db;

use PDO;

class DBInst
{
    private static $instance = null;
    private static $cachedDBName = null;

    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = '3306';
    const DEFAULT_USER = 'm2test6';
    const DEFAULT_PASSWORD = 'm2test6';
    const DEFAULT_DB_NAME = 'm2test6';


    public static function getFirstEnvVar($default)
    {
        foreach (func_get_args() as $param) {
            $var = getenv($param);
            if ($var !== false) {
                return $var;
            }
        }
        return $default;
    }

    public static function getHost()
    {
        return self::getFirstEnvVar(self::DEFAULT_HOST, 'OPENSHIFT_MYSQL_DB_HOST', 'MYSQL_DB_HOST');
    }

    public static function getPort()
    {
        return self::getFirstEnvVar(self::DEFAULT_PORT, 'OPENSHIFT_MYSQL_DB_PORT', 'MYSQL_DB_PORT');
    }

    public static function getUsername()
    {
        return self::getFirstEnvVar(self::DEFAULT_USER, 'OPENSHIFT_MYSQL_DB_USERNAME', 'MYSQL_DB_USERNAME');
    }

    public static function getPassword()
    {
        return self::getFirstEnvVar(self::DEFAULT_PASSWORD, 'OPENSHIFT_MYSQL_DB_PASSWORD', 'MYSQL_DB_PASSWORD');
    }

    public static function getDBName()
    {
        if (is_null(self::$cachedDBName)) {
            self::$cachedDBName = self::getFirstEnvVar(self::DEFAULT_DB_NAME, 'OPENSHIFT_APP_NAME', 'MYSQL_DB_NAME');
        }
        return self::$cachedDBName;
    }

    /**
     * @return PDO
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            $host = self::getHost();
            $dbname = self::getDBName();
            $port = self::getPort();

            $tmp = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", self::getUsername(), self::getPassword());
            $tmp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance = $tmp;
        }

        return self::$instance;
    }

}