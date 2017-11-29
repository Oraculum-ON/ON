<?php
    use ON\Files;
use PHPUnit\Framework\TestCase;

class FilesTest extends TestCase
{
    public function testInc()
    {
        define('DEBUG', true);
        //var_dump(defined(DEBUG));
        $result = Files::inc('tests/pages/home.php');
        $this->assertTrue($result);
        
        $result = Files::inc('tests/pages/teste.php');
        $this->assertFalse($result);
    }
    public function testFileFilter()
    {
        $result = Files::fileFilter('text/html', 'htm|html');
        $this->assertTrue($result);
        
        $result = Files::fileFilter('text/html', 'htm|html', true);
        $this->assertFalse($result);
        
        $result = Files::fileFilter('application/zip', 'htm|html', true);
        $this->assertTrue($result);
        
        $result = Files::fileFilter('application/zip', 'htm|html');
        $this->assertFalse($result);
    }
    public function testExtensao()
    {
        $result = Files::extensao('teste.php');
        $this->assertEquals('php', $result);
    }
}
