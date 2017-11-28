<?php
	use ON\HTTP;
	use PHPUnit\Framework\TestCase;

	class HTTPTest extends TestCase
    {

		public function testIp()
        {
			$_SERVER['REMOTE_ADDR']='testIP';
			$result = HTTP::ip();
			$this->assertEquals($_SERVER['REMOTE_ADDR'], $result);
		}

		public function testHost()
        {
			$_SERVER['REMOTE_HOST']='testHost';
			$result = HTTP::host();
			$this->assertEquals($_SERVER['REMOTE_HOST'], $result);
		}

		public function testReferer()
        {
			$_SERVER['HTTP_REFERER']='testReferer';
			$result = HTTP::referer();
			$this->assertEquals($_SERVER['HTTP_REFERER'], $result);
		}
	}
