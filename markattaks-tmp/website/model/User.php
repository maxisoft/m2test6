<?php

namespace website\model;


require_once 'autoload.php';
use website\db\IdBasedObject;

class User extends IdBasedObject
{
    private static $ROLES = ['admin' => 0, 'teacher' => 1, 'student' => 2];

    protected $login;
    protected $password;
    protected $role;
    protected $first_name;
    protected $last_name;
    protected $date_of_birth;
    protected $address;
    protected $phone;
    protected $email;
    protected $valid;


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

    public static function isValidRole($role)
    {
        return isset(self::$ROLES[$role]);
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function isValid()
    {
        return $this->valid;
    }



    public function validate()
    {
        if (!self::isUndef($this->getRole()) && !self::isValidRole($this->getRole())) {
            throw new \RuntimeException("bad role");
        }
        return true;
    }

    public static function delete($id)
    {
        $transactStarted = false;
        if (!self::db()->inTransaction()) {
            $transactStarted = true;
            self::db()->beginTransaction();
        }

        $queries = array();

        $queries[] = "DELETE FROM " . Notification::tableName() . " WHERE target_user_id = :id";
        $queries[] = "DELETE FROM " . StudentModuleSubscription::tableName() . " WHERE user_id = :id";
        $queries[] = "DELETE FROM " . TeacherModuleSubscription::tableName() . " WHERE user_id = :id";

        $queries[] = "DELETE FROM " . self::tableName() . " WHERE id = :id";

        foreach ($queries as &$query) {
            $st = self::db()->prepare($query);
            $st->execute(['id' => $id]);
        }

        if ($transactStarted && self::db()->inTransaction()) {
            self::db()->commit();
        }
    }
}