<?php

namespace Oraculum;

class Exception extends \Exception
{	
    public function __construct($message, $code = null)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return 'Code: '.$this->getCode().
                   '<br />Message: '.htmlentities($this->getMessage());
    }

    public function getException()
    {
        return $this;
    }

    public static function getStaticException($exception)
    {
        return $exception->getException();
    }

    public static function showException($error)
    {
        $filecode = null;
        $message = $error->getMessage();
        $code = $error->getCode();
        $file = $error->getFile();
        $line = $error->getLine();
        $trace = $error->getTrace();
        $report = '<h1>'.$message.'</h1>';
        $report .= '<strong>Error Code:</strong> #'.$code.'<br />';
        $report .= '<strong>Source File:</strong> '.$file.' <strong>line '.$line.'</strong><br />';
        $report .= '<h3>Backtrace</h3>';
        foreach ($trace as $error) :
            if (isset($error['file'])) :
                $filecode = $error['file'];
                $report .= '<hr />';
                $report .= '<strong>File:</strong> '.$filecode.' <strong>line '.$error['line'].'</strong><br />';
            else :
                    $filecode = null;
            endif;
                $args = [];
            if (isset($error['args'])) :
                foreach ($error['args'] as $arg) :
                    if (is_object($arg)) :
                        $args[] = get_class($arg).' object';
                    elseif (is_array($arg)) :
                                $args[] = implode(',', $arg);
                    else :
                                    $args[] = (string) $arg;
                    endif;
                endforeach;
            endif;

            if (isset($error['class'])) :
                $report .= '<span style="color:#0a0;">'.$error['class'].'</span>';
            endif;
            if (isset($error['type'])) :
                $report .= '<font style="color:#000;">'.$error['type'].'</font>';
            endif;
                $report .= '<strong style="color:#00a;">'.$error['function'].'</strong>';
                $report .= '(<font style="color:#a00;">\''.implode(', ', $args).'\'</font>);';

            if (!is_null($filecode)) :
                if (file_exists($filecode)) :
                    $cod = 'sourcecodedebug'.time().rand();
                    $report .= '<br /><a href="#alert"'.$cod.'" onclick="document.getElementById(\''.$cod.'\').style.display=\'block\';" style="color:#00a;">';
                    $report .= 'Show Source Code</a>';
                    $report .= '<div id="'.$cod.'" style="display:none;border:1px solid #444;background-color:#fff; word-wrap:break-word;">'.highlight_file($filecode, true).'</div><br />';
                endif;
            endif;
        endforeach;
        $report = '<div style=\'float:left;text-align:left;\'>'.$report.'</div>';
        Exception::alert($report);
    }

    public static function showError($code = null, $message = null, $file = null, $line = null, $context = null)
    {
        $report = '<h1>'.$message.'</h1>';
        $report .= '<strong>Error Code:</strong> #'.$code.'<br />';
        $report .= '<strong>Source File:</strong> '.$file.' <strong>line '.$line.'</strong><br />';
        $report .= '<h3>Backtrace</h3>';
        $report = '<div style=\'float:left;text-align:left;\'>'.$report.'</div>';
        Exception::alert($report);
    }
    public static function alert($mensagem)
    {
        $cod = time().rand();
        echo '<div id="alert'.$cod.'" style="';
        echo 'border:2px solid #456abc; background-color:#ffffe7; color:#000000; ';
        echo 'margin:auto; width:75%; margin-top:10px; text-align:center; ';
        echo "padding:10px; padding-top:0px; font-family: 'Times New Roman', ";
        echo "serif; font-style:italic;\">\n";
        echo '<div style="float:right; width:100%; text-align:right; ';
        echo "clear:both;\">\n";
        echo '<a href="#alert'.$cod.'" onclick="';
        echo "document.getElementById('alert".$cod."').style.display='none';\" ";
        echo 'style="color: #aa0000; font-size: 1em; text-decoration: none;" ';
        echo 'title="Fechar">x</a></div>';
        echo "\n".utf8_decode($mensagem)."\n";
        echo "</div>\n";
    }

    public static function stop()
    {
        restore_exception_handler();
        restore_error_handler();
    }

    public static function start()
    {
        set_exception_handler(['Oraculum\Exception', 'showException']);
        set_error_handler(['Oraculum\Exception', 'showError']);
    }

    public static function displayErrors()
    {
        ini_set('display_errors', true);
        error_reporting(E_ALL | E_STRICT);
    }
}
