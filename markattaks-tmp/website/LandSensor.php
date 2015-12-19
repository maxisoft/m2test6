<?php

namespace website {

class LandSensor {

    public function getNeededEnergy ( Vector $vector ) {

        return $vector->getLength() * (mt_rand(15, 25) / 100);
    }
}

}
