<?php

spl_autoload_register(function ( $class ) {

    $root = __DIR__;

    if('Test' === substr($class, 0, strpos($class, '\\')))
        $root = dirname(__DIR__);

    $to_include = $root . DIRECTORY_SEPARATOR .
            str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if('Test' === substr($class, 0, strpos($class, '\\'))){
      $tmp = strrchr($to_include, DIRECTORY_SEPARATOR);
      $tmp1 = strripos($to_include, DIRECTORY_SEPARATOR);
      $subtmp = substr($to_include, 0, $tmp1);
      $to_include = $subtmp . DIRECTORY_SEPARATOR . "tests" .
                    DIRECTORY_SEPARATOR . "units" . $tmp;
    }
      
    require $to_include;
});
