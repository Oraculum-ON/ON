<?php
    use Oraculum\FrontController;
use PHPUnit\Framework\TestCase;

class FrontControllerTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSetBaseUrl2()
    {
        $_SERVER['REQUEST_URI'] = '/teste/teste2/teste3';
        //define('URL', 'teste');
        $app = new FrontController();
        $app->setBaseUrl('/');
        //var_dump(BASE);
        $this->assertEquals('/', URL);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetBaseUrl()
    {
        $_SERVER['REQUEST_URI'] = '/teste/teste2/teste3';
        //define('URL', 'teste');
        $app = new FrontController();
        $app->setBaseUrl('/teste/teste2');
        //var_dump(BASE);
        $this->assertEquals('/teste/teste2', URL);

        $this->assertEquals(1, BASE);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetErrorPage()
    {
        $app = new FrontController();
        $app->setErrorPage('500');
        //var_dump(ERROR_PAGE);
        $this->assertEquals('500', ERROR_PAGE);
    }

    /**
     * @runInSeparateProcess
     */
    public function testFrontController()
    {
        try {
            $_SERVER['REQUEST_URI'] = 'http://localhost/';
            //define('URL', 'http://localhost/');
            //define('BASE', '/');
            define('CONTROL_DIR', 'tests/assets');
            $app = new FrontController();
            //$app->setBaseUrl('/ON/exemplo/');
            $app->setDefaultPage('home');
            @$app->start();
            $app->setDefaultPage('home');
            $app->setErrorPage('404');
            @$app->start();
        } catch (InvalidArgumentException $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }
}
