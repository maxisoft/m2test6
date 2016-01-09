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
    class TeacherModuleSubscription extends atoum
    {
        use DBTrait;
        const DELETE_ALL_TABLE_CONTENT = 'DELETE FROM `TEACHER_MODULE_SUBSCRIPTION`';
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

        public function testOnlyOneMainTeacherPerModule()
        {

            $mainTeacher = self::newTeacher();
            $mainTeacher->save();

            $otherTeacher = self::newTeacher();
            $otherTeacher->save();

            $module = self::newModule();
            $module->save();

            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance->user_id = $mainTeacher->getId())
                ->if($this->testedInstance->module_id = $module->getId())
                ->if($this->testedInstance->main = true)
            ->then
                ->boolean($this->testedInstance->save())
                    ->isTrue()
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->user_id = $otherTeacher->getId())
                    ->if($this->testedInstance->module_id = $module->getId())
                    ->if($this->testedInstance->main = true)
                ->then
                    ->exception(function(){$this->testedInstance->save();})
                        ->isInstanceOf('PDOException')
                        ->message
                            ->contains('45000')
                            ->contains('There\'s already a main teacher for this module')
            ;
        }


        public function testTableName()
        {
            $this
            ->given($tablename = \website\model\TeacherModuleSubscription::tableName())
            ->then
            ->string($tablename)
            ->isEqualTo('TEACHER_MODULE_SUBSCRIPTION');
        }

        private static function newModule()
        {
            $module = new \website\model\Module();
            $module->name = \website\model\tests\units\Module::name();
            $module->code = \website\model\tests\units\Module::code();
            $module->valid = true;
            $module->coefficient = 2;
            return $module;
        }

        private static function newTeacher()
        {
            $user = new \website\model\User();
            $user->login = \website\model\tests\units\User::login();
            $user->password = \website\utils\Password::hash("banana", false);
            $user->first_name = 'toto';
            $user->last_name = 'toto';
            $user->date_of_birth = '2000-01-01';
            $user->address = 'nohere';
            $user->phone = '0000';
            $user->role = 'teacher';
            $user->email = \website\model\tests\units\User::mailAddress();
            $user->valid = true;
            return $user;
        }
    }



}
