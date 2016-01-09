<?php
require_once 'autoload.php';

use website\Common;
use \website\model\User;
use \website\utils\Password;
use \website\model\Module;
use \website\model\StudentModuleSubscription;
use \website\model\TeacherModuleSubscription;

$common = Common::getInstance();

$common->db()->exec('DELETE FROM ' . StudentModuleSubscription::tableName());
$common->db()->exec('DELETE FROM ' . TeacherModuleSubscription::tableName());
$common->db()->exec('DELETE FROM ' . User::tableName());
$common->db()->exec('DELETE FROM ' . Module::tableName());

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
$admin->valid = true;
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
$prof->valid = true;
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
$student->valid = true;
$student->save();


$module1 = new Module();
$module1->name = 'math';
$module1->code = 'math00';
$module1->coefficient = 2;
$module1->valid = true;
$module1->save();

$module2 = new Module();
$module2->name = 'gym';
$module2->code = 'gym00';
$module2->coefficient = 1;
$module2->valid = true;
$module2->save();

$studentSub1 = new StudentModuleSubscription();
$studentSub1->user_id = $student->getId();
$studentSub1->module_id = $module1->getId();
$studentSub1->mark = 18;
$studentSub1->save();


$studentSub2 = new StudentModuleSubscription();
$studentSub2->user_id = $student->getId();
$studentSub2->module_id = $module2->getId();
$studentSub2->mark = 2;
$studentSub2->save();