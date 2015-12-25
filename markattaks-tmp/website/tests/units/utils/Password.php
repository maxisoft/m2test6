<?php

namespace website\utils\tests\units {
use \atoum;
class Password extends atoum {


    public function testValidatePassword ( ) {
        $password = "okanvoabvkjabi";
        $this
        ->given($this->newTestedInstance())
        ->then
        ->boolean($this->testedInstance->validate($password))
        ->isEqualTo(true)
        ;
    }


    public function testValidateNotAPasswordString ( ) {
        $password = null;
        $this
        ->given($this->newTestedInstance())
        ->then
        ->exception(
            function() use($password) {
                // ce code lève une exception
                $this->testedInstance->validate($password);
            }
        )
        ->isInstanceof('\website\utils\PasswordException')
        ->message->isEqualTo('not a string')
        ;
    }

    public function testValidateLowerLimitPassword ( ) {
        $password = str_repeat("h", \website\utils\Password::MIN_LEN);
        $this
        ->given($this->newTestedInstance())
        ->then
        ->boolean($this->testedInstance->validate($password))
        ->isEqualTo(true)
        ;
    }

    public function testValidateTooShortPassword ( ) {
        $password = str_repeat("h", \website\utils\Password::MIN_LEN - 1);
        $this
        ->given($this->newTestedInstance())
        ->then
            ->exception(
                function() use($password) {
                    // ce code lève une exception
                    $this->testedInstance->validate($password);
                }
            )
                ->isInstanceof('\website\utils\PasswordException')
                ->message->contains('too short')
        ;
    }

    public function testValidateUpperLimitPassword ( ) {
        $password = str_repeat("h", \website\utils\Password::MAX_LEN);
        $this
        ->given($this->newTestedInstance())
        ->then
        ->boolean($this->testedInstance->validate($password))
        ->isEqualTo(true)
        ;
    }

    public function testValidateTooLongPassword ( ) {
        $password = str_repeat("h", \website\utils\Password::MAX_LEN + 1);
        $this
        ->given($this->newTestedInstance())
        ->then
        ->exception(
            function() use($password) {
                // ce code lève une exception
                $this->testedInstance->validate($password);
            }
        )
        ->isInstanceof('\website\utils\PasswordException')
        ->message->contains('too long')
        ;
    }

}

}
