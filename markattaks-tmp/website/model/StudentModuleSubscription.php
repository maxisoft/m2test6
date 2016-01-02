<?php
/**
 * Created by IntelliJ IDEA.
 * User: duboi
 * Date: 31/12/2015
 * Time: 19:37
 */

namespace website\model;
use website\db\BaseObject;

require_once 'autoload.php';

class StudentModuleSubscription extends BaseObject
{
    protected $module_id;
    protected $user_id;
    protected $mark;

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

    /**
     * @return mixed
     */
    public function getMark()
    {
        return $this->mark;
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
        if (!(0 <= $this->getMark() && $this->getMark() <= 20)){
            throw new \RangeException("mark must be between 0 and 20");
        }
        return true;
    }

    public static function tableName()
    {
        return 'STUDENT_MODULE_SUBSCRIPTION';
    }


}