<?php

namespace website\utils\tests\units {
    require_once __DIR__ .'/../../tool/DB.php';
    use \atoum;
    use \website\db\DBTrait;
    use \website\model\User;
    use \website\tests\tool\DB;

    const DELETE_ALL_TABLE_CONTENT = 'DELETE FROM `USER`';

    /**
     * @engine inline
     */
    class Session extends atoum {
        use DBTrait;

        public function setUp()
        {
            DB::init();
            $this->cleanDB();
            $this->newUser()->save();
        }

        public function tearDown()
        {
            DB::end();
            session_status() == PHP_SESSION_NONE or session_destroy();
        }

        public function afterTestMethod($method)
        {
            // Exécutée *après chaque* méthode de test.
            session_unset();
        }

        private static function cleanDB()
        {
            self::db()->exec(DELETE_ALL_TABLE_CONTENT);
        }

        static function startsWith($haystack, $needle)
        {
            $length = strlen($needle);
            return (substr($haystack, 0, $length) === $needle);
        }

        private static function newUser()
        {
            $user = new User();
            $user->login = "toto";
            $user->password = \website\utils\Password::hash("banana", false);
            $user->first_name = 'toto';
            $user->last_name = 'toto';
            return $user;
        }

        public function testLogin()
        {
            $this
            ->given($this->newTestedInstance())
            ->then
                ->boolean($this->testedInstance->login("toto", "banana"))
                    ->isTrue()
            ;
        }

        public function testLoginFail()
        {

            $this
            ->given($this->newTestedInstance())
            ->then
                ->boolean($this->testedInstance->login("toto", "apple"))
                    ->isFalse()
            ;
        }

        public function testAlreadyLoggedIn()
        {
            $this->testLogin();
            $this
            ->given($this->newTestedInstance())
            ->then
                ->variable($this->testedInstance->login("toto", "banana"))
                    ->isNull()
            ;
        }

        public function testIsLogged()
        {
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance['user.id'] = '5000')
            ->then
                ->boolean($this->testedInstance->isLogged())
                    ->isTrue();
        }

        public function testIsNotLogged ()
        {
            $this
            ->given($this->newTestedInstance())
            ->then
                ->boolean($this->testedInstance->isLogged())
                    ->isFalse();
        }

        public function testOffsetExists ()
        {
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance['test'] = 'Okey-dokey')
            ->then
                ->boolean(isset($this->testedInstance['test']))
                    ->isTrue()
            ;
        }

        public function testFlushAndClose()
        {
            $this
            ->given($this->newTestedInstance())
                ->if($this->function->session_write_close = true)
            ->then
                ->if($this->testedInstance->flushAndClose())
                ->function('session_write_close')->wasCalled()->once();
        }

        public function testOffsetExistsOnEmptySession ()
        {
            $this
            ->given($this->newTestedInstance())
            ->then
                ->boolean(isset($this->testedInstance['test']))
                    ->isFalse()
            ;

        }


        public function testOffsetGetAndSet ()
        {
            $value = 'Okey-dokey';
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance['test'] = $value)
            ->then
                ->string($this->testedInstance['test'])
                    ->isEqualTo($value)
            ;
        }

        public function testOffsetUnSet ()
        {
            $value = 'Okey-dokey';
            $this
            ->given($this->newTestedInstance())
                ->if($this->testedInstance['test'] = $value)
                ->if($this->testedInstance->offsetUnset('test'))
            ->then
                ->boolean(isset($this->testedInstance['test']))
                    ->isFalse();
        }
    }

}
