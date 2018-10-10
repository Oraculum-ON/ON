<?php

namespace Oraculum;

class Request
{
    public static function defineTmpDir($dir = '/tmp/')
    {
        if (!defined('TMP_DIR')) :
            define('TMP_DIR', $dir);
        endif;
    }

    // Receber variaveis por $_POST
    public static function post($indice, $tipo = 's')
    {
        if ((isset($_POST[$indice])) && ($_POST[$indice] != '')) :
            $valor = $_POST[$indice];
        if ($tipo != 'h') :
                $valor = strip_tags($valor);
        $valor = htmlentities($valor, ENT_SUBSTITUTE, 'utf-8');
        endif;
        if ($tipo == 'n') :
                $valor = floor($valor);
        if ($valor == 0) :
                    $valor = null;
        endif;
        endif; else :
            $valor = null;
        endif;

        return $valor;
    }

    // Receber variaveis por $_GET
    public static function get($indice, $tipo = 's')
    {
        if ((isset($_GET[$indice])) && ($_GET[$indice] != '')) :
            $valor = $_GET[$indice];
        $valor = strip_tags($valor);
        $valor = htmlentities($valor, ENT_SUBSTITUTE, 'utf-8');
        $valor = addslashes($valor);
        if ($tipo == 'n') :
                $valor = floor($valor);
        if ($valor == 0) :
                        $valor = null;
        endif;
        endif; else :
            $valor = null;
        endif;

        return $valor;
    }

    // Desconvertendo para UTF8 valores passados por $_POST
    public static function postUtf8Decode()
    {
        foreach ($_POST as $key => $value) :
            if (mb_detect_encoding($value) == 'UTF-8') :
                $_POST[$key] = utf8_decode($value);
        endif;
        endforeach;
    }

    // Convertendo para UTF8 valores passados por $_POST
    public static function postUtf8Encode()
    {
        foreach ($_POST as $key => $value) :
            if (mb_detect_encoding($value) != 'UTF-8') :
                $_POST[$key] = utf8_encode($value);
        endif;
        endforeach;
    }

    // Desconvertendo para UTF8 valores passados por $_GET
    public static function getUtf8Decode()
    {
        foreach ($_GET as $key => $value) :
            if (mb_detect_encoding($value) == 'UTF-8') :
                $_GET[$key] = utf8_decode($value);
        endif;
        endforeach;
    }

    // Convertendo para UTF8 valores passados por $_GET
    public static function getUtf8Encode()
    {
        foreach ($_GET as $key => $value) :
            if (mb_detect_encoding($value) != 'UTF-8') :
                $_GET[$key] = utf8_encode($value);
        endif;
        endforeach;
    }

    // Receber variaveis por $_FILES
    public static function file($indice, $atributo = null)
    {
        if ((isset($_FILES[$indice])) && ($_FILES[$indice] != '')) :
            $file = $_FILES[$indice];
        if ($file['error'] != 0) :
                return false; elseif (is_null($atributo)) :
                return $file; else :
                return $file[$atributo];
        endif;
        endif;
    }

    // Capturar sessao
    public static function getSess($index)
    {
        return (isset($_SESSION[$index])) ?
                $_SESSION[$index] :
                null;
    }

    // Gravar sessao
    public static function setSess($indice, $valor)
    {
        if ((isset($indice)) && (isset($valor))) :
            $_SESSION[$indice] = $valor;

        return true; else :
            return false;
        endif;
    }

    // Remover variavel de sessao
    public static function unsetSess($indice)
    {
        if (isset($indice)) :
            unset($_SESSION[$indice]);

        return true; else :
            return false;
        endif;
    }

    // Inicializar sessao
    public static function initSess()
    {
        self::defineTmpDir();
        if (!isset($_SESSION)) :
            if (ini_get('session.save_path') != TMP_DIR) :
                session_save_path(TMP_DIR);
        endif;
        session_start();
        endif;
    }

    // Gravar cookie
    public static function setCookie($nome, $valor, $expirar = null)
    {
        $expirar = (is_null($expirar)) ? (time() + 604800) : $expirar;
        $return = \setcookie($nome, $valor, $expirar);

        return $return;
    }

    // Ler cookie
    public static function getCookie($index)
    {
        return (isset($_COOKIE[$index])) ?
                $_COOKIE[$index] :
                null;
    }

    // Capturar pagina
    public static function getPage()
    {
        return self::getVar('page');
    }

    // Capturar ultimo parametro
    public static function getLast($gets = null)
    {
        if (is_null($gets)) :
            $gets = self::gets();
        endif;

        return $gets[count($gets) - 1];
    }

    // Capturar parametro
    public static function getVar($index = 1, $default = null)
    {
        $gets = self::gets();
        if (is_string($index)) :
            if (($key = array_search($index, $gets)) === false) :
                return $default;
        endif;
        $index = $key + 2;
        endif;
        $index = (int) $index - 1;

        return isset($gets[$index]) ? $gets[$index] : $default;
    }

    // Capturar URI
    public static function gets()
    {
        $request = self::request();

        return explode('/', str_replace('?', '/', $request));
    }

    public static function getAction()
    {
        if (defined('ACTION_URL')) :
            return explode('/', str_replace('?', '/', ACTION_URL)); else :
            return self::gets();
        endif;
    }

    public static function request()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function referer()
    {
        return (isset($_SERVER['HTTP_REFERER'])) ?
                $_SERVER['HTTP_REFERER'] :
                null;
    }
}
