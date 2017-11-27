<?php
	use ON\Register;

	class RegisterTest extends PHPUnit_Framework_TestCase
    {
		public function testRegister(){
			Register::set('variavel', 'testeunitario');
			$result = Register::get('variavel');
			$this->assertEquals('testeunitario', $result);
		}
	}
