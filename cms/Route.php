<?php namespace Cms;

use Cms\Request;
use Cms\Response;
use Cms\Exception\NotFoundException;
use Cms\Exception\MethodNotFoundException;

class Route{

    // Храним все маршруты
    protected $routes = [];
    
    // Паттерны
    public $patterns = array(
		':any' 		=> '[^/]+',
		':num' 		=> '[0-9]+',
		':all' 		=> '.*'
	);
    
    
    protected $groupAttr;
    
    protected $middleware;
    
    public function __construct() {}
    
    public function get($uri, $action)
    {
        $this->setRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->setRoute('POST', $uri, $action);
    }


    public function put($uri, $action)
    {
        $this->setRoute('PUT', $uri, $action);

    }

    public function patch($uri, $action)
    {
        $this->setRoute('PATCH', $uri, $action);

    }


    public function delete($uri, $action)
    {
        $this->setRoute('DELETE', $uri, $action);

    }

    public function options($uri, $action)
    {
        $this->setRoute('OPTIONS', $uri, $action);

    }
    
    
    public function group(array $attr, \Closure $callback){
        
        $groupAttr = $this -> groupAttr;
        
        
        $this -> groupAttr = $attr;
        
        call_user_func($callback, $this);
        
        $this -> groupAttr = $groupAttr;

    }
    
    
    public function middleware($middleware, \Closure $callback){
        $this -> middleware[$middleware] = $callback;
    }
     
     
    protected function parseAction($action)
    {
        if (is_string($action)) {
            return ['use' => $action];
        } elseif (! is_array($action)) {
            return [$action];
        }
		return $action;
    }
    
    protected function setRoute($method, $uri, $action)
    {
		
        $action = $this->parseAction($action);

		if(!$action instanceOf Closure){
            $action = $this -> addNamespace($this -> addMiddleware($action));
        }
		
        if (isset($this->groupAttr)) {
            if(!isset($this->groupAttr['prefix'])){
				$this->groupAttr['prefix'] = ['/'];
			}
			
			
			if(!is_array($this->groupAttr['prefix'])){
				$this->groupAttr['prefix'] = [$this->groupAttr['prefix']];
			}
			
			$prefix_data = [];
			
			if (isset($this->groupAttr['prefix'])) {
                foreach($this->groupAttr['prefix'] as $prefix_url){
					$prefix_data[] = trim(trim( $prefix_url, '/').'/'.trim($uri, '/'), '/');
				}
            }
        }
		
		foreach($prefix_data  as $http){
			$uri = $http === '/' ? $http : '/'.trim($http, '/');
			$this->routes[$method.$uri] = [ 'method' => $method, 'action' => $action];
		}
    }
    
    
    public function dispatch(){
        
		$request = new Request();
		
        if(is_array($this->routes)){
            
            $searches = array_keys($this -> patterns);
		    $replaces = array_values($this -> patterns);

            foreach($this->routes as $uri => $routes){
			
				if($request -> getMethod() != $routes['method']){
					continue;
				}
                if($uri == $routes['method'].$this -> getPathInfo()){
					return $this -> call($routes['action']);
                }
                
                if(strpos($uri, ':') !== false) {
                    $uri = str_replace($searches, $replaces, $uri);
                }
                
                if(preg_match('#^' . $uri . '$#', $routes['method'].$this -> getPathInfo(), $matched)) {
                    return $this -> call($routes['action']);
                }
            }
        }
		throw new NotFoundException;
    }
  
  
  
    protected function callController($className, $methodController, $params)
    {

        $controller = new $className();
        
        if (! in_array(strtolower($methodController), array_map('strtolower', get_class_methods($controller)))) {
            throw new MethodNotFoundException($methodController, $controller);
        }
        $response = call_user_func_array([$controller, $methodController], $params);
		
		//if(is_string($response)){
			//return Response::create($response) -> sendContent();
		//}
		return $response;
    }
    
    
    protected function call($callback, $params = array())
    {
		
		if(isset($callback['middleware'])){
			if(isset($callback['middleware']) && is_array($callback['middleware'])){
				foreach($callback['middleware'] as $before){
					call_user_func_array($this -> middleware[$before], []);
				}
				
			} 
		}
		
		
		if(isset($callback['use'])){
			$callback = $callback['use'];
		}
		
		
		
		
		
		
        if (is_object($callback)) {
            call_user_func_array($callback, $params);
            return true;
        }

        list($controller, $methodController) = explode('@', $callback);

        if (class_exists($controller)) {
            return $this -> callController($controller, $methodController, $params);
        }

        echo 'Class Not Found';
    }
    

	protected function addNamespace($action)
    {
        if (isset($this->groupAttr['namespace']) && isset($action['use'])) {
            $action['use'] = $this->groupAttr['namespace'].'\\'.$action['use'];
        }

        return $action;
    }
	
	
	protected function addMiddleware($action)
    {
        if (isset($this->groupAttr['middleware'])) {
            if (isset($action['middleware'])) {
                $action['middleware'] = array_merge($this->groupAttr['middleware'], $action['middleware']);
            } else {
                $action['middleware'] = $this->groupAttr['middleware'];
            }
        }

        return $action;
    }
	
	
	
    public function getPathInfo()
    {
        $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

        return '/'.trim(str_replace('?'.$query, '', $_SERVER['REQUEST_URI']), '/');
    }

}