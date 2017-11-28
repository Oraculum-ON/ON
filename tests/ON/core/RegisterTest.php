<?php
	use ON\Register;
	use PHPUnit\Framework\TestCase;

	class RegisterTest extends TestCase
    {
		public function testRegister(){
			Register::set('variavel', 'testeunitario');
			$result = Register::get('variavel');
			$this->assertEquals('testeunitario', $result);
		}
	}
