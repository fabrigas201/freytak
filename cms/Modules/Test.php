<?php namespace Cms\Modules;

use Cms\Controller;

class Test extends Controller {
	public function index(){
		$data = [
			'title' => '�������'
		];
		
		return $this -> view -> show('admin/news/list', $data);
		
	}
}
