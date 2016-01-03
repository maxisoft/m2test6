<?php

namespace website\model\tests\units {
    require_once __DIR__ .'/../../tool/DB.php';
    use atoum;
    use website\db\BaseObject;
    use website\db\DBTrait;
    use website\tests\tool\DB;

    const DELETE_ALL_TABLE_CONTENT = 'DELETE FROM `USER`';

    /**
     * @engine inline
     */
    class User extends atoum
    {
        use DBTrait;

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
            self::db()->exec(DELETE_ALL_TABLE_CONTENT);
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
                ->if($this->testedInstance->login = uniqid("user_", true))
                ->if($this->testedInstance->password = 'foo')
                ->if($this->testedInstance->role = 'admin')
                ->if($this->testedInstance->first_name = 'admin')
                ->if($this->testedInstance->last_name = 'admin')
                ->if($this->testedInstance->date_of_birth = '2000-01-01')
            ->then
                ->boolean($this->allSqlPropertiesNotUndef($this->testedInstance))  //assert that all properties filled
                    ->isTrue()
                ->boolean($this->testedInstance->save())
                    ->isTrue();
        }

        public function testBadRole()
        {
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance->role = 'BAD_ROLE')
                ->if($this->testedInstance->id = 3)
                ->if($this->testedInstance->login = 'toto')
                ->if($this->testedInstance->password = 'foo')
                ->if($this->testedInstance->first_name = 'admin')
                ->if($this->testedInstance->last_name = 'admin')
                ->if($this->testedInstance->date_of_birth = '2000-01-01')
            ->then
                ->exception(function(){
                        $this->testedInstance->save();
                    })
                    ->message
                        ->contains("bad role");
        }

        public function testLoginUniqueness()
        {
            $login = uniqid("user_", true);
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance->login = $login)
                ->if($this->testedInstance->password = 'foo')
                ->if($this->testedInstance->role = 'admin')
                ->if($this->testedInstance->first_name = 'admin')
                ->if($this->testedInstance->last_name = 'admin')
                ->if($this->testedInstance->date_of_birth = '2000-01-01')
            ->then
                ->boolean($this->testedInstance->save())
                    ->isTrue()
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->login = $login)
                    ->if($this->testedInstance->password = 'foo')
                    ->if($this->testedInstance->role = 'admin')
                    ->if($this->testedInstance->first_name = 'admin')
                    ->if($this->testedInstance->last_name = 'admin')
                    ->if($this->testedInstance->date_of_birth = '2000-01-01')
                ->then
                    ->exception(function(){
                        $this->testedInstance->save();
                    })
                        ->isInstanceOf('PDOException')
                        ->message
                            ->contains("Integrity constraint violation")
                            ->contains("Duplicate entry")
                            ->contains($login);
        }

        public function testIsValidRole()
        {
            $validRoles = ['admin', 'teacher', 'student'];
            foreach($validRoles as $role) {
                $this
                ->given($role)
                ->then
                    ->boolean(\website\model\User::isValidRole($role))
                        ->isTrue();
            }
        }

        public function testIsValidRoleFail()
        {
            $this
            ->given($role = 'BAD_ROLE')
            ->then
                ->boolean(\website\model\User::isValidRole($role))
                    ->isFalse();
        }
    }

}
