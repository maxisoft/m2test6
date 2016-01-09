<?php

namespace website\db\bootstrap;


use website\db\DBTrait;
use website\db\tests\units\DBInst;

class Bootstrap
{
    use DBTrait;
    public function __invoke($script='index.php')
    {
        $transStarted = false;

        if(!$this->db()->inTransaction()) {
            $this->db()->beginTransaction();
            $transStarted = true;
        }

        require __DIR__ . DIRECTORY_SEPARATOR . $script;

        if($transStarted && $this->db()->inTransaction()) {
            $this->db()->commit();
        }

        return true;
    }
}