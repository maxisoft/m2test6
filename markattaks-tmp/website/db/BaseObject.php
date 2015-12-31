<?php


namespace website\db;

abstract class BaseObject
{
    use DBTrait;
    private static $UNDEF;
    private $_modificationMap = array();
    private $_insert = true;

    public function __construct()
    {
        $properties = get_class_vars(get_class($this));
        foreach ($properties as $name => $value) {
            $firstChar = substr($name, 0, 1);
            if (is_null($value) && ctype_lower($firstChar) && $firstChar != '_') {
                $this->$name = self::undef();
            }
        }
    }

    public function __set($key, $val)
    {
        if (!isset($this->$key)) {
            throw new \RuntimeException("$key doesn't exists");
        }
        if ($this->isUpdateMode() && isset($this->primaryKeysMapping()[$key])) {
            throw new \RuntimeException("do not modify primary key");
        }

        $this->_modificationMap[$key] = $val;
        $this->$key = $val;
    }


    public static function tableName()
    {
        return strtoupper((new \ReflectionClass(get_called_class()))->getShortName());
    }

    public function hasChanges()
    {
        return !empty($this->_modificationMap);
    }

    public function onInsert()
    {
    }

    public function onUpdate()
    {
    }

    public static function find($limit = null)
    {
        return self::findWhere(null, null, $limit, '*');
    }

    public static function findWhere($where = null, $input_parameters = null, $limit = null, $projection = '*')
    {

        if (is_array($projection)) {
            $projection = join(',', $projection);
        }
        $query = 'SELECT ' . $projection . ' FROM ' . self::tableName();
        if (!is_null($where)) {
            $query .= ' WHERE ';
            $tmp = '';
            if (is_array($where)) {
                if (self::isAssoc($where)) {
                    if (!is_null($input_parameters)) {
                        throw new \RuntimeException("input parameter must be null.");
                    }
                    $keys = [];
                    $input_parameters = [];
                    foreach ($where as $k => $v) {
                        $keys[] = $k;
                        $input_parameters[] = $v;
                    }
                    return self::findWhere($keys, $input_parameters, $limit, $projection);
                } else {
                    foreach ($where as $e) {
                        $tmp .= ' AND ';
                        $tmp .= $e . ' = ?';
                    }
                }

                $where = ltrim($tmp, ' AND ');
            }
            $query .= $where;
        }
        if (is_numeric($limit)) {
            $query .= ' LIMIT ' . $limit;
        }
        $st = self::db()->prepare($query);
        $st->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class());
        if (is_string($input_parameters)) {
            $input_parameters = array($input_parameters);
        }
        $res = $st->execute($input_parameters);
        if (!$res) {
            return $res;
        }
        $ret = $st->fetchAll();
        foreach ($ret as $e) {
            $e->resetModification();
            $e->setUpdateMode();
        }
        return $ret;
    }

    public static function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function findOneWhere($where = null, $input_parameters = null, $projection = '*')
    {
        $res = self::findWhere($where, $input_parameters, 1, $projection);
        if (is_array($res)) {
            return count($res) == 1 ? $res[0] : null;
        }
        return $res;
    }

    abstract protected function primaryKeysMapping();

    protected function insertQuery()
    {
        $keys = array_keys($this->_modificationMap);
        $query = 'INSERT INTO ' . $this->tableName() . '(' . join(',', $keys) . ') VALUES (';
        $valueEscape = '';
        foreach ($this->_modificationMap as $key => $val) {
            if ($val === $this->undef()) {
                throw new \RuntimeException("undefined primary key value ($key)");
            }
            $valueEscape .= ',';
            $valueEscape .= ':' . $key;
        }
        $query .= ltrim($valueEscape, ',');
        $query .= ')';
        return $query;
    }

    protected function updateQuery()
    {
        $primaryKeys = $this->primaryKeysMapping();
        if (is_null($primaryKeys) || empty($primaryKeys)) {
            throw new \RuntimeException("there is no primary key. Can't update current object");
        }
        $keys = array_keys($this->_modificationMap);
        $query = 'UPDATE ' . $this->tableName() . ' SET ';
        $valueEscape = '';
        foreach ($keys as $key) {
            $valueEscape .= ',';
            $valueEscape .= $key . '= :' . $key;
        }
        $query .= ltrim($valueEscape, ',');
        $query .= ' WHERE ';

        $valueEscape = '';
        foreach ($primaryKeys as $name => $value) {
            $valueEscape .= ' AND ';
            $valueEscape .= $name . '= :' . $name;
        }
        $query .= ltrim($valueEscape, ' AND ');

        return $query;
    }

    public function save()
    {
        if (!$this->hasChanges()) {
            throw new \RuntimeException("there's no changes on object. Can't save current object");
        }

        $query = $this->isUpdateMode() ? $this->updateQuery() : $this->insertQuery();
        $st = $this->db()->prepare($query);
        $input_parameters = &$this->_modificationMap;
        if ($this->isUpdateMode()) {
            $input_parameters = $this->_modificationMap;
            foreach ($this->primaryKeysMapping() as $k => $v) {
                $input_parameters[$k] = $v;
            }
        }
        $ret = $st->execute($input_parameters);
        $st->closeCursor();
        if ($ret) {
            if (!$this->isUpdateMode()) {
                $this->onInsert();
                $this->setUpdateMode();
            } else {
                $this->onUpdate();
            }
            $this->resetModification();
        }
        return $ret;
    }

    public function setUpdateMode()
    {
        $this->_insert = false;
    }

    public function isUpdateMode()
    {
        return !$this->_insert;
    }

    public static function undef()
    {
        if (is_null(self::$UNDEF)) {
            self::$UNDEF = new __Undef();
        }
        return self::$UNDEF;
    }

    public static function isUndef($value)
    {
        return $value === self::$UNDEF;
    }

    protected function resetModification()
    {
        $this->_modificationMap = array();
    }
}


class __Undef
{
    function __construct()
    {

    }

    public function __toString()
    {
        return "UNDEF";
    }
}


