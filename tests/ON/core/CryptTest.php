<?php
	use ON\Crypt;
	
	class CryptTest extends PHPUnit_Framework_TestCase
    {
		public function testCrypt(){
			$str=Crypt::strCrypt('testCrypt');
			$result=Crypt::strDecrypt($str);
			$this->assertEquals('testCrypt', $result);
		}
		
		public function testBlowfish(){
			$str=Crypt::blowfish('testBlowfish1');
			$result=Crypt::blowfishCheck('testBlowfish1', $str);
			$this->assertTrue($result);
		}
		
		public function testBlowfishCusto(){
			$str=Crypt::blowfish('testBlowfish2', 55);
			$result=Crypt::blowfishCheck('testBlowfish2', $str);
			$this->assertTrue($result);
		}
	}
