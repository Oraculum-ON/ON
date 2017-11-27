<?php
	use ON\ON;
	
    class ONTest extends PHPUnit_Framework_TestCase
    {		
		public function testApp()
        {
			try {
				$app=@ON::App();
			} catch (InvalidArgumentException $notExpected) {
				$this->fail();
			}
			$this->assertTrue(TRUE);
		}
	
		public function testCliApp()
        {
			try {
				$app=ON::CliApp();
			} catch (InvalidArgumentException $notExpected) {
				$this->fail();
			}
			$this->assertTrue(TRUE);
		}
	}
