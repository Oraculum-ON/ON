<?php
    use Oraculum\Http;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
	public function testRedirect()
	{
		$result = Http::redirect('testRedirect');
		$this->assertContains('Location: testRedirect', xdebug_get_headers());
	}
    /**
     * @runInSeparateProcess
     */
	public function testRedirectNull()
	{
		$result = Http::redirect(null);
		$this->assertNull($result);
	}

    public function testIp()
    {
        $_SERVER['REMOTE_ADDR'] = 'testIP';
        $result = Http::ip();
        $this->assertEquals('testIP', $result);
    }

    public function testHost()
    {
        $_SERVER['REMOTE_HOST'] = 'testHost';
        $result = Http::host();
        $this->assertEquals($_SERVER['REMOTE_HOST'], $result);
    }
	
    public function testHostNull()
    {
        $_SERVER['REMOTE_ADDR'] = 'testIP';
        $_SERVER['REMOTE_HOST'] = '';
        $result = Http::host();
        $this->assertEquals('testIP', $result);
    }

    public function testReferer()
    {
        $_SERVER['HTTP_REFERER'] = 'testReferer';
        $result = Http::referer();
        $this->assertEquals($_SERVER['HTTP_REFERER'], $result);
    }
}
