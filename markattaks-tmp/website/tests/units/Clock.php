<?php

namespace website\tests\units {
use \atoum;
class Clock extends atoum {

    public function testTimestamp ( ) {

        $this
            ->if($clock = new \website\Clock())
            ->then
                ->integer($clock->getTimestamp())
            ;
    }

    public function testResetReturn ( ) {

        $this
            ->if($clock = new \website\Clock())
            ->then
                ->object($clock->reset())
                    ->isIdenticalTo($clock)
            ;
    }

    public function testReset ( ) {
		// Exemple de Mock
        $this
            ->given($clock = new \Mock\website\Clock())
            ->and($previousTime = $clock->getTimestamp())

            ->if($delay = 10)
            ->and($nextTime = $previousTime + $delay)

            ->and($this->calling($clock)->getCurrentTime = $nextTime)
            ->and($clock->reset())

            ->then
                ->integer($clock->getTimestamp())
                    ->isGreaterThan($previousTime)
                    ->isEqualTo($previousTime + $delay)
            ;
    }

    public function testDifference ( ) {

    }
}

}
