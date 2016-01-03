<?php

namespace website\model;
use website\db\IdBasedObject;

require_once 'autoload.php';

class Notification extends IdBasedObject
{
    protected $message;
    protected $read;
    protected $creation_date;
    protected $target_user_id;

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function isRead()
    {
        return $this->read;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * @return mixed
     */
    public function getTargetUserId()
    {
        return $this->target_user_id;
    }

    public function getTargetUser($projection='*')
    {
        return User::findOneWhere(['id'], [$this->getTargetUserId()], $projection);
    }

    public function validate()
    {
        // TODO: Implement validate() method.
        return true;
    }
}