<?php

namespace website\tests\units {
use \atoum;

class Vector extends atoum {

    public function testC1C2 ( ) {
		// test des getters
    }

    public function testLengthSimple ( ) {
		// test d'une longueur simple (qui tombe juste)
		// longueur entre (0,0) et (3,4) égale 5.0
    }

    public function testLength ( ) {
		// test d'une longueur décimale
		// longueur entre (0,0) et (3,2) égale 3.605551275464... 
    }
}

}
