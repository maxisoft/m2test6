<?php
require_once 'autoload.php';

use website\Common;
use \website\model\User;
use \website\utils\Password;

$common = Common::getInstance();

$common->db()->exec('DELETE FROM ' . User::tableName());

$bananaHashed = Password::hash('banana', false);

$admin = new User();
$admin->first_name = "super admin";
$admin->last_name = "nameless";
$admin->login = 'admin';
$admin->password = $bananaHashed;
$admin->role = 'admin';
$admin->save();


$prof = new User();
$prof->first_name = "teneyug";
$prof->last_name = "nameless";
$prof->login = 'teacher';
$prof->password = $bananaHashed;
$prof->role = 'teacher';
$prof->save();

$student = new User();
$student->first_name = "franklin";
$student->last_name = "nameless";
$student->login = 'student';
$student->password = $bananaHashed;
$student->role = 'student';
$student->save();