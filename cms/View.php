<?php namespace Cms;

use Cms\Exception\BaseException;

class View extends \SmartyBC{

	public $path;
	public $ext = '.tpl';
    private $template;
	private $smarty;

	public function __construct(){

		$this -> smarty = new \SmartyBC();
		//if(!LOCALMODE){
			//$this -> smarty -> compile_check = false;
		//}else{
			$this -> smarty -> compile_check = true;
		//}

		if(!is_dir(TEMPLATES_DIR)){
			throw new BaseException( sprintf('Директории %s не существует', str_replace('\\', '/', TEMPLATES_DIR)));
			return;
		}

		$this -> smarty -> template_dir = TEMPLATES_DIR;
        $this -> smarty -> compile_dir  = TEMPLATES_DIR_CACHE;

	}

	
	public function vars($vars, $value=null){
		$this -> smarty -> assign($vars, $value);
	}

    public function show($template, $data) {
        $templateName = $template . $this -> ext;

		if(!is_array($data)){
			$data = [];
		}

		foreach($data as $key => $value){
			$this -> smarty -> assign($key, $value);
		}

        $this -> smarty -> display($templateName);
    }
}