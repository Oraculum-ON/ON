<?php

namespace Oraculum;

use Oraculum\Request;

class Router
{
    private $base = '';
    private $defaultMethod = '';
    private $routes = array();
    private $rules = [
        'i'  => '^\d+$',
        'a'  => '[0-9A-Za-z]++',
        'c'  => '^[a-zA-Z]+$'
    ];
    private $requestUrl = '';
    private $requestMethod = 'GET';
    
    public function __construct($base = null)
    {
        if (!is_null($base)) :
            $this->setBase($base);
        endif;
    }
    
    public function setBase($base)
    {
        $this->base=trim($base);
    }
    
    public function get($pattern, $callback)
    {
        $this->map('GET', $pattern, $callback);
    }
    
    public function post($pattern, $callback)
    {
        $this->map('POST', $pattern, $callback);
    }
    
    public function put($pattern, $callback)
    {
        $this->map('PUT', $pattern, $callback);
    }
    
    public function patch($pattern, $callback)
    {
        $this->map('PATCH', $pattern, $callback);
    }
    
    public function delete($pattern, $callback)
    {
        $this->map('DELETE', $pattern, $callback);
    }
    
    private function map($method, $pattern, $callback)
    {
        $pattern=$this->base.'/'.trim($pattern);
        $this->routes[$method][$pattern] = $callback;
    }

    public function setDefault($defaultMethod)
    {
        $this->defaultMethod=$defaultMethod;
    }

    public function run()
    {
        $this->requestUrl = trim((defined('ACTION_URL')) ? ACTION_URL : Request::request());
        $this->requestMethod = Request::requestMethod();
        $method = Request::requestMethod();
        if (($strpos = strpos($this->requestUrl, '?')) !== false) {
            $this->requestUrl = substr($this->requestUrl, 0, $strpos);
        }
        $routes=$this->routes[$this->requestMethod];
        $exec=$this->searchPattern($routes);
        if (!$exec) :
            $this->requestUrl = $this->requestUrl.'/'.$this->defaultMethod;
            $url=$this->base.'/'.$this->defaultMethod;
            if (isset($routes[$url])) :
                $pars = $this->complexMatch($this->requestUrl, $url);
                if ($pars) :
                    $exec=$this->call($routes[$url], $pars);
                else :
                    $exec=$this->call($routes[$url]);
                endif;
            endif;
        endif;
        return $exec;
    }
    
    private function searchPattern($routes)
    {
        foreach ($routes as $url => $call) :
            if (($strpos = strpos($url, '$')) !== false) :
                $pars = $this->complexMatch($this->requestUrl, $url);
                if ($pars) :
                    return $this->call($call, $pars);
                endif;
            endif;
            if (($this->requestUrl == '/') && ($url == '/')) :
                return $this->call($call);
                break;
            elseif (trim($this->requestUrl, '/') == trim($url, '/')) :
                return $this->call($call);
                break;
            endif;
        endforeach;
        return false;
    }
    
    private function call($callback, $params = array())
    {
        if (is_callable($callback)) :
            call_user_func_array($callback, $params);
            return true;
        else :
            return false;
        endif;
    }
    
    private function complexMatch($url, $pattern)
    {
        $url = trim($url, '/');
        $pattern = trim($pattern, '/');
        $url = explode('/', $url);
        $pattern = explode('/', $pattern);
        if (count($url) != count($pattern)) :
            return false;
        endif;
        $params = [];
        for ($i = 0; $i < count($url); $i++) {
            $u = $url[$i];
            $p = $pattern[$i];
            if (0 === strpos($p, '$')) :
                if (($strpos = strpos($p, ':')) !== false) :
                    if ($this->regMatch($u, $p) == false) :
                        return false;
                    else :
                        $temp = explode(':', $p);
                        $params[ltrim($temp[0], '$')] = $u;
                    endif;
                else :
                    $params[ltrim($p, '$')] = $u;
                endif;
            else :
                if ($u != $p) :
                    return false;
                endif;
            endif;
        }
        return $params;
    }
    private function regMatch($value, $pattern)
    {
        $pattern = explode(':', $pattern);
        if (sizeof($pattern) > 0):
            $regex = $pattern[1];
            if (array_key_exists($regex, $this->rules)) :
                $regex = $this->rules[$regex];
            endif;
            return preg_match("/$regex/", $value);
        else:
            return false;
        endif;
    }

    public static function addAlias($origem, $destino)
    {
        $request = Request::request();
        $_SERVER['REQUEST_URI'] = str_replace($origem, $destino, $request);
    }
}
