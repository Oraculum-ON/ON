<?php
    use ON\Routes;
use PHPUnit\Framework\TestCase;

class RoutesTest extends TestCase
{
    public function testAdd()
    {
        $_SERVER['REQUEST_URI'] = 'http://localhost/origem/teste';
        Routes::add('origem', 'destino');
        $result = $_SERVER['REQUEST_URI'];
        $this->assertEquals('http://localhost/destino/teste', $result);
    }
}
