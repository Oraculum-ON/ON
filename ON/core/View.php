<?php

namespace Oraculum;

class View
{
    protected $viewsRegister = null;
    protected $template = null;
    protected $templatetype = 'xml';
    protected $pagefile = null;
    protected $templates = [];

    public function __construct($view = null, $template = null)
    {
        if (!defined('VIEW_DIR')) :
            define('VIEW_DIR', 'views');
        endif;
        if (!is_null($view)) :
            if (!is_null($template)) :
                $this->addTemplate($template);
            endif;
            $this->loadPage($view);
        endif;
    }

    public function addTemplate($template = null)
    {
        if (is_null($template)) :
            throw new Exception('[Error '.__METHOD__.'] Template nao informado');
        else :
            $templatefilexml = VIEW_DIR.'/templates/'.$template.'.xml';
            $templatefilephp = VIEW_DIR.'/templates/'.$template.'.php';
            if (file_exists($templatefilexml)) :
                $this->template = simplexml_load_file($templatefilexml);
                $this->templatetype = 'xml';
            elseif (file_exists($templatefilephp)) :
                $this->template = file_get_contents($templatefilephp);
                $this->template = explode('[content]', $this->template);
                $this->templatetype = 'html';
            else :
                throw new Exception('[Error '.__METHOD__.'] Template nao encontrado ('.$template.') ');
            endif;
        endif;

        $this->templates[] = $template;
        return $this;
    }

    public function loadPage($page = null)
    {
        if (is_null($page)) :
            throw new Exception('[Error '.__METHOD__.'] Pagina nao informada');
        else :
            $pagefile = VIEW_DIR.'/pages/'.$page.'.php';
            $class = ucwords($page).'View';
            if (file_exists($pagefile)) :
                if (empty($this->templates)) :
                    include_once $pagefile;
                else :
                    if ($this->templatetype == 'xml') :
                        $this->pagefile = $pagefile;
                        echo $this->renderXML();
                    else :
                        echo $this->template[0];
                        include_once $pagefile;
                        echo $this->template[1];

                        return $this;
                    endif;
                endif;
            else :
                throw new Exception('[Error '.__METHOD__.'] Pagina nao encontrada ('.$pagefile.') ');
            endif;
            if (class_exists($class)) :
                new $class();
            endif;
        endif;

        return $this;
    }

    private function renderXML()
    {
        foreach ($this->template as $item => $value) :
            if ($item == 'content') :
                include_once $this->pagefile;
            elseif ($item == 'text') :
                echo $value;
            elseif ($item == 'element') :
                $this->loadElement($value);
            endif;
        endforeach;
    }

    public static function loadElement($element = null)
    {
        if (is_null($element)) :
            throw new Exception('[Error '.__METHOD__.'] Elemento nao informado');
        else :
            $elementfile = VIEW_DIR.'/elements/'.$element.'.php';
            if (file_exists($elementfile)) :
                include_once $elementfile;
            else :
                throw new Exception('[Error '.__METHOD__.'] Elemento nao encontrado ('.$elementfile.') ');
            endif;
        endif;
    }
}
