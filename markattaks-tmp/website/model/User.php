<?php

namespace website\model;


require_once 'autoload.php';
use website\db\BaseObject;

class User extends BaseObject
{
    protected $id;
    protected $login;
    protected $password;
    protected $age;
    protected $role;
    protected $first_name;
    protected $last_name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

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
    public function getAge()
    {
        return $this->age;
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

    public function onInsert()
    {
        $this->id = $this->db()->lastInsertId();
    }


    protected function primaryKeysMapping()
    {
        return ['id' => $this->getId()];
    }
}