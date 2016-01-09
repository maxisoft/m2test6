<?php

namespace website\model;

require_once 'autoload.php';
use website\db\IdBasedObject;

class Module extends IdBasedObject
{
    protected $name;
    protected $code;
    protected $coefficient;
    protected $description;
    protected $valid;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * @return mixed
     */
    public function isValid()
    {
        return $this->valid;
    }

    public function onInsert()
    {
        parent::onInsert();
        $this->description = null;
    }

    public function validate()
    {
        $code_len = strlen($this->code);
        if (!(2 <= $code_len && $code_len <= 6)) {
            throw new \RuntimeException("bad code length");
        }
        return true;
    }

}