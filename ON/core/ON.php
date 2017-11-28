<?php

namespace ON;

class ON
{
    private static $_instance = null;

    public function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)):
                self::setInstance(new self());
        endif;

        return self::$_instance;
    }

    public static function setInstance(self $instance)
    {
        self::$_instance = $instance;
    }

    public static function App()
    {
        $instance = self::getInstance();

        return new App();
    }

    public static function CliApp()
    {
        $instance = self::getInstance();

        return new CliApp();
    }
}
