<?php

namespace website {

class Coordinates {

    protected $_x = 0.0;
    protected $_y = 0.0;



    public function __construct ( $x, $y ) {

        $this->_x = (float) $x;
        $this->_y = (float) $y;

        return;
    }

    public function getX ( ) {

        return $this->_x;
    }

    public function getY ( ) {

        return $this->_y;
    }
}

}
