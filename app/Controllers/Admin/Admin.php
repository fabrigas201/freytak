<?php namespace App\Controllers\Admin;

use Cms\Controller;
use Cms\Request;
use Cms\Auth;
use Cms\Exception\MethodNotFoundException;

class Admin extends Controller{
    
    
	public function index(){
		
		$auth = new Auth();
		$request = new Request();
		
		
		if($request -> isPost()){
			if($auth -> login($request -> post('login'), $request -> post('password'))){
				redirect('admin?mod=news');
			}
		}

		if(!$auth -> isLogged()){
			$data = ['title' => 'Авторизация'];
			return $this -> view -> show('admin/auth', $data);
		}
		
		if(!isset($_GET['mod']) && empty($_GET['mod'])){
			$mod = 'news';
		}else{
			$mod = $_GET['mod'];
		}
		
		if(!class_exists('Cms\\Modules\\'.ucfirst($mod))){
			echo 'Модуль не найден или не включен'; exit;
		}
		
		if(empty($_GET['action']) && !isset($_GET['action'])){
			$action = 'index';
		}else{
			$action = $_GET['action'];
		}
		
		
		
		$className =  'Cms\\Modules\\'.ucfirst($mod);
		$module = new $className();

		
		
		if (! in_array(strtolower($action), array_map('strtolower', get_class_methods($module)))) {
            throw new MethodNotFoundException($action, $module);
        }
		
		return $module -> $action();
	}
   
   
	public function logout(){
	   $auth = new Auth();
	   $auth -> logout();
	   redirect('admin');
	}
}

