<?php

namespace website\model;
use website\db\BaseObject;

require_once 'autoload.php';

class TeacherModuleSubscription extends BaseObject
{
    protected $module_id;
    protected $user_id;

    /**
     * @return mixed
     */
    public function getModuleId()
    {
        return $this->module_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    public function getUser($projection='*')
    {
        return User::findOneWhere(['id'], [$this->getUserId()], $projection);
    }

    public function getModule($projection='*')
    {
        return Module::findOneWhere(['id'], [$this->getModuleId()], $projection);
    }

    protected function primaryKeysMapping()
    {
        return ['module_id' => $this->getModuleId(), 'user_id' => $this->getUserId()];
    }

    public function validate()
    {
        return true;
    }

    public static function tableName()
    {
        return 'TEACHER_MODULE_SUBSCRIPTION';
    }


}