<?php
use Oraculum\Auth;
use Oraculum\Model;
use Oraculum\Request;
use Oraculum\Crypt;
use PHPUnit\Framework\TestCase;
class AuthTest extends TestCase
{

	public function testSess()
	{
		$auth = new Auth();
		$auth->setSess('testSess');
		$result=$auth->getSess('testSess');
		$this->assertEquals('testSess', $result);
	}
	
    /**
     * @runInSeparateProcess
     */
	public function testAuth()
	{
            //$db=new Models('mysql');
            //$db->loadDynamicModelClass('users');
			$userdb=new UserFakeDb();
			
            //$users=new ON\Users();
			Request::defineTmpDir('./tests/assets/tmp');
			$auth=new Auth();
			//$auth->setSess('testSess');
            $auth->setDbAutentication($userdb);
            $auth->setDbKeyField('id');
            $auth->setDbUserField('user');
            $auth->setDbPasswordField('senha');
            $auth->setDbEmailField('email');
            $auth->setDbCryptkeyField('cryptkey');
            $auth->setCrypttype('md5');
            $auth->setUser('usuario');
            $auth->setPassword('senha');
            $auth->setEmail('email');
            $auth->setCryptkey('token');
            //if ($auth->DbAuth()):
			$auth->setHomeURL('/');
			$auth->setloginurl('/');
			$auth->setlogoffurl('/sair');
			$auth->setsess('sessao');
			$fields=array('id', 'nome', 'email');
			$auth->RecordFields($fields);
			$auth->RecordSession(TRUE);
			$result=$auth->DbAuth();
			
            $auth->setCrypttype('sha1');
			$result=$auth->DbAuth();
			
            $auth->setCrypttype('blowfish');
			$result=$auth->DbAuth();
			
			$result=$auth->verify();
			//$auth->logoff();
			$this->assertFalse($result);
			//HTTP::redirect(URL);
	}
	public function testRecordFields(){
        $this->expectException('Oraculum\Exception');
		$auth=new Auth();
		$auth->recordFields('campo');
	}
	
		
    /**
     * @runInSeparateProcess
     */
	public function testPasswordlessAuthError()
	{
        $this->expectException('Oraculum\Exception');
		$auth=new Auth();
		$auth->passwordlessAuth();
	}
    /**
     * @runInSeparateProcess
     */
	public function testPasswordlessAuth()
	{
		$userdb=new UserFakeDb();
		$auth=new Auth();
        $auth->setDbAutentication($userdb);
		$auth->setDbCryptkeyField('cryptkey');
		$auth->setDbKeyField('id');
		/*$auth->setDbUserField('user');
		$auth->setDbPasswordField('senha');
		$auth->setDbEmailField('email');
		$auth->setCrypttype('md5');*/
		$auth->setCryptkey('pUIhnKV6BzqlMzqlBwb5BGx5BGx5');
		$result=$auth->passwordlessAuth();
		$this->assertFalse($result);
	}
	
    /**
     * @runInSeparateProcess
     */
	public function testDbAuthError()
	{
        $this->expectException('Oraculum\Exception');
		$auth=new Auth();
		$auth->dbAuth();
	}
	
	/*public function testSetLogoffURL($url)
	{
		$auth = new Auth();
		$auth->etLogoffURL('testSess');
		$result=$auth->getSess('testSess');
		$this->assertEquals('testSess', $result);
		
		$this->_logoffurl=$url;
	}*/
	/*
	
	public function testSetLoginURL($url)
	{
		$this->_loginurl=$url;
	}
	
	public function testSetHomeURL($url)
	{
		$this->_homeurl=$url;
	}
	
	public function testVerify($redirect=FALSE)
	{
		Request::initSess();
		$user=Request::getSess($this->_sessname);
		if(!is_null($user)):
			$ip=Crypt::strDecrypt($user['ip']);
			if($ip!=HTTP::ip()):
				if ($redirect):
					HTTP::redirect($this->_logoffurl);
				endif;
				return false;
			else:
				return true;
			endif;
		else:
			if($redirect):
				HTTP::redirect($this->_logoffurl);
			endif;
			return false;
		endif;
	}

	public static function testLogoff()
	{
		Request::initSess();
		session_unset();
		$_SESSION=array();
		session_destroy();
		session_regenerate_id(true);
		HTTP::redirect(URL);
		exit;
	}

	public function testSetDbAutentication($dbobj=NULL)
	{
		if(!is_null($dbobj)):
			$this->_dbobj=$dbobj;
		endif;
	}
	
	public function testSetDbKeyField($keyfield=NULL)
	{
		if(!is_null($keyfield)):
			$this->_keyfield=$keyfield;
		endif;
	}
	
	public function testSetDbUserField($userfield=NULL)
	{
		if(!is_null($userfield)):
			$this->_userfield=$userfield;
		endif;
	}
	
	public function testSetDbPasswordField($passwordfield=NULL)
	{
		if(!is_null($passwordfield)):
			$this->_passwordfield=$passwordfield;
		endif;
	}
	
	public function testSetDbEmailField($emailfield=NULL)
	{
		if(!is_null($emailfield)):
			$this->_emailfield=$emailfield;
		endif;
	}
	
	public function testSetDbCryptkeyField($cryptkeyfield=NULL)
	{
		if(!is_null($cryptkeyfield)):
			$this->_cryptkeyfield=$cryptkeyfield;
		endif;
	}
	
	public function testSetUser($user=NULL)
	{
		if(!is_null($user)):
			$this->_user=$user;
		endif;
	}
	
	public function testSetPassword($pass=NULL)
	{
		if(!is_null($pass)):
			$this->_password=$pass;
		endif;
	}
	
	public function testSetEmail($email=NULL)
	{
		if(!is_null($email)):
			$this->_email=$email;
		endif;
	}
	
	public function testSetCryptkey($cryptkey=NULL)
	{
		if(!is_null($cryptkey)):
			$this->_cryptkey=$cryptkey;
		endif;
	}
	
	public function testDbAuth()
	{
		if(is_object($this->_dbobj)):
			$userfield='getBy'.ucwords($this->_userfield);
			$passwordfield=$this->_passwordfield;
			$keyfield=$this->_keyfield;
			$obj=$this->_dbobj;
			$register=$obj->$userfield($this->_user);
			if (sizeof($register)==1):
				if ($this->_crypttype=='md5'):
					if ($register->$passwordfield==md5($this->_password)):
						$this->_key=$register->$keyfield;
						$this->_register=$register;
						return true;
					else:
						return false;
					endif;
				elseif ($this->_crypttype=='sha1'):
					if($register->$passwordfield==sha1($this->_password)):
						$this->_key=$register->$keyfield;
						$this->_register=$register;
						return true;
					else:
						return false;
					endif;
				elseif ($this->_crypttype=='blowfish'):
					if (Crypt::blowfishCheck($this->_password, $register->$passwordfield)):
						$this->_key=$register->$keyfield;
						$this->_register=$register;
						return true;
					else:
						return false;
					endif;
				else:
					if($register->$passwordfield==$this->_password):
						$this->_key=$register->$keyfield;
						return true;
					else:
						return false;
					endif;
				endif;
			endif;
		else:
			throw new Exception('Para autenticacao atraves de base de dados deve ser passada uma instancia relacionada a uma entidade do banco');
		endif;
	}
	
	public function testPasswordlessAuth($clearkey=TRUE)
	{
		if(is_object($this->_dbobj)):
			$cryptfield=$this->_cryptkeyfield;
			$getcryptfield='getBy'.ucwords($this->_cryptkeyfield);
			$keyfield=$this->_keyfield;
			$obj=$this->_dbobj;
			$this->_register=$obj->$getcryptfield($this->_cryptkey);
			if(sizeof($this->_register)==1):
				$this->_key=$this->_register->$keyfield;
				$key=Crypt::strDecrypt($this->_cryptkey);
				$key=explode('::', $key);
				$time=$key[0];
				$timeout=$key[2];
				$auth=(time()<$time+$timeout);
				if(($auth)&&($clearkey)):
					$this->_register->$cryptfield=NULL;
					$this->_register->save();
				endif;
				return $auth;
			else:
				return FALSE;
			endif;
		else:
			throw new Exception('Para autenticacao atraves de base de dados deve ser passada uma instancia relacionada a uma entidade do banco');
		endif;
	}
	
	public function testRecordFields($fields=array())
	{
		if(is_array($fields)):
			$this->_fields=$fields;
		else:
			throw new Exception ('Campos que serao gravados em sessao devem ser informados em um vetor');
		endif;
	}
	
	public function testRecordSession($redirect=FALSE)
	{
		if(!is_null($this->_key)):
			$obj=$this->_register;
			foreach ($this->_fields as $field):
				//if (property_exists(get_class($obj),$field)) {
					$user[$field]=Crypt::strCrypt($obj->$field);
				//}
			endforeach;
			$user['ip']=Crypt::strCrypt(HTTP::ip());
			$user['key']=Crypt::strCrypt($this->_key);
			$user['user']=Crypt::strCrypt($this->_user);
			Request::initSess();
			Request::setSess($this->_sessname, $user);
			if($redirect):
				HTTP::redirect($this->_homeurl);
			endif;
			return true;
		else:
			return false;
		endif;
	}
	
	public function testSetCrypttype($type)
	{
		if(in_array($type, $this->_crypttypes)):
			$this->_crypttype=$type;
		endif;
	}*/
}

class UserFake
{
	public $id=1;
	public $user='usuario';
	public $senha='e8d95a51f3af4a3b134bf6bb680a213a';
	public $cryptkey='pUIhnKV6BzqlMzqlBwb5BGx5BGx5';
}

class UserFakeDb
{
	public function __construct()
	{
		return new UserFake();
	}
    public function getByEmail($email)
    {
		return new UserFake();
    }
    public function getByUser($user)
    {
		return new UserFake();
    }
    public function getByCryptkey($key)
    {
		//var_dump(explode('::', Crypt::strDecrypt('pUIhnKV6BzqlMzqlBwb5BGx5BGx5')));
		//var_dump(Crypt::strCrypt('1511964475::teste::9999999'));
		return new UserFake();
    }
}