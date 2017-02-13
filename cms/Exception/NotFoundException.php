<?php namespace Cms\Exception;

use Exception;


class NotFoundException extends Exception
{
    public function __construct() {
       set_exception_handler(array($this, 'exception_handler'));
       throw new Exception('Page Not Found');
	}

	public function exception_handler($e) {
		header("HTTP/1.0 404 Not Found");
		echo '404 Error';
		exit;
	}
}
