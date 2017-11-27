<?php
	use ON\HTTP;

	class HTTPTest extends PHPUnit_Framework_TestCase
    {
		public function testRedirect()
        {
			//$result = HTTP::redirect('testRedirect');
			//$this->assertContains('Location: testRedirect', xdebug_get_headers());
		}

		// Capturar endereco IP
		public function testIp()
        {
			$_SERVER['REMOTE_ADDR']='testIP';
			$result = HTTP::ip();
			$this->assertEquals($_SERVER['REMOTE_ADDR'], $result);
		}

		// Capturar HOST
		public function testHost()
        {
			$_SERVER['REMOTE_HOST']='testHost';
			$result = HTTP::host();
			$this->assertEquals($_SERVER['REMOTE_HOST'], $result);
		}

		// Capturar Request URL
		public function testReferer()
        {
			$_SERVER['HTTP_REFERER']='testReferer';
			$result = HTTP::referer();
			$this->assertEquals($_SERVER['HTTP_REFERER'], $result);
		}
	}
