<?php
use Oraculum\Control;
use PHPUnit\Framework\TestCase;
class ControlTest extends TestCase
{
	/**
	 * @runInSeparateProcess
	 */
    public function testLoadPage()
    {
        $this->expectException('Oraculum\Exception');
		$control=new Control();
		$control->loadPage('home');
    }
	/**
	 * @runInSeparateProcess
	 */
    public function testLoadPageError1()
    {
        $this->expectException('Oraculum\Exception');
		$control=new Control();
		$control->loadPage('not-found');
    }
	/**
	 * @runInSeparateProcess
	 */
    public function testLoadPageError2()
    {
        $this->expectException('Oraculum\Exception');
		$control=new Control();
		$control->loadPage(null);
    }
	/**
	 * @runInSeparateProcess
	 */
    public function testLoadPageError3()
    {
        $this->expectException('Oraculum\Exception');
		$control=new Control();
		$control->loadPage('', 'Teste');
    }
	/**
	 * @runInSeparateProcess
	 */
    public function testLoadPageError4()
    {
        define('CONTROL_DIR', 'tests/assets');
		$control=new Control();
		$result=$control->loadPage('teste1', 'teste2');
		$this->assertInstanceOf(Oraculum\Control::class, $result);
    }
	/**
	 * @runInSeparateProcess
	 */
    public function testLoadHelper()
    {
        define('CONTROL_DIR', 'tests/assets');
		$control=new Control();
		$result=$control->loadHelper('helper');
		$this->assertNull($result);
    }
	/**
	 * @runInSeparateProcess
	 */
    public function testLoadHelperError1()
    {
        $this->expectException('Oraculum\Exception');
		$control=new Control();
		$control->loadHelper(null);
    }
	/**
	 * @runInSeparateProcess
	 */
    public function testLoadHelperError2()
    {
        $this->expectException('Oraculum\Exception');
		$control=new Control();
		$control->loadHelper('not-found');
    }
}
