<?php namespace App\Controllers\Web;

use Cms\Libs\Captcha;

class CaptchaCode{
	
	public function index(){
		return Captcha::getInstance() -> get();
	}
}