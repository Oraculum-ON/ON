<?php

namespace Oraculum;

/*
 * Arquivo com funcoes globais que na verdade chamam funcoes de
 * suas respectivas classes
 */
class Alias
{
    public static function addAlias($newfunction, $originalfunction)
    {
        if (function_exists($newfunction)) :
            throw new Exception('[Error '.__METHOD__.'] A funcao \''.$newfunction.'\' ja existe'); elseif (!is_callable($originalfunction)) :
            throw new Exception('[Error '.__METHOD__.'] A funcao \''.$originalfunction.'\' nao pode ser chamada'); else :
            eval(
                'function '.$newfunction.'() {
				$args=func_get_args();
				return call_user_func_array(\''.$originalfunction.'\',$args);
				   }'
            );
        endif;
    }

    public static function loadRequest()
    {
        self::addAlias('post', 'Oraculum\Request::post');
        self::AddAlias('get', 'Oraculum\Request::get');
        self::AddAlias('getSess', 'Oraculum\Request::getSess');
        self::AddAlias('setSess', 'Oraculum\Request::setSess');
        self::AddAlias('unsetsess', 'Oraculum\Request::unsetSess');
        self::AddAlias('init_sess', 'Oraculum\Request::initSess');
        self::AddAlias('set_cookie', 'Oraculum\Request::setCookie');
        self::AddAlias('cookie', 'Oraculum\Request::setCookie');
        self::AddAlias('getpage', 'Oraculum\Request::getPage');
        self::AddAlias('getlast', 'Oraculum\Request::getLast');
        self::AddAlias('getvar', 'Oraculum\Request::getVar');
        self::AddAlias('gets', 'Oraculum\Request::gets');
    }

    public static function loadCrypt()
    {
        self::AddAlias('strcrypt', 'Oraculum\Crypt::strCrypt');
        self::AddAlias('strdecrypt', 'Oraculum\Crypt::strDecrypt');
        self::AddAlias('blowfish', 'Oraculum\Crypt::blowfish');
        self::AddAlias('blowfishcheck', 'Oraculum\Crypt::blowfishcheck');
    }

    public static function loadHttp()
    {
        self::AddAlias('redirect', 'Oraculum\HTTP::redirect');
        self::AddAlias('ip', 'Oraculum\HTTP::ip');
        self::AddAlias('host', 'Oraculum\HTTP::host');
    }

    public static function loadAlias($class)
    {
        if (($class == 'Request') || ($class == 'All')) :
            self::loadRequest();
        endif;
        if (($class == 'Crypt') || ($class == 'All')) :
            self::loadCrypt();
        endif;
        if (($class == 'HTTP') || ($class == 'All')) :
            self::loadHttp();
        endif;
    }
}
