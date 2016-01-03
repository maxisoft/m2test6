<?php
require_once '../Common.php';

$data = null;
if (isset($argc) && $argc > 1) {
    $data = $argv[1];
}
else if(isset($_GET['data'])){
    $data = $_GET['data'];
}

!is_null($data) or die();
echo \website\utils\Password::hash($data, false);