<?php
/**
 * Created by IntelliJ IDEA.
 * User: duboi
 * Date: 31/12/2015
 * Time: 14:03
 */

namespace website\db;


abstract class IdBasedObject extends BaseObject
{
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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