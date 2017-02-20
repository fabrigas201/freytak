<?php namespace Cms\Libs;

use Cms\Libs\Kcaptcha\Kcaptcha;

class Captcha{
	
    protected static $instance;
    private $kcaptcha;
	
    public static function getInstance(){
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

	private function __construct(){}
	
	
	public function get(){
		unset($_SESSION['captcha_keystring']);
		
		$this -> kcaptcha = new Kcaptcha();
		
		if(!isset($_SESSION['captcha_keystring'])){
			$_SESSION['captcha_keystring'] = $this -> kcaptcha -> getKeyString();
		}
		
		return $this -> kcaptcha;
	}
	
	public function keyString(){
		if(isset($_SESSION['captcha_keystring'])){
			return $_SESSION['captcha_keystring'];
		}
	}
	
	
	
}