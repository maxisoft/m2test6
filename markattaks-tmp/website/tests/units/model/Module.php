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
    class Module extends atoum
    {
        use DBTrait;
        const DELETE_ALL_TABLE_CONTENT = 'DELETE FROM `MODULE`';
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
                    ->isTrue();
        }


        public function testMapping()
        {
            $this
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->id = 500000)
                    ->if($this->testedInstance->name = uniqid("name", true))
                    ->if($this->testedInstance->code = self::code())
                    ->if($this->testedInstance->coefficient = 2)
                    ->if($this->testedInstance->description = '')
                ->then
                    ->boolean($this->allSqlPropertiesNotUndef($this->testedInstance))  //assert that all properties filled
                        ->isTrue()
                    ->boolean($this->testedInstance->save())
                        ->isTrue();
        }

        public static function code()
        {
            return sprintf("%'.04d\n", self::$code++);
        }
    }



}
