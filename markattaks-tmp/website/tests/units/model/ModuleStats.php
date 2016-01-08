<?php

namespace website\model\tests\units {
    require_once __DIR__ .'/../../tool/DB.php';
    use atoum;
    use website\db\BaseObject;
    use website\db\DBTrait;
    use website\model\Notification;
    use website\tests\tool\DB;



    /**
     * @engine inline
     */
    class ModuleStats extends atoum
    {
        use DBTrait;

        public function setUp()
        {
            DB::init();
        }

        public function tearDown()
        {
            DB::end();
        }


        public function afterTestMethod($method)
        {
            $this->db()->rollBack();
            if (!$this->db()->inTransaction()){
                $this->db()->beginTransaction();
            }
        }

        public function testStatSimple()
        {
            $objs = self::insertSampleData1();

            $module1_stat = new \website\model\ModuleStats();
            $module1_stat->module_id = $objs['module1']->getId();
            $module1_stat->average = 18;
            $module1_stat->min_mark = 18;
            $module1_stat->max_mark = 18;
            $module1_stat->standard_deviation = 0;
            $module1_stat->student_count = 1;

            $module2_stat = new \website\model\ModuleStats();
            $module2_stat->module_id = $objs['module2']->getId();
            $module2_stat->average = 2;
            $module2_stat->min_mark = 2;
            $module2_stat->max_mark = 2;
            $module2_stat->standard_deviation = 0;
            $module2_stat->student_count = 1;

            $this
            ->given($res = \website\model\ModuleStats::find())
            ->then
                ->array(array_map(function($e){return $e->toArray();}, $res))
                    ->contains($module1_stat->toArray())
                    ->contains($module2_stat->toArray())
                ;
        }

        public function testStat()
        {
            $objs = self::insertSampleData2();

            $module1_stat = new \website\model\ModuleStats();
            $module1_stat->module_id = $objs['module1']->getId();
            $module1_stat->average = (13 + 18) / 2;
            $module1_stat->min_mark = 13;
            $module1_stat->max_mark = 18;
            $module1_stat->standard_deviation = 2.5;
            $module1_stat->student_count = 2;

            $module2_stat = new \website\model\ModuleStats();
            $module2_stat->module_id = $objs['module2']->getId();
            $module2_stat->average = 2;
            $module2_stat->min_mark = 2;
            $module2_stat->max_mark = 2;
            $module2_stat->standard_deviation = 0;
            $module2_stat->student_count = 1;

            $this
            ->given($res = \website\model\ModuleStats::find())
            ->then
                ->array($tm=array_map(function($e){return $e->toArray();}, $res))
                    ->contains($module1_stat->toArray())
                    ->contains($module2_stat->toArray())
            ;
        }

        private static function insertSampleData1()
        {

            $student = new \website\model\User();
            $student->first_name = "franklin";
            $student->last_name = "nameless";
            $student->login = 'student';
            $student->password = 'banana';
            $student->role = 'student';
            $student->address = 'nowhere';
            $student->date_of_birth = '2000-01-01';
            $student->phone = '000';
            $student->email = 'franklin@mail.com';
            $student->save();


            $module1 = new \website\model\Module();
            $module1->name = 'math';
            $module1->code = 'math00';
            $module1->coefficient = 2;
            $module1->save();

            $module2 = new \website\model\Module();
            $module2->name = 'gym';
            $module2->code = 'gym00';
            $module2->coefficient = 1;
            $module2->save();

            $studentSub1 = new \website\model\StudentModuleSubscription();
            $studentSub1->user_id = $student->getId();
            $studentSub1->module_id = $module1->getId();
            $studentSub1->mark = 18;
            $studentSub1->save();


            $studentSub2 = new \website\model\StudentModuleSubscription();
            $studentSub2->user_id = $student->getId();
            $studentSub2->module_id = $module2->getId();
            $studentSub2->mark = 2;
            $studentSub2->save();

            return [
                'student' => $student,
                'module1' => $module1,
                'module2' => $module2,
                'studentSub1' => $studentSub1,
                'studentSub2' => $studentSub2
            ];
        }


        private static function insertSampleData2()
        {

            $student = new \website\model\User();
            $student->first_name = "franklin";
            $student->last_name = "nameless";
            $student->login = 'student';
            $student->password = 'banana';
            $student->role = 'student';
            $student->address = 'nowhere';
            $student->date_of_birth = '2000-01-01';
            $student->phone = '000';
            $student->email = 'franklin@mail.com';
            $student->save();

            $student2 = new \website\model\User();
            $student2->first_name = "franklin2";
            $student2->last_name = "nameless";
            $student2->login = 'student2';
            $student2->password = 'banana';
            $student2->role = 'student';
            $student2->address = 'nowhere';
            $student2->date_of_birth = '2000-01-01';
            $student2->phone = '000';
            $student2->email = 'franklin2@mail.com';
            $student2->save();


            $module1 = new \website\model\Module();
            $module1->name = 'math';
            $module1->code = 'math00';
            $module1->coefficient = 2;
            $module1->save();

            $module2 = new \website\model\Module();
            $module2->name = 'gym';
            $module2->code = 'gym00';
            $module2->coefficient = 1;
            $module2->save();

            $studentSub1 = new \website\model\StudentModuleSubscription();
            $studentSub1->user_id = $student->getId();
            $studentSub1->module_id = $module1->getId();
            $studentSub1->mark = 18;
            $studentSub1->save();


            $studentSub2 = new \website\model\StudentModuleSubscription();
            $studentSub2->user_id = $student->getId();
            $studentSub2->module_id = $module2->getId();
            $studentSub2->mark = 2;
            $studentSub2->save();

            $student2Sub1 = new \website\model\StudentModuleSubscription();
            $student2Sub1->user_id = $student2->getId();
            $student2Sub1->module_id = $module1->getId();
            $student2Sub1->mark = 13;
            $student2Sub1->save();


            $student2Sub2 = new \website\model\StudentModuleSubscription();
            $student2Sub2->user_id = $student2->getId();
            $student2Sub2->module_id = $module2->getId();
            $student2Sub2->mark = null;
            $student2Sub2->save();

            return [
                'student' => $student,
                'student2' => $student2,
                'module1' => $module1,
                'module2' => $module2,
                'studentSub1' => $studentSub1,
                'studentSub2' => $studentSub2,
                'student2Sub1' => $student2Sub1,
                '$student2Sub2' => $student2Sub2
            ];
        }
    }



}
