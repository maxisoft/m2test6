<?php

namespace website\db\tests\units {

    use atoum;
    use website\db\__Undef;
    use website\db\DBTrait;
    const DROP_TABLE = 'DROP TABLE IF EXISTS DUMMY';
    const CREATE_TABLE_SQL = 'CREATE TABLE IF NOT EXISTS DUMMY (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  data1 VARCHAR(45) NOT NULL,
  data2 VARCHAR(255),
  PRIMARY KEY (id))
ENGINE = MEMORY;';
    const DELETE_ALL_TABLE_CONTENT = 'DELETE FROM DUMMY';
    /**
     * @engine inline
     */
    class BaseObject extends atoum
    {
        use DBTrait;

        public function setUp()
        {
            putenv("MYSQL_DB_NAME=m2test6-utest");
            $this->resetDB();
        }

        public function beforeTestMethod($method)
        {
            // Exécutée *avant chaque* méthode de test.
            if (self::startsWith($method, 'testFind')) {
                $this->cleanDB();
            }
        }

        function resetDB()
        {
            $this->db()->exec(join(';', [DROP_TABLE, CREATE_TABLE_SQL]));
        }

        function cleanDB()
        {
            $this->db()->exec(DELETE_ALL_TABLE_CONTENT);
        }

        static function startsWith($haystack, $needle)
        {
            $length = strlen($needle);
            return (substr($haystack, 0, $length) === $needle);
        }


        public function tearDown()
        {
            //$this->db()->exec(DROP_TABLE);
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
            ->given($testedInstance)
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
            ->if($testedInstance->setUpdateMode())
            ->then
                ->exception(function() use($testedInstance) {
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
            ->given($arr = [1=>1, 3=>2, 4=>4])
            ->then
                ->boolean(\website\db\BaseObject::isAssoc($arr))
                    ->isTrue();

            $this
            ->given($arr = ["a"=>1, 1=>2, 2=>4])
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
            ->given($undef = new __Undef()) //any another Undef instance doesn't work
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

        public function testFind()
        {
            $instance = $this->testSaveAndRetrieveBack();

            $this
            ->given($result = Dummy::find())
            ->then
                ->integer(sizeof($result))
                    ->isEqualTo(1)
                ->array($result)
                    ->contains($instance);
        }

        public function testFindWithLimit()
        {
            $lim = 10;

            //insert $lim + 1 elements
            $acc = [];
            for( $i=0 ; $i < $lim + 1; $i++ ) {
                $acc[] = $this->testSaveAndRetrieveBack();
            }

            $this
            ->given($result = Dummy::find($lim))
            ->then
                ->integer(sizeof($result))
                    ->isEqualTo($lim)
                ->array($result)
                    ->containsValues(array_slice($acc, 0, $lim));
        }

        public function testFindWithoutData()
        {
            $this
            ->given($result = Dummy::find())
            ->then
                ->boolean(empty($result))
                    ->isTrue();
        }

        public function testFindWhere()
        {
            $instance = $this->testSaveAndRetrieveBack();

            $this
            ->given($result = Dummy::findWhere(['id' => $instance->getId()]))
            ->then
                ->integer(sizeof($result))
                    ->isEqualTo(1)
                ->array($result)
                    ->contains($instance);
        }

        public function testFindWhereWithEmptyResult()
        {
            $this
            ->given($result = Dummy::findWhere('0 = 1'))
                ->then
                ->boolean(empty($result))
                    ->isTrue();
        }

        public function testFindWhereAlternativeSyntax1()
        {
            $instance = $this->testSaveAndRetrieveBack();
            $this
            ->given($result = Dummy::findWhere(['id'], [$instance->getId()]))
            ->then
                ->integer(sizeof($result))
                    ->isEqualTo(1)
                ->array($result)
                    ->contains($instance);
        }

        public function testFindWhereAlternativeSyntax3()
        {
            $instance = $this->testSaveAndRetrieveBack();
            $this
            ->given($result = Dummy::findWhere('id = ?', [$instance->getId()]))
            ->then
                ->integer(sizeof($result))
                    ->isEqualTo(1)
                ->array($result)
                    ->contains($instance);
        }

        public function testFindWhereAlternativeSyntax4()
        {
            $instance = $this->testSaveAndRetrieveBack();
            $this
            ->given($result = Dummy::findWhere('id = :id', ['id' => $instance->getId()]))
            ->then
                ->integer(sizeof($result))
                    ->isEqualTo(1)
                ->array($result)
                    ->contains($instance);
        }

        public function testFindWhereLimit()
        {
            $lim = 10;

            //insert $lim + 1 elements
            $acc = [];
            for( $i=0 ; $i < $lim + 1; $i++ ) {
                $acc[] = $this->testSaveAndRetrieveBack();
            }

            $this
            ->given($result = Dummy::findWhere(['data1', 'data2'], [$acc[0]->getData1(), $acc[0]->getData2()], $lim))
            ->then
                ->integer(sizeof($result))
                    ->isEqualTo($lim)
                ->array($result)
                    ->containsValues(array_slice($acc, 0, $lim));
        }

        public function testFindWhereProjection()
        {
            $instance = $this->testSaveAndRetrieveBack();
            $this
            ->given($result = Dummy::findWhere(['id'], [$instance->getId()], null, ['id', 'data2']))
            ->then
                ->integer(sizeof($result))
                    ->isEqualTo(1)
                ->given($elem = $result[0])
                ->then
                    ->variable($elem->getId())
                        ->isEqualTo($instance->getId())
                    ->variable($elem->getData2())
                        ->isEqualTo($instance->getData2())
                    ->boolean($elem->isUndef($elem->getData1()))
                        ->isTrue();
        }

        public function testFindOneWhere()
        {
            $instance = $this->testSaveAndRetrieveBack();

            $this
            ->given($result = Dummy::findOneWhere(['id'], [$instance->getId()]))
            ->then
                ->object($result)
                    ->isEqualTo($instance);
        }

        public function testFindOneWhereNoResult()
        {
            $this
            ->given($result = Dummy::findOneWhere('id < ?', [0]))
            ->then
                ->variable($result)
                    ->isNull();
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
