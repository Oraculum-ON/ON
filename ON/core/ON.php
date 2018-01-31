<?php

namespace Oraculum;

class On
{
    private static $instance = null;

    public function __construct()
    {
    }

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

    public static function app()
    {
        self::getInstance();

        return new App();
    }

    public static function cliApp()
    {
        self::getInstance();

        return new CliApp();
    }
}
