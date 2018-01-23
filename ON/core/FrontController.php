<?php

namespace ON;

class FrontController
{
    private $_defaulturl = null;
    private $_errorpage = null;

    public function __construct()
    {
    }

    public function setBaseUrl($url)
    {
        if (!defined('URL')):
                define('URL', $url);
        $gets = Request::gets();
        $base = count(explode('/', URL));
		if ($base > 1):
			$base = $base - 2;
		endif;
        $base = (count(explode('/', URL)) - 2);
        $base = strpos($gets[$base], '.php') ? $base + 2 : $base;
        define('BASE', $base);
        endif;

        return $this;
    }

    public function setDefaultPage($url)
    {
        $this->_defaulturl = $url;

        return $this;
    }

    public function setErrorPage($url)
    {
        if (!defined('ERRORPAGE')):
                define('ERRORPAGE', $url);
        endif;

        return $this;
    }

    public function start()
    {
        $request = Request::request();
        if((URL != '') && (URL != '/')):
            $url = str_ireplace(URL, '', $request);
        else:
            $url = $request;
        endif;
        if (!defined('ACTION_URL')):
            define('ACTION_URL', $url);
        endif;
        
        $action = Request::getAction();
        $gets = Request::gets();
        if (isset($gets[(BASE) + 1])):
            $page = $gets[(BASE) + 1]; else:
            $page = $this->_defaulturl;
        endif;
        if ($action == ''):
                $action = $this->_defaulturl;
        endif;
        if ($page == ''):
                $page = $this->_defaulturl;
        endif;
        App::loadControl()->loadPage($page, $url);
    }


		public function setBaseUrlOld($url) {
			if (!defined('URL')):
				define('URL', $url);
				$gets=Request::gets();
				$base=(count(explode('/', URL))-2);
				$base=strpos($gets[$base], '.php')?$base+2:$base;
				define('BASE', $base);
			endif;
			return $this;
		}
		public function startOld() {
			//Oraculum::Load('Request');
			$request=Request::request();
			$url=str_ireplace(URL, '', $request);
			$gets=Request::gets();
			if(isset($gets[(BASE)+1])):
				$page=$gets[(BASE)+1];
			else:
				$page=$this->_defaulturl;
				//throw new Exception('[Erro CGFC36] Nao foi possivel determinar a pagina atraves da URL');
			endif;
			if($url==''):
				$url=$this->_defaulturl;
			endif;
			if($page==''):
				$page=$this->_defaulturl;
			endif;
			App::loadControl()->loadPage($page, $url);
		}
}
