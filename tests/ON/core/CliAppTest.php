<?php
use Oraculum\CliApp;
use PHPUnit\Framework\TestCase;
class CliAppTest extends TestCase
{
	/**
	 * @runInSeparateProcess
	 */
	public function testConstruct()
	{
		$this->setOutputCallback(function() {});
		$result=new CliApp();
		$this->assertInstanceOf(Oraculum\CliApp::class, $result);
	}
	
	/**
	 * @runInSeparateProcess
	 */
	public function testAddMethod()
	{
		$this->setOutputCallback(function() {});
		$fn=static function() {};
		$app=new CliApp();
		$result=$app->addMethod('testMethod', $fn, 'Teste de funcao CLI');
		$this->assertNull($result);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testStart()
	{
		$this->setOutputCallback(function() {});
		$fn=static function() {};
		$app=new CliApp();
		$app->addMethod('testMethod', $fn, 'Teste de funcao CLI');
		$app->setDefaultText('default-text');
		$app->setErrorText('error-text');
		
		$return=$app->start(array());
		$this->assertEquals('default-text', $return);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testHelp()
	{
		
		$this->setOutputCallback(function() {});
		$fn=static function() {};
		$app=new CliApp();
		$app->addMethod('test', $fn, 'Teste');
		$app->setDefaultText('default-text');
		$app->setErrorText('error-text');
		
		$return=$app->start(array('+', 'help'));
		
		$return=$app->start(array('+', 'help'));			
		//$this->assertNull($return);
		
		$assert='Metodos disponiveis:'."\n\t".'- test: Teste'."\n\t";
		$this->assertEquals($assert, $return);
		
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testErro()
	{
		
		$this->setOutputCallback(function() {});
		$fn=static function() {};
		$app=new CliApp();
		$app->addMethod('testMethod', $fn, 'Teste de funcao CLI');
		$app->setDefaultText('default-text');
		$app->setErrorText('error-text');
		
		$return=$app->start(array('+', 'erro'));
		$this->assertEquals('error-text', $return);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testMethod()
	{
		
		$this->setOutputCallback(function() {});
		$fn=static function() {};
		$app=new CliApp();
		$app->addMethod('testMethod', $fn, 'Teste de funcao CLI');
		$app->setDefaultText('default-text');
		$app->setErrorText('error-text');
		
		$return=$app->start(array('+', 'testMethod'));			
		$this->assertNull($return);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testMethodParam()
	{
		
		$this->setOutputCallback(function() {});
		$fn=static function() {};
		$app=new CliApp();
		$app->addMethod('testMethod', $fn, 'Teste de funcao CLI');
		$app->setDefaultText('default-text');
		$app->setErrorText('error-text');
		
		$return=$app->start(array('+', 'testMethod', 'parametro'));			
		$this->assertNull($return);
	}
}
