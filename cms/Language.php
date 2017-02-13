<?php namespace Cms;

use Cms\Config;

class Language
{

    private static $lines = [];

	public static function load($file) {
		
		$lang = Config::get('lang.weblang');
		
		if(is_readable($path = SITE_DIR . 'content/lang/' .$lang. '/'.  $file . '.php')) {
			return static::$lines[$file] = require $path;
		}
	}
	
	
	
	public static function line($key, $default = '', $args = array()) {
		$parts = explode('.', $key);

		if(count($parts) > 1) {
			$file = array_shift($parts);
			$line = array_shift($parts);
		}

		if(count($parts) == 1) {
			$file = 'text';
			$line = array_shift($parts);
		}

		if( ! isset(static::$lines[$file])) {
			static::load($file);
		}

		if(isset(static::$lines[$file][$line])) {
			$text = static::$lines[$file][$line];
		}
		else if($default) {
			$text = $default;
		}
		else {
			$text = $key;
		}

		if(count($args)) {
			return call_user_func_array('sprintf', array_merge(array($text), $args));
		}

		return $text;
	}
}


