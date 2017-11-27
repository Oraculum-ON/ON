<?php

class TextTest extends PHPUnit_Framework_TestCase
{
    public function testMoeda()
    {
		define('MOEDA', 'R$');
        $text = new ON\Text;
        $result = $text->moeda(1.23);
        $this->assertEquals('R$ 1,23', $result);
		
        $result = $text->moeda(1.23, false);
        $this->assertEquals('1,23', $result);		
    }
}
