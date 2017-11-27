<?php
	use ON\Routes;
	
	class RoutesTest extends PHPUnit_Framework_TestCase
    {
		public function testAdd() {
			$_SERVER['REQUEST_URI']='http://localhost/origem/teste';
			Routes::add('origem', 'destino');
			$result = $_SERVER['REQUEST_URI'];
			$this->assertEquals('http://localhost/destino/teste', $result);
		}
	}