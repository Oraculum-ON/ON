<?php
    use Oraculum\View;
use PHPUnit\Framework\TestCase;

define('VIEW_DIR', 'tests/assets');

class ViewTest extends TestCase
{
    public function testConstruct()
    {
        $result = new View('view', 'template');
        $this->assertInstanceOf(Oraculum\View::class, $result);
    }

    public function testConstruct2()
    {
        $result = new View('view', 'templatephp');
        $this->assertInstanceOf(Oraculum\View::class, $result);
    }

    public function testConstruct3()
    {
        $this->expectException('Oraculum\Exception');
        $view = new View('view', 'template-erro');
    }

    public function testConstruct4()
    {
        $this->expectException('Oraculum\Exception');
        $view = new View('invalida', 'template-erro');
    }

    public function testConstruct5()
    {
        $this->expectException('Oraculum\Exception');
        $view = new View('view', 'template-invalido');
    }

    public function testConstruct6()
    {
        $result = new View('view');
        $this->assertInstanceOf(Oraculum\View::class, $result);
    }

    public function testAddTemplateError()
    {
        $this->expectException('Oraculum\Exception');
        $view = new View();
        $view->addTemplate();
    }

    public function testLoadPageError()
    {
        $this->expectException('Oraculum\Exception');
        $view = new View();
        $view->loadPage();
    }

    public function testLoadElement()
    {
        $this->expectException('Oraculum\Exception');
        View::loadElement();
    }
}
