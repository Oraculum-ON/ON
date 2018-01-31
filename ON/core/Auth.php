<?php

namespace Oraculum;

class Auth
{
    private $sessname = 'ofauth';
    private $logoffurl = null;
    private $loginurl = null;
    private $homeurl = null;
    private $dbobj = null;
    private $keyfield = null;
    private $userfield = null;
    private $passwordfield = null;
    private $emailfield = null;
    private $cryptkeyfield = null;
    private $user = null;
    private $password = null;
    private $email = null;
    private $cryptkey = null;
    private $crypttype = 'md5';
    private $crypttypes = ['md5', 'sha1', 'blowfish'];
    private $key = null;
    private $register = null;
    private $fields = [];

    public function __construct()
    {
		$url = ((defined('URL')) ? URL : '/');
        $this->logoffurl = $url.'logoff';
        $this->loginurl = $url.'login';
    }

    public function setSess($sess)
    {
        $this->sessname = $sess;
    }

    public function getSess()
    {
        return $this->sessname;
    }

    public function setLogoffURL($url)
    {
        $this->logoffurl = $url;
    }

    public function setLoginURL($url)
    {
        $this->loginurl = $url;
    }

    public function setHomeURL($url)
    {
        $this->homeurl = $url;
    }

    public function verify($redirect = false)
    {
        Request::initSess();
        $user = Request::getSess($this->sessname);
        if (!is_null($user)) : 
                $ip = Crypt::strDecrypt($user['ip']);
            if ($ip != Http::ip()) :
                if ($redirect) :
                    Http::redirect($this->logoffurl);
                endif;

                return false;
            else :
                return true;
            endif;
        else :
            if ($redirect) :
                Http::redirect($this->logoffurl);
            endif;

            return false;
        endif;
    }

    public static function logoff()
    {
        Request::initSess();
        session_unset();
        $_SESSION = [];
        session_destroy();
        session_regenerate_id(true);
		$url = ((defined('URL')) ? URL : '/');
        Http::redirect($url);
        exit;
    }

    /* DB Autentication */
    public function setDbAutentication($dbobj = null)
    {
        if (!is_null($dbobj)) :
            $this->dbobj = $dbobj;
        endif;
    }

    public function setDbKeyField($keyfield = null)
    {
        if (!is_null($keyfield)) :
            $this->keyfield = $keyfield;
        endif;
    }

    public function setDbUserField($userfield = null)
    {
        if (!is_null($userfield)) :
            $this->userfield = $userfield;
        endif;
    }

    public function setDbPasswordField($passwordfield = null)
    {
        if (!is_null($passwordfield)) :
            $this->passwordfield = $passwordfield;
        endif;
    }

    public function setDbEmailField($emailfield = null)
    {
        if (!is_null($emailfield)) :
            $this->emailfield = $emailfield;
        endif;
    }

    public function setDbCryptkeyField($cryptkeyfield = null)
    {
        if (!is_null($cryptkeyfield)) :
            $this->cryptkeyfield = $cryptkeyfield;
        endif;
    }

    public function setUser($user = null)
    {
        if (!is_null($user)) :
                $this->user = $user;
        endif;
    }

    public function setPassword($pass = null)
    {
        if (!is_null($pass)) :
            $this->password = $pass;
        endif;
    }

    public function setEmail($email = null)
    {
        if (!is_null($email)) :
            $this->email = $email;
        endif;
    }

    public function setCryptkey($cryptkey = null)
    {
        if (!is_null($cryptkey)) :
            $this->cryptkey = $cryptkey;
        endif;
    }

    public function dbAuth()
    {
        if (is_object($this->dbobj)) :
            $userfield = 'getBy'.ucwords($this->userfield);
            $passwordfield = $this->passwordfield;
            $keyfield = $this->keyfield;
            $obj = $this->dbobj;
            $register = $obj->$userfield($this->user);
            if (count($register) == 1) :
                if ($this->crypttype == 'md5') :
                    if ($register->$passwordfield == md5($this->password)) :
                        $this->key = $register->$keyfield;
                        $this->register = $register;

                        return true;
                    else :
                        return false;
                    endif;
                elseif ($this->crypttype == 'sha1') :
                    if ($register->$passwordfield == sha1($this->password)) :
                        $this->key = $register->$keyfield;
                        $this->register = $register;

                        return true;
                    else :
                        return false;
                    endif;
                elseif ($this->crypttype == 'blowfish') :
                    if (Crypt::blowfishCheck($this->password, $register->$passwordfield)) :
                        $this->key = $register->$keyfield;
                        $this->register = $register;

                        return true;
                    else :
                        return false;
                    endif;
                else :
                    if ($register->$passwordfield == $this->password) :
                        $this->key = $register->$keyfield;

                        return true;
                    else :
                        return false;
                    endif;
                endif;
            endif;
        else :
            throw new Exception('Para autenticacao atraves de base de dados deve ser passada uma instancia relacionada a uma entidade do banco');
        endif;
    }

    public function passwordlessAuth($clearkey = true)
    {
        if (is_object($this->dbobj)) :
                $cryptfield = $this->cryptkeyfield;
            $getcryptfield = 'getBy'.ucwords($this->cryptkeyfield);
            $keyfield = $this->keyfield;
            $obj = $this->dbobj;
            $this->register = $obj->$getcryptfield($this->cryptkey);
            if (count($this->register) == 1) :
                    $this->key = $this->register->$keyfield;
                $key = Crypt::strDecrypt($this->cryptkey);
                $key = explode('::', $key);
                $time = $key[0];
                $timeout = $key[2];
                $auth = (time() < $time + $timeout);
                if (($auth) && ($clearkey)) :
                        $this->register->$cryptfield = null;
                    $this->register->save();
                endif;

                return $auth;
            else :
                return false;
            endif;
        else :
            throw new Exception('Para autenticacao atraves de base de dados deve ser passada uma instancia relacionada a uma entidade do banco');
        endif;
    }

    public function recordFields($fields = [])
    {
        if (is_array($fields)) :
                $this->fields = $fields;
        else :
            throw new Exception('Campos que serao gravados em sessao devem ser informados em um vetor');
        endif;
    }

    public function recordSession($redirect = false)
    {
        if (!is_null($this->key)) :
            $obj = $this->register;
            foreach ($this->fields as $field) :
                $user[$field] = Crypt::strCrypt($obj->$field);
            endforeach;
            $user['ip'] = Crypt::strCrypt(Http::ip());
            $user['key'] = Crypt::strCrypt($this->key);
            $user['user'] = Crypt::strCrypt($this->user);
            Request::initSess();
            Request::setSess($this->sessname, $user);
            if ($redirect) :
                Http::redirect($this->homeurl);
            endif;

            return true;
        else :
            return false;
        endif;
    }

    public function setCrypttype($type)
    {
        if (in_array($type, $this->crypttypes)) :
            $this->crypttype = $type;
        endif;
    }
}
