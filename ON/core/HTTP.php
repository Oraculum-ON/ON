<?php

namespace Oraculum;

class HTTP
{
    // Redirecionar
    public static function redirect($url)
    {
        if (isset($url)) :
            header('Location: '.$url);
        endif;
    }

    // Capturar endereco IP
    public static function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    // Capturar HOST
    public static function host()
    {
        $host = isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : null;
        if ((is_null($host)) || ($host == '')) {
            $host = self::ip();
        }

        return $host;
    }

    // Capturar Request URL
    public static function referer()
    {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

        return $referer;
    }
}
