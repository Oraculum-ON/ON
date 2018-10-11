<?php

namespace Oraculum;

class App
{
    public function __construct()
    {
        header('X-Powered-By: ON Framework');
        self::checkDebug();
    }

    public static function loadView($view = null, $template = null)
    {
        return new View($view, $template);
    }

    public static function loadControl()
    {
        return new Control();
    }

    public static function loadModel($model)
    {
        return new Model($model);
    }

    public function view()
    {
        return new View();
    }

    public function control()
    {
        return new Control();
    }

    public function model()
    {
        return new Model();
    }

    public function setControlDir($dir)
    {
        if (file_exists($dir)) {
            define('CONTROL_DIR', $dir);
        } else {
            throw new Exception('[Error '.__METHOD__.'] Diretorio nao encontrado ('.$dir.')');
        }
    }

    public function setViewDir($dir)
    {
        if (file_exists($dir)) {
            define('VIEW_DIR', $dir);
        } else {
            throw new Exception('[Error '.__METHOD__.'] Diretorio nao encontrado ('.$dir.')');
        }
    }

    public function setModelDir($dir)
    {
        if (file_exists($dir)) :
            define('MODEL_DIR', $dir); else :
            throw new Exception('[Error '.__METHOD__.'] Diretorio nao encontrado ('.$dir.')');
        endif;
    }

    public function frontController()
    {
        return new FrontController();
    }

    public static function checkDebug()
    {
        if ((defined('ON_DEBUG')) && (ON_DEBUG)) :
            Exception::displayErrors();
        Exception::start(); else :
            ini_set('display_errors', false);
        endif;
    }
}
