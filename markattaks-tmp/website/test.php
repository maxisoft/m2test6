<?php
$common = require_once 'Common.php';

try {
    header('Content-Type: text/plain');
    foreach  ($common->db()->query("SELECT 1 as res") as $row) {
        echo $row['res'];
    }
} catch (Exception $e) {
    echo $e->getMessage();
}