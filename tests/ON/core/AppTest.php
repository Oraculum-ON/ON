<?php

use Oraculum\App;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testLoadView()
    {
        $app = new App();
        $result = $app->loadView();
        $this->assertInstanceOf(Oraculum\View::class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoadControl()
    {
        $app = new App();
        $result = $app->loadControl();
        $this->assertInstanceOf(Oraculum\Control::class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoadModel()
    {
        $app = new App();
        $result = $app->loadModel(null);
        $this->assertInstanceOf(Oraculum\Model::class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testView()
    {
        $app = new App();
        $result = $app->view();
        $this->assertInstanceOf(Oraculum\View::class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testControl()
    {
        $app = new App();
        $result = $app->control();
        $this->assertInstanceOf(Oraculum\Control::class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testModel()
    {
        $app = new App();
        $result = $app->model();
        $this->assertInstanceOf(Oraculum\Model::class, $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetControlDir()
    {
        $app = new App();
        $app->setControlDir('tests/assets');
        $result = CONTROL_DIR;
        $this->assertEquals('tests/assets', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetControlDirError()
    {
        $this->expectException('Oraculum\Exception');
        $app = new App();
        $app->setControlDir('teste');
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetViewDir()
    {
        //$app=new App();
        //$app->setViewDir('tests/assets');
        $result = VIEW_DIR;
        $this->assertEquals('tests/assets', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetViewDirError()
    {
        $this->expectException('Oraculum\Exception');
        $app = new App();
        $app->setViewDir('teste');
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetModelDir()
    {
        $app = new App();
        $app->setModelDir('tests/assets/models');
        $result = MODEL_DIR;
        $this->assertEquals('tests/assets/models', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetModelDirError()
    {
        $this->expectException('Oraculum\Exception');
        $app = new App();
        $app->setModelDir('teste');
    }

    /**
     * @runInSeparateProcess
     */
    public function testFrontController()
    {
        $app = new App();
        $result = $app->frontController();
        $this->assertInstanceOf(Oraculum\FrontController::class, $result);
    }

    public function testCheckDebug()
    {
        App::checkDebug();
        $result = (bool) ini_get('display_errors');
        $this->assertFalse($result);

        define('ON_DEBUG', true);
        App::checkDebug();
        $result = (bool) ini_get('display_errors');
        $this->assertTrue($result);
    }
}
