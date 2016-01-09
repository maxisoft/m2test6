<?php

namespace website\model\tests\units {
    require_once __DIR__ .'/../../tool/DB.php';
    require_once __DIR__ . '/User.php';
    use atoum;
    use PDO;
    use website\db\BaseObject;
    use website\db\DBTrait;
    use website\model\Notification;
    use website\tests\tool\DB;



    /**
     * @engine inline
     */
    class StudentModuleSubscription extends atoum
    {
        use DBTrait;
        const DELETE_ALL_TABLE_CONTENT = 'DELETE FROM `STUDENT_MODULE_SUBSCRIPTION`';
        private static $code = 0;

        public function setUp()
        {
            DB::init();
            $this->cleanDB();
        }

        public function tearDown()
        {
            DB::end();
        }

        private static function cleanDB()
        {
            self::db()->exec(self::DELETE_ALL_TABLE_CONTENT);
        }

        private static function allSqlPropertiesUndef($instance)
        {
            $properties = get_class_vars(get_class($instance));
            foreach ($properties as $name => $value) {
                if (BaseObject::isSqlPropertyMapping($name)) {
                    if(!$instance->isUndef($value)){
                        return false;
                    }
                }
            }
            return true;
        }

        private static function allSqlPropertiesNotUndef($instance)
        {
            $properties = get_class_vars(get_class($instance));
            foreach ($properties as $name => $value) {
                if (BaseObject::isSqlPropertyMapping($name)) {
                    if($instance->isUndef($value)){
                        return false;
                    }
                }
            }
            return true;
        }

        public function testConstruct()
        {
            //assert that all properties are undef
            $this
            ->given($this->newTestedInstance())
            ->then
                ->boolean($this->allSqlPropertiesUndef($this->testedInstance))
                    ->isTrue()
            ;
        }


        public function testMapping()
        {
           //TODO
        }

        public function testMarkOK()
        {
            $marks = range(0, 20);
            $marks[] = 10.5;
            $marks[] = 0.001;

            $module = $this->newModule();
            $student = $this->newStudent();
            $module->save();
            $student->save();

            foreach ($marks as $mark) {
                $this
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->module_id = $module->getId())
                    ->if($this->testedInstance->user_id = $student->getId())
                    ->if($this->testedInstance->mark = $mark)
                ->then
                    ->boolean($this->testedInstance->save())
                        ->isTrue()
                ;
                self::cleanDB();
            }
        }

        public function testMarkFail()
        {
            $marks = [-1, -0.1, 21, 20.001];

            $module = $this->newModule();
            $student = $this->newStudent();
            $module->save();
            $student->save();

            foreach ($marks as $mark) {
                $this
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->module_id = $module->getId())
                    ->if($this->testedInstance->user_id = $student->getId())
                    ->if($this->testedInstance->mark = $mark)
                ->then
                    ->exception(function(){$this->testedInstance->save();})
                        ->isInstanceOf('RangeException')
                ;
            }
        }

        public function testDefaultValue()
        {
            $module = $this->newModule();
            $student = $this->newStudent();
            $module->save();
            $student->save();

            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance->module_id = $module->getId())
                ->if($this->testedInstance->user_id = $student->getId())
            ->then
                ->boolean($this->testedInstance->save())
                    ->isTrue()
                ->boolean($this->testedInstance->hasChanges())
                    ->isFalse()
                ->given($dbTestedInstCopy = \website\model\StudentModuleSubscription::findOneWhere(
                [   'module_id' => $this->testedInstance->getModuleId(),
                    'user_id' => $this->testedInstance->getUserId()]))
                ->then
                    ->object($dbTestedInstCopy)
                        ->isEqualTo($this->testedInstance)
            ;
        }


        public function testTableName()
        {
            $this
            ->given($tablename = \website\model\StudentModuleSubscription::tableName())
            ->then
                ->string($tablename)
                    ->isEqualTo('STUDENT_MODULE_SUBSCRIPTION');
        }

        private static function newModule()
        {
            $module = new \website\model\Module();
            $module->name = \website\model\tests\units\Module::name();
            $module->code = \website\model\tests\units\Module::code();
            $module->coefficient = 2;
            $module->valid = true;
            return $module;
        }

        private static function newStudent()
        {
            $user = new \website\model\User();
            $user->login = \website\model\tests\units\User::login();
            $user->password = \website\utils\Password::hash("banana", false);
            $user->first_name = 'toto';
            $user->last_name = 'toto';
            $user->date_of_birth = '2000-01-01';
            $user->address = 'nohere';
            $user->phone = '0000';
            $user->role = 'student';
            $user->email = \website\model\tests\units\User::mailAddress();
            $user->valid = true;
            return $user;
        }
    }



}
