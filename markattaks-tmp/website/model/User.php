<?php

namespace website\model;


require_once 'autoload.php';
use website\db\IdBasedObject;

class User extends IdBasedObject
{
    protected $login;
    protected $password;
    protected $role;
    protected $first_name;
    protected $last_name;
    protected $date_of_birth;


    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getDateOfBird()
    {
        return $this->date_of_birth;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    public function validate()
    {
        // TODO: Implement validate() method.
        return true;
    }
}