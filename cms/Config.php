<?php namespace Cms;

use Cms\Arr;

class Config
{

    private static $config = [];


    public static function get($key = null, $default = null)
    {
		$keys = explode('.', $key);

		if( ! array_key_exists($file = current($keys), static::$config)) {
			if(is_readable($path = SITE_DIR . 'content/settings/' .  $file . '.php')) {
				static::$config[$file] = require $path;
			}
		}
		return Arr::get(static::$config, $key, $default);
		
    }

	
	public static function set($key, $value) {
		Arr::set(static::$config, $key, $value);
	}
}


