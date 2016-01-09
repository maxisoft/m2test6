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
                    ->isTrue()
            ;
        }


        public function testMapping()
        {
            $this
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->id = 500000)
                    ->if($this->testedInstance->name = self::name())
                    ->if($this->testedInstance->code = self::code())
                    ->if($this->testedInstance->coefficient = 2)
                    ->if($this->testedInstance->description = '')
                    ->if($this->testedInstance->valid = true)
                ->then
                    ->boolean($this->allSqlPropertiesNotUndef($this->testedInstance))  //assert that all properties filled
                        ->isTrue()
                    ->boolean($this->testedInstance->save())
                        ->isTrue()
            ;
        }

        public function testDefaultValue()
        {
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance->name = self::name())
                ->if($this->testedInstance->code = self::code())
                ->if($this->testedInstance->coefficient = 2)
                ->if($this->testedInstance->valid = true)
            ->then
                ->boolean($this->testedInstance->save())
                    ->isTrue()
                ->boolean($this->testedInstance->hasChanges())
                    ->isFalse()
                ->given($dbTestedInstCopy = \website\model\Module::findOneWhere(['id' => $this->testedInstance->getId()]))
                ->then
                    ->object($dbTestedInstCopy)
                        ->isEqualTo($this->testedInstance)
            ;
        }

        public function testCodeUniqueness()
        {

            $code = self::code();
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance->name = self::name())
                ->if($this->testedInstance->code = $code)
                ->if($this->testedInstance->coefficient = 2)
                ->if($this->testedInstance->description = '')
                ->if($this->testedInstance->valid = true)
            ->then
                ->boolean($this->testedInstance->save())
                    ->isTrue()
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->name = self::name())
                    ->if($this->testedInstance->code = $code)
                    ->if($this->testedInstance->coefficient = 2)
                    ->if($this->testedInstance->description = '')
                    ->if($this->testedInstance->valid = true)
                ->then
                    ->exception(function(){$this->testedInstance->save();})
                        ->isInstanceOf('PDOException')
                        ->message
                            ->contains('Integrity constraint violation')
                            ->contains('Duplicate entry')
                            ->contains('code_UNIQUE')
            ;
        }
        public function testCodeLengthOk()
        {
            $testedLengths = range(2, 6);
            foreach ($testedLengths as $len) {
                $this
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->name = self::name())
                    ->if($this->testedInstance->code = str_repeat('9', $len))
                    ->if($this->testedInstance->coefficient = 2)
                    ->if($this->testedInstance->description = '')
                    ->if($this->testedInstance->valid = true)
                ->then
                    ->boolean($this->testedInstance->save())
                        ->isTrue();
            }
        }

        public function testBadCodeLength()
        {

            $testedLengths = [0, 1, 7];
            foreach ($testedLengths as $len) {
                $this
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->name = self::name())
                    ->if($this->testedInstance->code = str_repeat('9', $len))
                    ->if($this->testedInstance->coefficient = 2)
                    ->if($this->testedInstance->description = '')
                    ->if($this->testedInstance->valid = true)
                ->then
                    ->exception(function(){$this->testedInstance->save();})
                        ->isInstanceOf('RuntimeException')
                        ->message
                            ->contains('bad code length');
            }
        }

        public function testTableName()
        {
            $this
            ->given($tablename = \website\model\Module::tableName())
            ->then
                ->string($tablename)
                    ->isEqualTo('MODULE');
        }

        public static function name()
        {
            return uniqid("name", true);
        }

        public static function code()
        {
            return sprintf("%'.05d\n", self::$code++);
        }
    }



}
