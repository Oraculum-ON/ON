<?php
    namespace ON;

    use ON\Crypt;
    use ON\HTTP;
    use ON\Request;

    class Auth
    {
        private $_sessname='ofauth';
        private $_logoffurl=NULL;
        private $_loginurl=NULL;
        private $_homeurl=NULL;
        private $_dbobj=NULL;
        private $_keyfield=NULL;
        private $_userfield=NULL;
        private $_passwordfield=NULL;
        private $_emailfield=NULL;
        private $_cryptkeyfield=NULL;
        private $_user=NULL;
        private $_password=NULL;
        private $_email=NULL;
        private $_cryptkey=NULL;
        private $_crypttype='md5';
        private $_crypttypes=array('md5', 'sha1', 'blowfish');
        private $_key=NULL;
        private $_register=NULL;
        private $_fields=array();

        public function __construct()
        {
            if (!defined('URL')):
                define('URL','');
            endif;
            $this->_logoffurl=URL.'logoff';
            $this->_loginurl=URL.'login';
        }
        
        public function setSess($sess)
        {
            $this->_sessname=$sess;
        }
        public function getSess()
        {
            return $this->_sessname;
        }
        
        public function setLogoffURL($url)
        {
            $this->_logoffurl=$url;
        }
        
        public function setLoginURL($url)
        {
            $this->_loginurl=$url;
        }
        
        public function setHomeURL($url)
        {
            $this->_homeurl=$url;
        }
        
        public function verify($redirect=FALSE)
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

        public static function logoff()
        {
            Request::initSess();
            session_unset();
            $_SESSION=array();
            session_destroy();
            session_regenerate_id(true);
            HTTP::redirect(URL);
            exit;
        }

        /* DB Autentication */
        public function setDbAutentication($dbobj=NULL)
        {
            if(!is_null($dbobj)):
                $this->_dbobj=$dbobj;
            endif;
        }
        
        public function setDbKeyField($keyfield=NULL)
        {
            if(!is_null($keyfield)):
                $this->_keyfield=$keyfield;
            endif;
        }
        
        public function setDbUserField($userfield=NULL)
        {
            if(!is_null($userfield)):
                $this->_userfield=$userfield;
            endif;
        }
        
        public function setDbPasswordField($passwordfield=NULL)
        {
            if(!is_null($passwordfield)):
                $this->_passwordfield=$passwordfield;
            endif;
        }
        
        public function setDbEmailField($emailfield=NULL)
        {
            if(!is_null($emailfield)):
                $this->_emailfield=$emailfield;
            endif;
        }
        
        public function setDbCryptkeyField($cryptkeyfield=NULL)
        {
            if(!is_null($cryptkeyfield)):
                $this->_cryptkeyfield=$cryptkeyfield;
            endif;
        }
        
        public function setUser($user=NULL)
        {
            if(!is_null($user)):
                $this->_user=$user;
            endif;
        }
        
        public function setPassword($pass=NULL)
        {
            if(!is_null($pass)):
                $this->_password=$pass;
            endif;
        }
        
        public function setEmail($email=NULL)
        {
            if(!is_null($email)):
                $this->_email=$email;
            endif;
        }
        
        public function setCryptkey($cryptkey=NULL)
        {
            if(!is_null($cryptkey)):
                $this->_cryptkey=$cryptkey;
            endif;
        }
        
        public function dbAuth()
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
        
        public function passwordlessAuth($clearkey=TRUE)
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
        
        public function recordFields($fields=array())
        {
            if(is_array($fields)):
                $this->_fields=$fields;
            else:
                throw new Exception ('Campos que serao gravados em sessao devem ser informados em um vetor');
            endif;
        }
        
        public function recordSession($redirect=FALSE)
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
        
        public function setCrypttype($type)
        {
            if(in_array($type, $this->_crypttypes)):
                $this->_crypttype=$type;
            endif;
        }
    }