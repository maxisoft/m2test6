<?php

namespace website\db\tests\units {

    use atoum;
    use website\db\__Undef;
    use website\db\DBTrait;
    const DROP_TABLE = 'DROP TABLE IF EXISTS `DUMMY`';
    const CREATE_TABLE_SQL = 'CREATE TABLE IF NOT EXISTS `DUMMY` (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  data1 VARCHAR(45) NOT NULL,
  data2 VARCHAR(255),
  PRIMARY KEY (id))
ENGINE = InnoDB;';
    const TRUNCATE_TABLE_SQL = 'TRUNCATE Dummy';

    class BaseObject extends atoum
    {
        use DBTrait;

        public function setUp()
        {
            putenv("MYSQL_DB_NAME=m2test6-utest");
            $this->db()->exec(join(';', [DROP_TABLE, CREATE_TABLE_SQL]));
        }

        public function tearDown()
        {
            $this->db()->exec(DROP_TABLE);
        }

        public function testConstruct()
        {
            $this
            ->given($testedInstance = new Dummy())
            ->then
                ->object($testedInstance->getId())
                    ->isIdenticalTo($testedInstance->undef())
                ->object($testedInstance->getData1())
                    ->isIdenticalTo($testedInstance->undef())
                ->object($testedInstance->getData2())
                    ->isIdenticalTo($testedInstance->undef())

                ->boolean($testedInstance->hasChanges())
                    ->isFalse()
                ->boolean($testedInstance->isUpdateMode())
                    ->isFalse();
        }

        public function testHasChanges()
        {
            //base obj
            $this
            ->given($testedInstance = new Dummy())
            ->then
                ->boolean($testedInstance->hasChanges())
                    ->isFalse();

            //alter obj
            $this
            ->given($testedInstance = new Dummy())
                ->if($testedInstance->data1 = "test")
            ->then
                ->boolean($testedInstance->hasChanges())
                    ->isTrue();
        }

        public function testChangePrimaryKeyInInsertMode()
        {

            $this
            ->given($testedInstance = new Dummy())
            ->then
                ->integer($testedInstance->id = 9999)
                    ->isEqualTo(9999)
                ->boolean($testedInstance->isUpdateMode())
                    ->isFalse();
        }

        public function testChangePrimaryKeyInUpdateMode()
        {
            //can't alter primary key if updateMode's active
            $this
            ->given($testedInstance = new Dummy())
            ->then
                ->exception(function() use($testedInstance) {
                    $testedInstance->setUpdateMode();
                    $testedInstance->id = 3;
                })
                    ->message->isEqualTo('do not modify primary key')
                ->boolean($testedInstance->isUpdateMode())
                    ->isTrue();
        }

        public function testSave()
        {
            $this
            ->given($testedInstance = new Dummy())
                ->if($testedInstance->data1 = "test")
                ->if($testedInstance->data2 = "has to work")
            ->then
                ->boolean($testedInstance->save())
                    ->isTrue()
                ->boolean($testedInstance->isUpdateMode())
                    ->isTrue();
            return $testedInstance;
        }

        public function testSaveWithoutAnyChange()
        {
            $this
            ->given($testedInstance = new Dummy())
            ->then
                ->exception(function() use($testedInstance) {
                    $testedInstance->save();
                })
                ->message->contains('no changes on object');
        }

        public function testSaveAndRetrieveBack()
        {
            $oldTestedInstance = $this->testSave();
            $lastInsertId = $this->db()->lastInsertId();
            $this
            ->given($testedInstance = Dummy::findOneWhere(['id' => $lastInsertId]))
            ->then
                ->object($testedInstance)
                    ->isInstanceOf('website\db\tests\units\Dummy')
                    ->isNotIdenticalTo($oldTestedInstance)
                ->variable($testedInstance->getId())
                    ->isEqualTo($lastInsertId)
                ->string($testedInstance->getData1())
                    ->isEqualTo($oldTestedInstance->getData1())
                ->string($testedInstance->getData2())
                    ->isEqualTo($oldTestedInstance->getData2());
            return $testedInstance;
        }

        public function testSaveUpdateAndRetrieveBack()
        {
            $oldTestedInstance = $this->testSaveAndRetrieveBack();
            $this
            ->given($testedInstance = $oldTestedInstance)
                ->if($testedInstance->data2 = "must work")
            ->then
                ->boolean($testedInstance->save())
                    ->isTrue();

            $oldestTestedInstance = $oldTestedInstance;
            $oldTestedInstance = $testedInstance;

            $this
            ->given($testedInstance = Dummy::findOneWhere(['id' => $oldTestedInstance->getId()]))
            ->then
                ->object($testedInstance)
                    ->isInstanceOf('website\db\tests\units\Dummy')
                    ->isNotIdenticalTo($oldTestedInstance)
                    ->isNotIdenticalTo($oldestTestedInstance)
                ->variable($testedInstance->getId())
                    ->isEqualTo($oldTestedInstance->getId())
                ->string($testedInstance->getData1())
                    ->isEqualTo($oldTestedInstance->getData1())
                ->string($testedInstance->getData2())
                    ->isEqualTo($oldTestedInstance->getData2());

        }

        public function testSaveWithoutPrimaryKeyMapping()
        {
            $this
            ->given($testedInstance = new BadDummy())
                ->if($testedInstance->data1 = "test")
                ->if($testedInstance->data2 = "should work")
            ->then
                ->boolean($testedInstance->save())
                    ->isTrue()
                ->boolean($testedInstance->isUpdateMode())
                    ->isTrue();
            return $testedInstance;
        }

        public function testSaveThenUpdateWithoutPrimaryKeyMapping()
        {

            $this
            ->given($testedInstance = $this->testSaveWithoutPrimaryKeyMapping())
                ->if($testedInstance->data2 = "throw exception")
            ->then
                ->exception(function() use($testedInstance) {
                    $testedInstance->save();
                })
                    ->message->contains('there is no primary key');
        }

        public function testIsAssoc()
        {
            $this
            ->given($arr = [1,2,3])
                ->then
                ->boolean(\website\db\BaseObject::isAssoc($arr))
                    ->isFalse();

            $this
            ->given($arr = [0=>1, 1=>2, 2=>4])
            ->then
                ->boolean(\website\db\BaseObject::isAssoc($arr))
                    ->isFalse();

            $this
            ->given($arr = ["0"=>1, 1=>2, '2'=>4])
            ->then
                ->boolean(\website\db\BaseObject::isAssoc($arr))
                ->isFalse();

            $this
            ->given($arr = ["a"=> 1,1 => 2, 2=>4])
                ->then
                ->boolean(\website\db\BaseObject::isAssoc($arr))
                    ->isTrue();
        }

        public function testIsUndef()
        {

            $this
            ->given($undef = \website\db\BaseObject::undef())
            ->then
                ->boolean(\website\db\BaseObject::isUndef($undef))
                    ->isTrue();

            $this
            ->given($undef = null) //null isn't undef
            ->then
                ->boolean(\website\db\BaseObject::isUndef($undef))
                    ->isFalse();

            $this
            ->given($undef = new __Undef()) //any another instance of Undef too
            ->then
            ->boolean(\website\db\BaseObject::isUndef($undef))
                ->isFalse();
        }

        public function testIsUpdateMode()
        {
            $this
            ->given($testedInstance = new Dummy())
                ->boolean($testedInstance->isUpdateMode())
                    ->isFalse(); //default value
            $this
                ->given($testedInstance)
                ->if($testedInstance->setUpdateMode())
                ->then
                    ->boolean($testedInstance->isUpdateMode())
                    ->isTrue();
        }
    }



    class Dummy extends \website\db\BaseObject {

        protected $id;
        protected $data1;
        protected $data2;

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
        public function getData1()
        {
            return $this->data1;
        }

        /**
         * @return mixed
         */
        public function getData2()
        {
            return $this->data2;
        }

        protected function primaryKeysMapping()
        {
            return ["id" => $this->getId()];
        }
    }

    class BadDummy extends Dummy {

        protected function primaryKeysMapping()
        {
            return [];
        }

        public static function tableName()
        {
            return Dummy::tableName();
        }
    }

}
