<?php

namespace Oraculum;

class CliApp
{
    private $defaulttext = null;
    private $errortext = null;
    private $methods = [];

    public function __construct()
    {
        App::checkDebug();
    }

    public function addMethod($metodo, $funcao, $info = null)
    {
        $this->methods[strtolower($metodo)] = $funcao;
        //if (!is_null($info)):
        $this->infos[$metodo] = $info;
        //endif;
    }

    public function start($argv)
    {
        $cmd = (is_array($argv) ? $argv : []);
        if (isset($cmd[1])) :
                $method = strtolower($cmd[1]);
        if (isset($cmd[2])) :
                    $par = $cmd[2];
        endif;
        if (isset($this->methods[$method])) :
                if (isset($par)) :
                    return $this->methods[$method]($par); else :
                    return $this->methods[$method]();
        endif; elseif ($method == 'help') :
                return $this->help(); else :
                return $this->errortext;
        endif; else :
            return $this->defaulttext;
        endif;
    }

    public function setDefaultText($text)
    {
        $this->defaulttext = $text;

        return $this;
    }

    public function setErrorText($text)
    {
        $this->errortext = $text;

        return $this;
    }

    public function help()
    {
        $maxlen = 0;
        $help = 'Metodos disponiveis:'."\n\t";
        foreach ($this->methods as $metodo => $funcao) :
                $len = strlen($metodo);
        if ($len > $maxlen) :
                $maxlen = $len;
        endif;
        endforeach;
        if (isset($this->infos)):
            foreach ($this->infos as $metodo => $info) :
                    $help .= '- '.$metodo;
        if (!is_null($info)) :
                        $help .= str_repeat('.', ($maxlen - strlen($metodo)));
        $help .= ': '.$info;
        endif;
        $help .= "\n\t";
        endforeach;
        endif;

        return $help;
    }
}
