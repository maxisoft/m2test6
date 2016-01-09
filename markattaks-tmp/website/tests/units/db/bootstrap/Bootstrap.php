<?php
namespace website\db\bootstrap\tests\units;

require_once __DIR__ .'/../../../tool/DB.php';

use \atoum;
use website\tests\tool\DB;

/**
 * @engine inline
 */
class Bootstrap extends atoum
{


    public function setUp()
    {
        DB::init();
    }

    public function tearDown()
    {
        DB::end();
    }

    public function testIt()
    {
        $this
        ->given($this->newTestedInstance())
        ->then
            ->boolean(call_user_func($this->testedInstance))
                ->isTrue();
    }
}