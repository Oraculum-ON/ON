<?php

use Oraculum\Model;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testConstruct()
    {
        define('MODEL_DIR', 'tests/assets/models');
        $result = new Model('sqlite');
        $this->assertInstanceOf(Oraculum\Model::class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testConstructError1()
    {
        $this->expectException('Oraculum\Exception');
        define('MODEL_DIR', 'tests/assets/models');
        $result = new Model('not-found');
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoadModelError1()
    {
        $this->expectException('Oraculum\Exception');
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model();
        $result = $db->loadModel('sqlite2');
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoadModelError2()
    {
        $this->expectException('Oraculum\Exception');
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model();
        $result = $db->loadModel('sqlite3');
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoadTable()
    {
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model('sqlite');
        $result = $db->getTable('teste');
        $this->assertTrue($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetTableError()
    {
        $this->expectException('Oraculum\Exception');
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model('sqlite');
        $db->getTable();
    }

    /**
     * @runInSeparateProcess
     */
    /*public function testLoadDynamicModelClassError()
    {
        $this->expectException('Oraculum\Exception');
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Models('sqlite');
        $result=$db->loadDynamicModelClass();
        $this->assertTrue($result);
    }*/

    /**
     * @runInSeparateProcess
     */
    public function testCloseModel()
    {
        $result = Model::closeModel();
        $this->assertNull($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetDSNError()
    {
        $this->expectException('Oraculum\Exception');
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model('sqlite');
        $result = $db->setDsn();
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetDSN()
    {
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model('sqlite');
        $result = $db->setDsn('sqlite://tests/assets/models/teste.sqlite@/');
        $this->assertNull($result);

        $result = $db->setDsn('sqlite2://tests/assets/models/teste.sqlite@/');
        $this->assertNull($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetModelName()
    {
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model('sqlite');
        $result = $db->getModelName();
        $this->assertEquals('sqlite', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGenerateAR()
    {
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model('sqlite');
        $result = $db->generateAR('teste');
        $this->assertNull($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGenerateARError()
    {
        $this->expectException('Oraculum\Exception');
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model();
        $db->loadModel('sqlite3');
        $db->generateAR();
        //$this->assertInstanceOf(ON\Models::class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGeneratorARReturn()
    {
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model('sqlite');
        $result = $db->generateAR('teste', false);
        $class = '<'.'?'."php \nclass Teste extends Oraculum\ActiveRecord{\n\tpublic function __construct(){\n\t\tparent::__construct(get_class(\$this));\n\t}\n}\n";
        $this->assertEquals($class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGeneratorARAll()
    {
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model('sqlite');
        $result = $db->generateAR();
        $this->assertNull($result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testGeneratorARAll2()
    {
        $this->expectException('Oraculum\Exception');
        define('MODEL_DIR', 'tests/assets/models');
        $db = new Model('teste');
        $result = $db->generateAR();
    }
}
