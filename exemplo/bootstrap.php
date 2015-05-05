<?php
    use ON\Alias;
    use ON\Exception as Exception;

	define('DS', DIRECTORY_SEPARATOR);
	define('PATH', getcwd().DS.'..'.DS.'ON'.DS);
    define('ON_DEBUG', TRUE);
	include(PATH.'autoload.php');

	Alias::loadAlias('Request');

    Exception::displayErrors();
    Exception::start();