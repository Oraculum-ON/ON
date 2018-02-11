<?php

namespace Oraculum;

class Auth
{
    private $sessName = 'ofauth';
    private $logoffUrl = null;
    private $loginUrl = null;
    private $homeurl = null;
    private $dbObj = null;
    private $keyField = null;
    private $passwordField = null;
    private $emailField = null;
    private $cryptKeyField = null;
    private $user = null;
    private $password = null;
    private $email = null;
    private $cryptkey = null;
    private $cryptType = 'md5';
    private $cryptTypes = ['md5', 'sha1', 'blowfish'];
    private $key = null;
    private $register = null;
    private $fields = [];
    private $userFields = [];
    private $statusField = null;
    private $statusValue = null;

    public function __construct()
    {
		$url = ((defined('URL')) ? URL : '/');
        $this->logoffUrl = $url.'logoff';
        $this->loginUrl = $url.'login';
    }

    public function setSess($sess)
    {
        $this->sessName = $sess;
    }

    public function getSess()
    {
        return $this->sessName;
    }

    public function setLogoffURL($url)
    {
        $this->logoffUrl = $url;
    }

    public function setLoginURL($url)
    {
        $this->loginUrl = $url;
    }

    public function setHomeURL($url)
    {
        $this->homeUrl = $url;
    }

    public function verify($redirect = false)
    {
        Request::initSess();
        $user = Request::getSess($this->sessName);
        if (!is_null($user)) : 
            $ip = Crypt::strDecrypt($user['ip']);
            if ($ip != Http::ip()) :
                if ($redirect) :
                    Http::redirect($this->logoffUrl);
                endif;

                return false;
            else :
                return true;
            endif;
        else :
            if ($redirect) :
                Http::redirect($this->logoffUrl);
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
    public function setDbAutentication($dbObj = null)
    {
        if (!is_null($dbObj)) :
            $this->dbObj = $dbObj;
        endif;
    }

    public function setDbKeyField($keyField = null)
    {
        if (!is_null($keyField)) :
            $this->keyField = $keyField;
        endif;
    }

    public function setDbUserField($userField = null)
    {
        if (!is_null($userField)) :
            $this->setDbUserFields([$userField]);
        endif;
    }

    public function setDbUserFields($userFields = [])
    {
        if (!empty($userFields)) :
            $this->userFields = $userFields;
        endif;
    }

    public function setDbPasswordField($passwordField = null)
    {
        if (!is_null($passwordField)) :
            $this->passwordField = $passwordField;
        endif;
    }

    public function setDbEmailField($emailField = null)
    {
        if (!is_null($emailField)) :
            $this->emailField = $emailField;
        endif;
    }

    public function setDbStatusField($statusField = null)
    {
        if (!is_null($statusField)) :
            $this->statusField = $statusField;
        endif;
    }

    public function setDbStatusValue($statusValue = null)
    {
        if (!is_null($statusValue)) :
            $this->statusValue = $statusValue;
        endif;
    }

    public function setDbCryptkeyField($cryptKeyField = null)
    {
        if (!is_null($cryptKeyField)) :
            $this->cryptKeyField = $cryptKeyField;
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

    public function setCryptkey($cryptKey = null)
    {
        if (!is_null($cryptKey)) :
            $this->cryptKey = $cryptKey;
        endif;
    }

    public function dbAuth($debug = false)
    {
        $found = false;
        if ((is_object($this->dbObj))&&
            (!empty($this->userFields))) :
            $obj = $this->dbObj;
            foreach ($this->userFields as $userField):
                $filter = 'getBy'.ucwords($userField);
                $reg = $obj->$filter($this->user);
                $found=(count($reg) == 1);
                if ($found) :
                    break 1;
                endif;
            endforeach;
            $passwordfield = $this->passwordField;
            $keyField = $this->keyField;
            if ($found) :
                if ((!is_null($this->statusField)&&
                    (!is_null($this->statusValue)))):
                        $statusField = $this->statusField;
                    if ($reg->$statusField != $this->statusValue):
                        return false;
                    endif;
                endif;
                if ($this->cryptType == 'md5') :
                    if ($reg->$passwordfield == md5($this->password)) :
                        $this->key = $reg->$keyField;
                        $this->register = $reg;

                        return true;
                    else :
                        return false;
                    endif;
                elseif ($this->cryptType == 'sha1') :
                    if ($register->$passwordfield == sha1($this->password)) :
                        $this->key = $reg->$keyField;
                        $this->register = $reg;

                        return true;
                    else :
                        return false;
                    endif;
                elseif ($this->cryptType == 'blowfish') :
                    if (Crypt::blowfishCheck($this->password, $reg->$passwordField)) :
                        $this->key = $reg->$keyField;
                        $this->register = $reg;

                        return true;
                    else :
                        return false;
                    endif;
                else :
                    if ($register->$passwordField == $this->password) :
                        $this->key = $register->$keyField;

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
        if (is_object($this->dbObj)) :
            $cryptField = $this->cryptKeyField;
            $getCryptField = 'getBy'.ucwords($this->cryptKeyField);
            $keyField = $this->keyField;
            $obj = $this->dbobj;
            $this->register = $obj->$getCryptField($this->cryptKey);
            if (count($this->register) == 1) :
                $this->key = $this->register->$keyField;
                $key = Crypt::strDecrypt($this->cryptkey);
                $key = explode('::', $key);
                $time = $key[0];
                $timeout = $key[2];
                $auth = (time() < $time + $timeout);
                if (($auth) && ($clearkey)) :
                        $this->register->$cryptField = null;
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
            Request::setSess($this->sessName, $user);
            if ($redirect) :
                Http::redirect($this->homeUrl);
            endif;

            return true;
        else :
            return false;
        endif;
    }

    public function setCrypttype($type)
    {
        if (in_array($type, $this->cryptTypes)) :
            $this->cryptType = $type;
        endif;
    }
}
