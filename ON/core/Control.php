<?php

namespace Oraculum;

class Control
{
    public function __construct()
    {
        if (!defined('CONTROL_DIR')) :
            define('CONTROL_DIR', 'controls');
        endif;
        if (!defined('ERROR_PAGE')) :
            define('ERROR_PAGE', '404');
        endif;
    }

    public function loadPage($page = null, $url = null)
    {
        if (is_null($page)) :
            throw new Exception('[Error '.__METHOD__.'] Pagina nao informada');
        else :
            $pagefile = CONTROL_DIR.'/pages/'.$page.'.php';
            $urlfile = CONTROL_DIR.'/pages/'.$url.'.php';
            $errorpage = CONTROL_DIR.'/pages/'.ERROR_PAGE.'.php';
            if ($page == '') :
                $class = ucwords($url).'Controller';
            else :
                $class = ucwords($page).'Controller';
            endif;
            if (file_exists($urlfile)) :
                include_once $urlfile;
            elseif (file_exists($pagefile)) :
                include_once $pagefile;
            elseif (file_exists($errorpage)) :
                //header('HTTP/1.1 404 Not Found');
                include_once $errorpage;
            else :
                header('HTTP/1.1 404 Not Found');

                throw new Exception('[Error '.__METHOD__.'] Pagina nao encontrada ('.$pagefile.') ');
            endif;
            if (class_exists($class)) :
                new $class();
            endif;
        endif;

        return $this;
    }

    public static function loadHelper($helper = null)
    {
        if (is_null($helper)) :
            throw new Exception('[Error '.__METHOD__.'] Helper nao informado');
        else :
            $helperfile = CONTROL_DIR.'/helpers/'.$helper.'.php';
            if (file_exists($helperfile)) :
                include_once $helperfile;
            else :
                throw new Exception('[Error '.__METHOD__.'] Helper nao encontrado ('.$helperfile.') ');
            endif;
        endif;
    }
}
