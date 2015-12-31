<?php

namespace website\utils\tests\units {
use \atoum;
    use website\db\DBTrait;
    use website\model\User;

    const DROP_TABLE = 'DROP TABLE IF EXISTS `USER`';
    const CREATE_TABLE_SQL = 'CREATE TABLE IF NOT EXISTS `USER` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (id))
ENGINE = MEMORY;';

    class Session extends atoum {
    use DBTrait;

    public function setUp()
    {
        putenv("MYSQL_DB_NAME=m2test6-utest");
        session_status() == PHP_SESSION_NONE or session_destroy();
    }

    public function tearDown()
    {
        session_status() == PHP_SESSION_NONE or session_destroy();
    }

    public function testLogin()
    {
        $this->db()->exec(join(';', [DROP_TABLE, CREATE_TABLE_SQL]));
        $this
        ->given($user = new User())
            ->if($user->login = "toto")
            ->if($user->password = \website\utils\Password::hash("banana", false))
        ->then
            ->boolean($user->save())
                ->isTrue()
            ->given($this->newTestedInstance())
            ->then
                ->boolean($this->testedInstance->login("toto", "banana"))
                    ->isTrue()
        ;
    }

    public function testFailLogin()
    {
        $this->db()->exec(join(';', [DROP_TABLE, CREATE_TABLE_SQL]));

        $this
        ->given($user = new User())
            ->if($user->login = "toto")
            ->if($user->password = \website\utils\Password::hash("banana", false))
        ->then
            ->boolean($user->save())
                ->isTrue()
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
