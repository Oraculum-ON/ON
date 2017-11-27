<?php
	use ON\Request;

	class RequestTest extends PHPUnit_Framework_TestCase
	{
		public function testDefineTmpDir()
        {
			Request::defineTmpDir('tests');
			$result=TMP_DIR;
			$this->assertEquals('tests', $result);
		}
		
		public function testPost()
        {
			$_POST['testPost']='valor-testPost';
			$result=Request::post('testPost');
			$this->assertEquals('valor-testPost', $result);
		}

		public function testGet()
        {
			$_GET['testGet']='valor-testGet';
			$result=Request::get('testGet');
			$this->assertEquals('valor-testGet', $result);
		}
	  

		public function testPostUtf8Decode()
        {
			$_POST['postUtf8']=utf8_encode('valor-postUtf8-ã');
			$result=Request::postUtf8Decode();
			$result=$_POST['postUtf8'];
			$this->assertEquals('valor-postUtf8-ã', $result);
		}
	  
		public function testPostUtf8Encode()
        {
			$_POST['postUtf8']='valor-postUtf8-ã';
			$result=Request::postUtf8Encode();
			$result=$_POST['postUtf8'];
			$this->assertEquals('valor-postUtf8-ã', $result);
		}
	  
		public function testgetUtf8Decode()
        {
			$_GET['getUtf8']=utf8_encode('valor-getUtf8-ã');
			$result=Request::getUtf8Decode();
			$result=$_GET['getUtf8'];
			$this->assertEquals('valor-getUtf8-ã', $result);
		}
	  
		public function testgetUtf8Encode()
        {
			$_GET['getUtf8']='valor-getUtf8-ã';
			$result=Request::getUtf8Encode();
			$result=$_GET['getUtf8'];
			$this->assertEquals('valor-getUtf8-ã', $result);
		}

		public function testFile()
        {
			$_FILES['file1']['file']='testFile';
			$_FILES['file1']['error']=0;
			$result=Request::file('file1');
			$this->assertEquals(array('file'=>'testFile', 'error'=>0), $result);
		}
		public function testFile2()
        {
			$_FILES['file2']['file']='testFile2';
			$_FILES['file2']['error']=0;
			$result=Request::file('file2', 'file');
			$this->assertEquals('testFile2', $result);
		}

		public function testGetSess()
        {
			$_SESSION['getSess']='testGetSess';
			$result=Request::getSess('getSess');
			$this->assertEquals('testGetSess', $result);
		}

		public function testSetSess()
        {
			Request::setSess('getSess', 'testSetSess');
			$result=$_SESSION['getSess'];
			$this->assertEquals('testSetSess', $result);
		}

		public function testUnsetSess() {
			$_SESSION['unsetSess']='testGetSess';
			Request::unsetSess('unsetSess');
			$result=isset($_SESSION['unsetSess']);
			$this->assertFalse($result);
		}

		public function testInitSess() {
			try {
				Request::initSess();
			} catch (InvalidArgumentException $notExpected) {
				$this->fail();
			}
			$this->assertTrue(TRUE);
		}

		public function testSetCookie()
        {
			//Request::setCookie('getSess', 'testSetSess');
			//$result=$_COOKIE['getSess'];
			//$this->assertEquals('testSetSess', $result);
		}

		public function testGetCookie()
        {
			$_COOKIE['testGetCookie']='getCookie';
			$result=Request::getCookie('testGetCookie');
			$this->assertEquals('getCookie', $result);
		}

		public function testGetPagina()
        {
			$_SERVER['REQUEST_URI']='http://localhost/page/produtos';
			$result=Request::getPagina();
			$this->assertEquals('produtos', $result);
		}

		public function testGetId()
        {
			$_SERVER['REQUEST_URI']='page/editar/11';
			$result=Request::getId();
			$this->assertEquals(11, $result);
		}
		
		public function testGetId2()
        {			
			$_SERVER['REQUEST_URI']='page/11/editar';
			$result=Request::getId(null, 2);
			$this->assertEquals(11, $result);
		}

		public function testGetLast()
        {
			$_SERVER['REQUEST_URI']='a/b/c/d/e/f?g';
			$result=Request::getLast();
			$this->assertEquals('g', $result);
		}

		public function testGetVar()
        {
			$_SERVER['REQUEST_URI']='a/b/c/d/e/f?g';
			$result=Request::getVar('c');
			$this->assertEquals('d', $result);
		}

		public function testGetVarDefault()
        {
			$_SERVER['REQUEST_URI']='a/b/c/d/e/f?g';
			$result=Request::getVar('teste', 'padrao');
			$this->assertEquals('padrao', $result);
		}
		
		public function testGets()
        {
			$_SERVER['REQUEST_URI']='a/b/c/d/e/f?g';
			$result=Request::gets();
			$gets=array('a', 'b', 'c', 'd', 'e', 'f', 'g');
			$this->assertEquals($gets, $result);
		}
		
		public function testRequest()
        {
			$_SERVER['REQUEST_URI']='testRequest';
			$result = Request::request();
			$this->assertEquals($_SERVER['REQUEST_URI'], $result);
		}
		
		public function testRequestMethod()
        {
			$_SERVER['REQUEST_METHOD']='testRequestMethod';
			$result = Request::requestMethod();
			$this->assertEquals($_SERVER['REQUEST_METHOD'], $result);
		}
		
		public function testReferer()
        {
			$_SERVER['HTTP_REFERER']='testReferer';
			$result = Request::referer();
			$this->assertEquals($_SERVER['HTTP_REFERER'], $result);
		}
	}