<?php

namespace website {

class Vector {

    protected $_c1 = null;
    protected $_c2 = null;



    public function __construct ( Coordinates $c1, Coordinates $c2 ) {

        $this->_c1 = $c1;
        $this->_c2 = $c2;

        return;
    }

    public function getFirstCoordinates ( ) {

        return $this->_c1;
    }

    public function getSecondCoordinates ( ) {

        return $this->_c2;
    }

    public function getLength ( ) {

        return sqrt(
            pow($this->_c2->getX() - $this->_c1->getX(), 2)
          + pow($this->_c2->getY() - $this->_c1->getY(), 2)
        );
    }
}

}
