<?php
	use ON\Adds;
	
	class AddsTest extends PHPUnit_Framework_TestCase {
		public function testLoad() {
			try {
				Adds::load('datagrid');
			} catch (InvalidArgumentException $notExpected) {
			  $this->fail();
			}
			$this->assertTrue(TRUE);
		}
	}