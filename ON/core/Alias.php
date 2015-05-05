<?php
    namespace ON;

    /*
     * Arquivo com funcoes globais que na verdade chamam funcoes de
     * suas respectivas classes
     */
	class Alias
	{
		public static function addAlias($newfunction, $originalfunction)
        {
			if(function_exists($newfunction)):
				throw new Exception('[Error '.__METHOD__.'] A funcao \''.$newfunction.'\' ja existe');
			elseif (!is_callable($originalfunction)):
				throw new Exception('[Error '.__METHOD__.'] A funcao \''.$originalfunction.'\' nao pode ser chamada');
			else:
				eval('function '.$newfunction.'() {
					$args=func_get_args();
					return call_user_func_array(\''.$originalfunction.'\',$args);
				       }');
			endif;
		}
		
		public static function loadAlias($class)
        {
            if (($class=='Request')||($class=='All')):
				/**********************************
				 * Tratamento de requisicoes
				 **********************************/
                //Request::getpagina();
				Alias::addAlias('post', 'ON\Request::post');
				Alias::AddAlias('get', 'ON\Request::get');
				Alias::AddAlias('getSess', 'ON\Request::getSess');
				Alias::AddAlias('setSess', 'ON\Request::setSess');
				Alias::AddAlias('unsetsess', 'ON\Request::unsetSess');
				Alias::AddAlias('init_sess', 'ON\Request::initSess');
                Alias::AddAlias('set_cookie', 'ON\Request::setCookie');
                Alias::AddAlias('cookie', 'ON\Request::setCookie');
                Alias::AddAlias('getpagina', 'ON\Request::getPagina');
                Alias::AddAlias('getid', 'ON\Request::getid');
                Alias::AddAlias('getlast', 'ON\Request::getLast');
                Alias::AddAlias('getvar', 'ON\Request::getVar');
                Alias::AddAlias('gets', 'ON\Request::gets');
			endif;
			if (($class=='Crypt')||($class=='All')):
                /**********************************
                * Tratamento de criptografia
                **********************************/
                Alias::AddAlias('strcrypt', 'ON\Crypt::strcrypt');
                Alias::AddAlias('strdcrypt', 'ON\Crypt::strdcrypt');
                Alias::AddAlias('blowfish', 'ON\Crypt::blowfish');
                Alias::AddAlias('blowfishcheck', 'ON\Crypt::blowfishcheck');
			endif;
			if (($class=='HTTP')||($class=='All')):
                /**********************************
                * Tratamento de parametros HTTP
                **********************************/
                Alias::AddAlias('redirect', 'ON\HTTP::redirect');
                Alias::AddAlias('ip', 'ON\HTTP::ip');
                Alias::AddAlias('host', 'ON\HTTP::host');
			endif;
			if (($class=='Validate')||($class=='All')):
                /**********************************
                * Tratamento de formularios
                **********************************/
                Alias::AddAlias('validar', 'ON\Validate::validar');
                Alias::AddAlias('verificaCPF', 'ON\Validate::verificaCPF');
                Alias::AddAlias('verificaEmail', 'ON\Validate::verificaEmail');
			endif;
			if (($class=='Text')||($class=='All')):
                /**********************************
                * Tratamento de informacoes textuais
                **********************************/
                Alias::AddAlias('moeda', 'ON\Text::moeda');
                Alias::AddAlias('moedaSql', 'ON\Text::moedaSql');
                Alias::AddAlias('data', 'ON\Text::data');
                Alias::AddAlias('dataSql', 'ON\Text::dataSql');
                Alias::AddAlias('hora', 'ON\Text::hora');
                Alias::AddAlias('saudacao', 'ON\Text::saudacao');
                Alias::AddAlias('getpwd', 'ON\Text::getpwd');
                Alias::AddAlias('inflector', 'ON\Text::inflector');
                Alias::AddAlias('plural', 'ON\Text::plural');
			endif;
			if (($class=='Files')||($class=='All')):
				/**********************************
				* Tratamento de inclusao de arquvos
				**********************************/
                Alias::AddAlias('inc', 'ON\Files::inc');
			endif;
			if(($class=='Logs')||($class=='All')):
                /**********************************
                * Tratamento de erros e logs
                **********************************/
                Alias::AddAlias('alert', 'ON\Logs::alert');
			endif;
		}
	}
