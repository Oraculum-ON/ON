<?php

namespace Oraculum;

class FrontController
{
    private $defaulturl = null;

    public function __construct()
    {
    }

    public function setBaseUrl($url)
    {
        if (!defined('URL')) :
            define('URL', $url);
        $gets = Request::gets();
        $base = count(explode('/', URL));
        $base = (($base > 1) ? ($base - 2) : $base);
        $base = (count(explode('/', URL)) - 2);
        $base = (strpos($gets[$base], '.php') ? $base + 2 : $base);
        define('BASE', $base);
        endif;

        return $this;
    }

    public function setDefaultPage($url)
    {
        $this->defaulturl = $url;

        return $this;
    }

    public function setErrorPage($url)
    {
        if (!defined('ERROR_PAGE')) :
                define('ERROR_PAGE', $url);
        endif;

        return $this;
    }

    public function start()
    {
        $request = Request::request();
        if ((URL != '') && (URL != '/')) :
            $url = str_ireplace(URL, '', $request); else :
            $url = $request;
        endif;
        if (!defined('ACTION_URL')) :
            define('ACTION_URL', $url);
        endif;

        $action = Request::getAction();
        $gets = Request::gets();
        if (isset($gets[(BASE) + 1])) :
            $page = $gets[(BASE) + 1]; else :
            $page = $this->defaulturl;
        endif;
        if ($page == '') :
            $page = $this->defaulturl;
        endif;
        App::loadControl()->loadPage($page, $url);
    }
}
