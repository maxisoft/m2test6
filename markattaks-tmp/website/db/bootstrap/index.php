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
$admin->address = 'nowhere';
$admin->date_of_birth = '2000-01-01';
$admin->phone = '000';
$admin->email = 'admin@mail.com';
$admin->save();

$prof = new User();
$prof->first_name = "teneyug";
$prof->last_name = "nameless";
$prof->login = 'teacher';
$prof->password = $bananaHashed;
$prof->role = 'teacher';
$prof->address = 'nowhere';
$prof->date_of_birth = '2000-01-01';
$prof->phone = '000';
$prof->email = 'teneyug@mail.com';
$prof->save();

$student = new User();
$student->first_name = "franklin";
$student->last_name = "nameless";
$student->login = 'student';
$student->password = $bananaHashed;
$student->role = 'student';
$student->address = 'nowhere';
$student->date_of_birth = '2000-01-01';
$student->phone = '000';
$student->email = 'franklin@mail.com';
$student->save();