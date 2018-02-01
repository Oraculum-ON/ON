<?php
    use Oraculum\Register;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    public function testGetVars()
    {
        $result = Register::getVars();
        $this->assertEmpty($result);
    }

    public function testRegister()
    {
        Register::set('variavel', 'testeunitario');
        $result = Register::get('variavel');
        $this->assertEquals('testeunitario', $result);
    }

    public function testRegisterEmpty()
    {
        $result = Register::get('variavel-inexistente');
        $this->assertEmpty($result);
    }

    public function testGetVarsFill()
    {
        $result = Register::getVars();
        $this->assertEquals(array('variavel'=>'testeunitario'), $result);
    }
}
