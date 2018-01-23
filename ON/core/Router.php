<?php
use ON\Request;
namespace ON;

class Router
{
	private $_base = '';
	private $_default = '';
	private $_routes = array();
    private $_rules = [
        'i'  => '^\d+$',
        'a'  => '[0-9A-Za-z]++',
        'c'  => '^[a-zA-Z]+$'
    ];
    private $_requestUrl = '';
    private $_requestMethod = 'GET';
	
    public function __construct($base=null)
    {
        if (!is_null($base)):
            $this->setBase($base);
        endif;
    }
	
    public function setBase($base)
    {
		$this->_base=trim($base);
    }
	
	public function get($pattern, $callback) {
        $this->map('GET', $pattern, $callback);
	}
	
	public function post($pattern, $callback) {
        $this->map('POST', $pattern, $callback);
	}
	
	public function put($pattern, $callback) {
        $this->map('PUT', $pattern, $callback);
	}
	
	public function patch($pattern, $callback) {
        $this->map('PATCH', $pattern, $callback);
	}
	
	public function delete($pattern, $callback) {
        $this->map('DELETE', $pattern, $callback);
	}
	
	private function map($method, $pattern, $callback) {
        $pattern=$this->_base.'/'.trim($pattern);
        $this->_routes[$method][$pattern] = $callback;
	}

	public function setDefault($default){
		$this->_default=$default;
		$this->_default=$default;
	}

	public function run() {
        $this->_requestUrl = trim((defined('ACTION_URL')) ? ACTION_URL : Request::request());
        $this->_requestMethod = Request::requestMethod();
        $method = Request::requestMethod();
        if (($strpos = strpos($this->_requestUrl, '?')) !== false) {
            $this->_requestUrl = substr($this->_requestUrl, 0, $strpos);
        }
        $routes=$this->_routes[$this->_requestMethod];
        $exec=$this->searchPattern($routes);
        if (!$exec):
            $this->_requestUrl = $this->_requestUrl.'/'.$this->_default;
            $url=$this->_base.'/'.$this->_default;
            if (isset($routes[$url])):
                $pars = $this->complexMatch($this->_requestUrl, $url);
                if ($pars):
                    $exec=$this->call($routes[$url], $pars);
                else:
                    $exec=$this->call($routes[$url]);
                endif;
            endif;
        endif;
        return $exec;
    }
    
    private function searchPattern($routes){
        foreach($routes as $url=>$call):
            if (($strpos = strpos($url, '$')) !== false):
                $pars = $this->complexMatch($this->_requestUrl, $url);
                if ($pars):
                    return $this->call($call, $pars);
                endif;
            endif;
            if(($this->_requestUrl == '/') && ($url == '/')):
                return $this->call($call);
                break;
            elseif(rtrim($this->_requestUrl, '/') == rtrim($url, '/')):
                return $this->call($call);
                break;
            endif;
        endforeach;
        return false;
    }
    
    private function call($callback, $params=array()){
        if(is_callable($callback)){
            call_user_func_array($callback, $params);
            return true;
        } else {
            return false;
        }
    }
	
	private function complexMatch($url, $pattern) {
        $url = trim($url, '/' );
        $pattern = trim($pattern, '/' );
        $url_pieces = explode('/', $url );
        $pattern_pieces = explode('/', $pattern );
        if( count($url_pieces) != count($pattern_pieces) ):
            return false;
        endif;
        $params = [];
        for( $i = 0; $i < count($url_pieces); $i++ ){
            $u = $url_pieces[$i];
            $p = $pattern_pieces[$i];
            if ( 0 === strpos($p, '$') ):
                if (($strpos = strpos($p, ':')) !== false){
                    if( $this->regMatch( $u, $p ) == false )
                        return false;
                    else {
                        $temp = explode( ':', $p );
                        $params[ltrim($temp[0], '$')] = $u;
                    }
                }
                else {
                    $params[ltrim($p, '$')] = $u;
                }
            else:
                if( $u != $p )
                    return false;
            endif;
        }
        return $params;
    }
    private function regMatch( $value, $pattern ){
        $pieces = explode( ':', $pattern );
        if( count( $pieces ) <= 1 )
            return false;
        $regex = $pieces[1];
        if (array_key_exists($regex, $this->rules)) {
            $regex = $this->rules[$regex];
        }
        return preg_match( "/$regex/", $value );
    }
}
