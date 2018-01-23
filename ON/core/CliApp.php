<?php

namespace ON;

class CliApp
{
    private $_defaulttext = null;
    private $_errortext = null;
    private $_methods = [];

    public function __construct()
    {
        App::checkDebug();
    }

    public function addMethod($metodo, $funcao, $info = null)
    {
        $this->_methods[strtolower($metodo)] = $funcao;
        //if (!is_null($info)):
        $this->_infos[$metodo] = $info;
        //endif;
    }

    public function start($argv)
    {
        $cmd = (is_array($argv) ? $argv : []);
        if (isset($cmd[1])):
                $method = strtolower($cmd[1]);
        if (isset($cmd[2])):
                    $par = $cmd[2];
        endif;
        if (isset($this->_methods[$method])):
                    if (isset($par)):
                        return $this->_methods[$method]($par); else:
                        return $this->_methods[$method]();
        endif; elseif ($method == 'help'):
                    return $this->help(); else:
                    return $this->_errortext;
        endif; else:
                return $this->_defaulttext;
        endif;
    }

    public function setDefaultText($text)
    {
        $this->_defaulttext = $text;

        return $this;
    }

    public function setErrorText($text)
    {
        $this->_errortext = $text;

        return $this;
    }

    public function help()
    {
        $maxlen = 0;
        $help = 'Metodos disponiveis:'."\n\t";
        foreach ($this->_methods as $metodo=>$funcao):
                $len = strlen($metodo);
        if ($len > $maxlen):
                    $maxlen = $len;
        endif;
        endforeach;
        foreach ($this->_infos as $metodo=>$info):
                $help .= '- '.$metodo;
        if (!is_null($info)):
                    $help .= str_repeat('.', ($maxlen - strlen($metodo)));
        $help .= ': '.$info;
        endif;
        $help .= "\n\t";
        endforeach;

        return $help;
    }
}
