<?php

namespace website\db\tests\units {
use \atoum;
    
class DBInst extends atoum {

    public function testGetFirstEnvVarValueDefault ( ) {
        $mustReturn = "*@xn";
        $this
        ->given($this->newTestedInstance())
            ->if($this->function->getenv = false) // mock php's getenv function
        ->then
            ->string($this->testedInstance->getFirstEnvVar($mustReturn, "env"))
                ->isEqualTo($mustReturn);
    }

    public function testGetFirstEnvVarValue ( ) {
        $mustReturn = "FOO";
        $default = "THIS IS BAD";
        $this
        ->given($this->newTestedInstance())
            ->if($this->function->getenv[0] = false) // mock php's getenv function
            ->if($this->function->getenv[1] = $mustReturn)// mock php's getenv function
        ->then
            ->string($this->testedInstance->getFirstEnvVar($default, "MISSING", "IN_ENV"))
                ->isEqualTo($mustReturn);
    }

    public function testDefaultGetHost ( ) {
        $mustReturn = \website\db\DBInst::DEFAULT_HOST;
        $this
        ->given($this->newTestedInstance())
            ->if($this->function->getenv = false) // mock php's getenv function
        ->then
            ->string($this->testedInstance->getHost())
                ->isEqualTo($mustReturn);
    }

    public function testDefaultGetPort ( ) {
        $mustReturn = \website\db\DBInst::DEFAULT_PORT;
        $this
        ->given($this->newTestedInstance())
            ->if($this->function->getenv = false) // mock php's getenv function
        ->then
            ->string($this->testedInstance->getPort())
                ->isEqualTo($mustReturn);
    }

    public function testDefaultGetUsername ( ) {
        $mustReturn = \website\db\DBInst::DEFAULT_USER;
        $this
        ->given($this->newTestedInstance())
            ->if($this->function->getenv = false) // mock php's getenv function
        ->then
            ->string($this->testedInstance->getUsername())
                ->isEqualTo($mustReturn);
    }

    public function testDefaultGetPassword ( ) {
        $mustReturn = \website\db\DBInst::DEFAULT_PASSWORD;
        $this
        ->given($this->newTestedInstance())
            ->if($this->function->getenv = false) // mock php's getenv function
        ->then
            ->string($this->testedInstance->getPassword())
                ->isEqualTo($mustReturn);
    }

    public function testDefaultGetDBName ( ) {
        $mustReturn = \website\db\DBInst::DEFAULT_DB_NAME;
        $this
        ->given($this->newTestedInstance())
            ->if($this->function->getenv = false) // mock php's getenv function
        ->then
            ->string($this->testedInstance->getDBName())
                ->isEqualTo($mustReturn);
    }

    public function testGetInstance ( ) {
        $this
        ->object(\website\db\DBInst::getInstance())
            ->isInstanceOf('\PDO')
            ->isIdenticalTo(\website\db\DBInst::getInstance());
    }
}

}
