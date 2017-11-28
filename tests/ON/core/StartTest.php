<?php
	use ON\Start;
	use PHPUnit\Framework\TestCase;

    class StartTest extends TestCase
    {
		public function testStart(){
			try {
				define('URL', 'http://localhost/');
				define('BASE', '/');
				define('CONTROL_DIR', 'tests');
				$app=new Start();
				$app->setDefaultPage('home');
				@$app->start();
				$app->setBaseUrl('/ON/exemplo/');
				$app->setDefaultPage('home');
				$app->setErrorPage('404');
				@$app->start();
			} catch (InvalidArgumentException $notExpected) {
				$this->fail();
			}
			$this->assertTrue(TRUE);
		}
	}
