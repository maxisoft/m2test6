<?php
$common = require_once 'Common.php';
try{
    echo $common->db()->exec("SELECT 1 FROM NOWHERE");
}catch (Exception $e){
    http_response_code(500);
    throw $e;
}

