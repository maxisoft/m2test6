<?php

namespace website\model;

require_once 'autoload.php';
use website\db\IdBasedObject;

class Module extends IdBasedObject
{
    protected $owner_user;
    protected $name;
    protected $coefficient;

    /**
     * @return mixed
     */
    public function getOwnerUser()
    {
        return $this->owner_user;
    }

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

    public function validate()
    {
        // TODO: Implement validate() method.
        return true;
    }

}