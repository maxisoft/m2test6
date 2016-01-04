<?php
/**
 * Created by IntelliJ IDEA.
 * User: duboi
 * Date: 04/01/2016
 * Time: 19:49
 */

namespace website\db\tests\units;


class Escape extends \atoum
{
    public function testEscapeSQLLike()
    {
        $this
        ->given($tested = "azerty_qwerty/QWERTZ% QZERTY")
        ->then
            ->string(\website\db\Escape::escapeSQLLike($tested))
                ->isEqualTo("azerty/_qwerty//QWERTZ/% QZERTY");
    }
}