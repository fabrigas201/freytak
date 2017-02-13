<?php namespace Cms;


class Request{
    
    private $url;
    
    public function __construct()
	{
        $this -> url = $_SERVER['REQUEST_URI'];
    }
    
    
    // Вернем сегмент URL
    public function segment($segment)
	{
        
        $urlArray = parse_url( $this -> url, PHP_URL_PATH);
        $segments = explode('/', trim($urlArray, '/'));
		
		if(count($segments)){
			$i = 1;
			$items = array();
			
			foreach($segments as $partUrl){
				$items[$i] = $partUrl;
				$i++;
			}
		}
        if(array_key_exists($segment, $items)){
			return $items[$segment];
        }
    }
    
    // Вернем метод
    public function getMethod()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
        } elseif (isset($_REQUEST['_method'])) {
            $method = $_REQUEST['_method'];
        }

        return strtoupper($method);
    }
    
    
    // проверям на AJAX запрос
    public function isAjax()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            return strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        }
        return false;
    }
	
	public function post($key)
    {
        return array_key_exists($key, $_POST)? $_POST[$key]: null;
    }
	
	public static function get($key)
    {
        return array_key_exists($key, $_GET)? $_GET[$key]: null;
    }
    
    // Проверям на POST запрос
    public static function isPost()
    {
        return $_SERVER["REQUEST_METHOD"] === "POST";
    }

    // Проверям на GET запрос 
    public static function isGet()
    {
        return $_SERVER["REQUEST_METHOD"] === "GET";
    }
  
}