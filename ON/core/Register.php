<?php

namespace Oraculum;

class Register
{
    private $vars = [];
    private static $instance = null;

    public static function getInstance()
    {
        if (is_null(self::$instance)) :
            self::setInstance(new self());
        endif;

        return self::$instance;
    }

    public static function setInstance(self $instance)
    {
        self::$instance = $instance;
    }

    public static function set($index, $value)
    {
        $instance = self::getInstance();
        $instance->vars[$index] = $value;
    }

    public static function get($index)
    {
        $instance = self::getInstance();
        if (isset($instance->vars[$index])) :
            return $instance->vars[$index]; else :
            return;
        endif;
    }

    public static function getVars()
    {
        $instance = self::getInstance();
        if (!empty($instance->vars)) :
            return $instance->vars;
        endif;
    }
}
