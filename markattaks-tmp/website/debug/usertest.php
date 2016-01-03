<?php
require_once '../Common.php';

$common = \website\Common::getInstance();

$user = new \website\model\User();
$user->age = 15;
$user->login = 'test' . rand();
$user->password = \website\utils\Password::hash('5a1v564a1v564a1');
$user->first_name = 'toto';
$user->last_name = 'caca';

var_dump($user->save());
echo '------------------------<br/>';
var_dump($user);
echo '------------------------<br/>';

$user->age = 16;
var_dump($user->save());
echo '------------------------<br/>';
var_dump($user);
echo '------------------------<br/>';

var_dump(\website\model\User::find());
echo '------------------------<br/>';

var_dump(\website\model\User::findOneWhere(['id'], [5]));
var_dump(\website\model\User::findOneWhere(['id' => 5]));
var_dump(\website\model\User::findOneWhere('id = ?', [5]));