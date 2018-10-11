<?php
    use Oraculum\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testDefineTmpDir()
    {
        Request::defineTmpDir('/tmp/');
        $result = TMP_DIR;
        $this->assertEquals('/tmp/', $result);
    }

    public function testPost1()
    {
        $_POST['testPost1'] = 'valor-testPost';
        $result = Request::post('testPost1');
        $this->assertEquals('valor-testPost', $result);
    }

    public function testPost2()
    {
        $_POST['testPost2'] = '2valor-testPost';
        $result = Request::post('testPost2', 'n');
        $this->assertEquals(2, $result);
    }

    public function testPost3()
    {
        $_POST['testPost3'] = 'valor-testPost';
        $result = Request::post('testPost3', 'n');
        $this->assertNull($result);
    }

    public function testPost4()
    {
        $result = Request::post('testPost4');
        $this->assertNull($result);
    }

    public function testGet1()
    {
        $_GET['testGet1'] = 'valor-testGet';
        $result = Request::get('testGet1');
        $this->assertEquals('valor-testGet', $result);
    }

    public function testGet2()
    {
        $_GET['testGet2'] = '2valor-testGet';
        $result = Request::get('testGet2', 'n');
        $this->assertEquals(2, $result);
    }

    public function testGet3()
    {
        $_GET['testGet3'] = 'valor-testGet';
        $result = Request::get('testGet3', 'n');
        $this->assertNull($result);
    }

    public function testGet4()
    {
        $result = Request::get('testGet4');
        $this->assertNull($result);
    }

    public function testPostUtf8Decode()
    {
        $_POST['postUtf8'] = utf8_encode('valor-postUtf8-ã');
        $result = Request::postUtf8Decode();
        $result = $_POST['postUtf8'];
        $this->assertEquals('valor-postUtf8-ã', $result);
    }

    public function testPostUtf8Encode()
    {
        $_POST['postUtf8'] = 'valor-postUtf8-ã';
        $result = Request::postUtf8Encode();
        $result = $_POST['postUtf8'];
        $this->assertEquals('valor-postUtf8-ã', $result);
    }

    public function testgetUtf8Decode()
    {
        $_GET['getUtf8'] = utf8_encode('valor-getUtf8-ã');
        $result = Request::getUtf8Decode();
        $result = $_GET['getUtf8'];
        $this->assertEquals('valor-getUtf8-ã', $result);
    }

    public function testgetUtf8Encode()
    {
        $_GET['getUtf8'] = 'valor-getUtf8-ã';
        $result = Request::getUtf8Encode();
        $result = $_GET['getUtf8'];
        $this->assertEquals('valor-getUtf8-ã', $result);
    }

    public function testFile1()
    {
        $_FILES['file1']['file'] = 'testFile';
        $_FILES['file1']['error'] = 0;
        $result = Request::file('file1');
        $this->assertEquals(['file'=>'testFile', 'error'=>0], $result);
    }

    public function testFile2()
    {
        $_FILES['file2']['file'] = 'testFile2';
        $_FILES['file2']['error'] = 0;
        $result = Request::file('file2', 'file');
        $this->assertEquals('testFile2', $result);
    }

    public function testFile3()
    {
        $_FILES['file3']['file'] = 'testFile2';
        $_FILES['file3']['error'] = 1;
        $result = Request::file('file3', 'file');
        $this->assertFalse($result);
    }

    public function testFile4()
    {
        $_FILES['file3']['file'] = 'testFile2';
        $_FILES['file3']['error'] = 1;
        $result = Request::file('', 'file');
        $this->assertNull($result);
    }

    public function testGetSess1()
    {
        $_SESSION['getSess1'] = 'testGetSess';
        $result = Request::getSess('getSess1');
        $this->assertEquals('testGetSess', $result);
    }

    public function testGetSess2()
    {
        $result = Request::getSess('getSess2');
        $this->assertNull($result);
    }

    public function testSetSess()
    {
        Request::setSess('getSess', 'testSetSess');
        $result = $_SESSION['getSess'];
        $this->assertEquals('testSetSess', $result);
    }

    public function testUnsetSess()
    {
        $_SESSION['unsetSess'] = 'testGetSess';
        Request::unsetSess('unsetSess');
        $result = isset($_SESSION['unsetSess']);
        $this->assertFalse($result);
    }

    public function testInitSess()
    {
        try {
            Request::initSess();
        } catch (InvalidArgumentException $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    /**
     * @runInSeparateProcess
     */
    public function testCookie1()
    {
        $_COOKIE['testCookie1'] = 'Cookie1';
        $result = Request::getCookie('testCookie1');
        $this->assertEquals('Cookie1', $result);
    }

    public function testGetPage()
    {
        $_SERVER['REQUEST_URI'] = 'http://localhost/page/produtos';
        $result = Request::getPage();
        $this->assertEquals('produtos', $result);
    }

    public function testGetLast()
    {
        $_SERVER['REQUEST_URI'] = 'a/b/c/d/e/f?g';
        $result = Request::getLast();
        $this->assertEquals('g', $result);
    }

    public function testGetVar()
    {
        $_SERVER['REQUEST_URI'] = 'a/b/c/d/e/f?g';
        $result = Request::getVar('c');
        $this->assertEquals('d', $result);
    }

    public function testGetVarDefault()
    {
        $_SERVER['REQUEST_URI'] = 'a/b/c/d/e/f?g';
        $result = Request::getVar('teste', 'padrao');
        $this->assertEquals('padrao', $result);
    }

    public function testGets()
    {
        $_SERVER['REQUEST_URI'] = 'a/b/c/d/e/f?g';
        $result = Request::gets();
        $gets = ['a', 'b', 'c', 'd', 'e', 'f', 'g'];
        $this->assertEquals($gets, $result);
    }

    public function testRequest()
    {
        $_SERVER['REQUEST_URI'] = 'testRequest';
        $result = Request::request();
        $this->assertEquals($_SERVER['REQUEST_URI'], $result);
    }

    public function testRequestMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'testRequestMethod';
        $result = Request::requestMethod();
        $this->assertEquals($_SERVER['REQUEST_METHOD'], $result);
    }

    public function testReferer()
    {
        $_SERVER['HTTP_REFERER'] = 'testReferer';
        $result = Request::referer();
        $this->assertEquals($_SERVER['HTTP_REFERER'], $result);
    }

    public function testGetAction()
    {
        $_SERVER['HTTP_REFERER'] = 'testReferer';
        $result = Request::referer();
        $this->assertEquals($_SERVER['HTTP_REFERER'], $result);
    }
}
