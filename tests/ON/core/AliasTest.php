<?php
    use Oraculum\Alias;
use PHPUnit\Framework\TestCase;

class AliasTest extends TestCase
{
	public function testAddAlias()
	{
		Alias::addAlias('b64encode', 'base64_encode');
		$result = b64encode('testAddAlias');
		$this->assertEquals(base64_encode('testAddAlias'), $result);
	}

	public function testLoadAlias()
	{
		try {
			Alias::loadAlias('All');
		} catch (InvalidArgumentException $notExpected) {
			$this->fail();
		}
		$this->assertTrue(true);
	}

	public function testAddAliasError()
	{
		$this->expectException('\Oraculum\Exception');
		Alias::addAlias('b64encode2', 'testAddAliasError');
	}

	public function testAddAliasDuplicate()
	{
		$this->expectException('\Oraculum\Exception');
		Alias::addAlias('post', '\Oraculum\Request::post');
		Alias::addAlias('post', '\Oraculum\Request::post');
	}

	public function testAddAliasExist()
	{
		$this->expectException('\Oraculum\Exception');
		Alias::addAlias('date', '\Oraculum\Request::post');
	}
}