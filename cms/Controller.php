<?php namespace Cms;

use Cms\View;
use Cms\Request;
use Cms\Config;
use Cms\Database;
use Cms\Libs\Wysiwyg;

abstract class Controller {

    public $view;
	public $db;
	
	public function __construct() {
		$this -> view = new View();
		$this -> wysiwyg = new Wysiwyg();
		$this -> db = Database::getInstance();
	}
}
