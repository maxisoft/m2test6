<?php

namespace website\model;

use website\db\BaseObject;

require_once 'autoload.php';

class ModuleStats extends BaseObject
{

    protected $module_id;
    protected $average;
    protected $min_mark;
    protected $max_mark;
    protected $standard_deviation;
    protected $student_count;


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
    public function getAverage()
    {
        return $this->average;
    }

    /**
     * @return mixed
     */
    public function getMinMark()
    {
        return $this->min_mark;
    }

    /**
     * @return mixed
     */
    public function getMaxMark()
    {
        return $this->max_mark;
    }

    /**
     * @return mixed
     */
    public function getStandardDeviation()
    {
        return $this->standard_deviation;
    }

    /**
     * @return mixed
     */
    public function getStudentCount()
    {
        return $this->student_count;
    }

    /**
     * @param string $projection
     * @return mixed
     */
    public function getModule($projection='*')
    {
        return Module::findOneWhere(['id' => $this->getModuleId()], null, $projection);
    }

    protected function primaryKeysMapping()
    {
        return null;
    }

    public function validate()
    {
        return false;
    }

    public static function tableName()
    {
        return 'MODULE_STATS';
    }
}