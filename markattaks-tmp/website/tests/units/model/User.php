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
    class User extends atoum
    {
        const DELETE_ALL_TABLE_CONTENT = 'DELETE FROM `USER`';
        const DEFAULT_PHONE_NUMBER = '000';
        const DEFAULT_ADDRESS = 'nowhere';
        const DEFAULT_DATE = '2000-01-01';
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
                ->if($this->testedInstance->login = uniqid("user_", true))
                ->if($this->testedInstance->password = 'foo')
                ->if($this->testedInstance->role = 'admin')
                ->if($this->testedInstance->first_name = 'admin')
                ->if($this->testedInstance->last_name = 'admin')
                ->if($this->testedInstance->date_of_birth = self::DEFAULT_DATE)
                ->if($this->testedInstance->address = self::DEFAULT_ADDRESS)
                ->if($this->testedInstance->phone = self::DEFAULT_PHONE_NUMBER)
                ->if($this->testedInstance->email = $this->mailAddress())
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
                ->if($this->testedInstance->date_of_birth = self::DEFAULT_DATE)
                ->if($this->testedInstance->address = self::DEFAULT_ADDRESS)
                ->if($this->testedInstance->phone = self::DEFAULT_PHONE_NUMBER)
                ->if($this->testedInstance->email = $this->mailAddress())
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
                ->if($this->testedInstance->date_of_birth = self::DEFAULT_DATE)
                ->if($this->testedInstance->address = self::DEFAULT_ADDRESS)
                ->if($this->testedInstance->phone = self::DEFAULT_PHONE_NUMBER)
                ->if($this->testedInstance->email = $this->mailAddress())
            ->then
                ->boolean($this->testedInstance->save())
                    ->isTrue()
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->login = $login)
                    ->if($this->testedInstance->password = 'foo')
                    ->if($this->testedInstance->role = 'admin')
                    ->if($this->testedInstance->first_name = 'admin')
                    ->if($this->testedInstance->last_name = 'admin')
                    ->if($this->testedInstance->date_of_birth = self::DEFAULT_DATE)
                    ->if($this->testedInstance->address = self::DEFAULT_ADDRESS)
                    ->if($this->testedInstance->phone = self::DEFAULT_PHONE_NUMBER)
                    ->if($this->testedInstance->email = $this->mailAddress())
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

        public function testDelete()
        {
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance->login = uniqid("user_", true))
                ->if($this->testedInstance->password = 'foo')
                ->if($this->testedInstance->role = 'student')
                ->if($this->testedInstance->first_name = 'bad')
                ->if($this->testedInstance->last_name = 'boy')
                ->if($this->testedInstance->date_of_birth = self::DEFAULT_DATE)
                ->if($this->testedInstance->address = self::DEFAULT_ADDRESS)
                ->if($this->testedInstance->phone = self::DEFAULT_PHONE_NUMBER)
                ->if($this->testedInstance->email = $this->mailAddress())
            ->then
                ->boolean($this->testedInstance->save())
                    ->isTrue()
                ->given($notif = new Notification())
                    ->if($notif->message = "hey")
                    ->if($notif->read = 1)
                    ->if($notif->creation_date = date('Y-m-d H:i:s'))
                    ->if($notif->target_user_id = $this->testedInstance->getId())
                ->then
                    ->boolean($notif->save())
                        ->isTrue()
                    ->given(\website\model\User::delete($this->testedInstance->getId()))
                    ->then
                        ->variable(\website\model\User::findOneWhere(['id' => $this->testedInstance->getId()]))
                            ->isNull()
                        ->variable(Notification::findOneWhere(['id' => $notif->getId()]))
                            ->isNull();
            //TODO test with module subscription
        }

        public function testMailAddress()
        {
            $email = 'test@mail.com';
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance->login = uniqid("user_", true))
                ->if($this->testedInstance->password = 'foo')
                ->if($this->testedInstance->role = 'student')
                ->if($this->testedInstance->first_name = 'bad')
                ->if($this->testedInstance->last_name = 'boy')
                ->if($this->testedInstance->date_of_birth = self::DEFAULT_DATE)
                ->if($this->testedInstance->address = self::DEFAULT_ADDRESS)
                ->if($this->testedInstance->phone = self::DEFAULT_PHONE_NUMBER)
                ->if($this->testedInstance->email = $email)
            ->then
                ->boolean($this->testedInstance->save())
                    ->isTrue();
        }

        public function testInsertBadMailAddress()
        {

            $bad_mailaddresses = ['bad address', 'test@', 'test@g', 'test@t.', 'te@t@t'];

            array_map(function($mail){
                $this
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->login = uniqid("user_", true))
                    ->if($this->testedInstance->password = 'foo')
                    ->if($this->testedInstance->role = 'student')
                    ->if($this->testedInstance->first_name = 'bad')
                    ->if($this->testedInstance->last_name = 'boy')
                    ->if($this->testedInstance->date_of_birth = self::DEFAULT_DATE)
                    ->if($this->testedInstance->address = self::DEFAULT_ADDRESS)
                    ->if($this->testedInstance->phone = self::DEFAULT_PHONE_NUMBER)
                    ->if($this->testedInstance->email = $mail)
                ->then
                ->exception(function(){$this->testedInstance->save();})
                    ->isInstanceOf('PDOException')
                    ->message
                        ->contains('bad email address')
                ;
            }, $bad_mailaddresses);

        }

        public function testUpdateBadMailAddress()
        {

            $bad_mailaddresses = ['bad address', 'test@', 'test@g', 'test@t.', 'te@t@t'];

            array_map(function($mail){
                $this
                ->given($this->newTestedInstance())
                    ->if($this->testedInstance->login = uniqid("user_", true))
                    ->if($this->testedInstance->password = 'foo')
                    ->if($this->testedInstance->role = 'student')
                    ->if($this->testedInstance->first_name = 'bad')
                    ->if($this->testedInstance->last_name = 'boy')
                    ->if($this->testedInstance->date_of_birth = self::DEFAULT_DATE)
                    ->if($this->testedInstance->address = self::DEFAULT_ADDRESS)
                    ->if($this->testedInstance->phone = self::DEFAULT_PHONE_NUMBER)
                    ->if($this->testedInstance->email = $this->mailAddress())
                ->then
                    ->boolean($this->testedInstance->save())
                        ->isTrue()
                    ->then
                        ->given($this->testedInstance)
                            ->if($this->testedInstance->email = $mail)
                        ->then
                            ->exception(function(){$this->testedInstance->save();})
                                ->isInstanceOf('PDOException')
                                ->message
                                    ->contains('bad email address')
                ;
            }, $bad_mailaddresses);

        }

        public function mailAddress()
        {
            return uniqid("mail", true) . "@mail.com";
        }
    }

}
