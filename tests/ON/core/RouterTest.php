<?php
    use Oraculum\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testConstruct()
    {
        $result = new Router('teste');
        $this->assertInstanceOf(Oraculum\Router::class, $result);
    }

    public function testSetDefault()
    {
        $router = new Router('teste');
        $result = $router->setDefault('teste');

        $this->assertNull($result);
    }

    public function testGet()
    {
        $router = new Router('teste');
        $result = $router->get('busca', function () {
        });

        $this->assertNull($result);
    }

    public function testaddAlias()
    {
        $_SERVER['REQUEST_URI'] = 'http://localhost/origem/teste';
        Router::addAlias('origem', 'destino');
        $result = $_SERVER['REQUEST_URI'];
        $this->assertEquals('http://localhost/destino/teste', $result);
    }

    /*public function testConstruct()
    {
        $this->expectException('Error');
        $result=new Routes();
    }*/
}
