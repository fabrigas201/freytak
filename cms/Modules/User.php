<?php namespace Cms\Modules;

use Cms\Modules;
use Cms\Libs\Wysiwyg;
use Cms\Api\Users;
use Cms\Api\Pagination;
use Cms\Hash;
use Cms\Api\Upload;
use Cms\Request;
use Cms\DB;

class User extends Modules {

	

	public function index(){
		
		$request = new Request();
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		
		$orderby = $request -> get('orderby') ? addslashes($request -> get('orderby')) : 'uname';
		$ascdesc = in_array($request -> get('ascdesc'), ['asc','desc']) ? $request -> get('ascdesc') : 'asc';
		
		$limit = 50;
		
		$mainTbl = PREFIX.'_user';
		$ugroups = [
			'2'=>'менеджер',
			'1'=>'администратор'
		];
		
		$users = new Users($mainTbl);
		
		$pagination = new Pagination();
		
		$pagination -> skipParam['id'] = 1;
		$pagination -> skipParam['action'] = 1;
		$pagination -> skipParam['artist'] = 1;
		

		$sqlParam = [
			'start' 		=> ($page-1)*$limit,
			'limit'  		=> $limit,
			'orderby'		=> $orderby,
			'ascdesc'		=> $ascdesc,
			'letter'		=> $request -> get('letter'),
			'colforletter'	=> 'uname',
			'category'		=> $request -> get('cat'),
		];
		
		
		$l = $users -> getList($sqlParam);
		$count = $users -> getCountRec($sqlParam);

		$pagination 	-> path = '/admin/';
		$pagesList 		= $pagination -> getPagesList($count,$page,$limit);

		$data = [
			'title'		=> 'Пользователи',
			'l' 		=> $l,
			'pagesList' => $pagesList
		];
		return $this -> view -> show('admin/users/list', $data);
		
	}
	
	public function add(){
		
		$request = new Request();
		$id = $request -> get('id');
		$mod = $request -> get('mod');
		$users = new User(PREFIX.'_user');
		$users -> mainTbl = PREFIX.'_user';
		
		$hash = new Hash();
		

		$users -> imgTbl = PREFIX.'_shop_images';
		
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateUser($request) != false){
			
			$sql_query_data = $this -> validateUser($request);

			if(is_array($sql_query_data)){
				$id = DB::insert($users -> mainTbl, $sql_query_data);
			}
			
			redirect('admin?mod='.$request -> get('mod').'&m='.($request -> get('action') == 'add'?1:2));
		}
		
		$forms['metainfoForm'] = metainfoForm([]);

		$imagesFormOpt = [
			'pref'		=> 'tm_',
			'name'		=> 'image',
			'label'		=> 'Images',
			//'descr'		=>'Image',
			'showDel'	=> 1,
			'showRadio'	=> 1,
			'showDescr'	=> 1,//комментарий к картинке
			'multy'		=> 0
		];

		$forms['imagesForm'] = imageForm($imagesFormOpt);
		
		$ugroups = [
			'2'=>'менеджер',
			'1'=>'администратор'
		];
		$vars = [];
		
		if(!isset($vars['name'])){
			$vars['name'] = '';
		}
		if(!isset($vars['pass'])){
			$vars['pass'] = '';
		}
		if(!isset($vars['repass'])){
			$vars['repass'] = '';
		}
		if(!isset($vars['email'])){
			$vars['email'] = '';
		}
		
		$vars['ugroups'] = $ugroups;
		

		$data = [
			'title'			 	=> 'Добавить пользователя',
			'wysiwygScript' 	=> $this -> wysiwyg -> getScript(),
			'forms' 			=> $forms,
			'errors' 			=> $this -> errors,
			'save' 				=> 'Сохранить',
			'vars' 				=> $vars
		];
		
		// Устанавливаем поле для полного описания новости
		
		//$this -> wysiwyg -> setField('descr', null);
		//$data['descr'] = $this -> wysiwyg -> CreateHtml();
		
		//$this -> wysiwyg -> setField('descrfull', null);
		//$data['descrfull'] = $this -> wysiwyg -> CreateHtml();
		

		return $this -> view -> show('admin/users/addEdit', $data);
		
	}
	
	public function edit(){

		$vars = [];
		
		$request = new Request();
		$id = $request -> get('id');
		$mod = $request -> get('mod');
		
		
		$users = new Users(PREFIX.'_user');
		$users -> mainTbl = PREFIX.'_user';
		$users -> imgTbl = PREFIX.'_shop_images';

		$l = $users -> getById($id);
		
		$sql_query_data = [];
		
		$ugroups = [
			'2'=>'менеджер',
			'1'=>'администратор'
		];
		
		
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateUser($request) != false){
			
			$sql_query_data =  $this -> validateUser($request);

			if(is_array($sql_query_data)){
				DB::update($users -> mainTbl, $sql_query_data, ['uid' => $id]);
			}

			redirect('admin/?mod='.$_GET['mod'].'&m='.($_GET['action']=='add'?1:2));

		}

		$vars['name']  = $request -> post('name') ? $_POST['name']  : $l -> uname;
		$vars['email']  = $request -> post('email') ? $_POST['email']  : $l -> email;
		$vars['ugroup']  = $request -> post('ugroup') ? $_POST['ugroup']  : $l -> ugroup;
		
		// Пароль зашифрован, вывести нельзя и не нужно
		$vars['pass']  = '';
		$vars['repass']  = '';
		
		$vars['ugroups'] = $ugroups;
		
		$imagesFormOpt = [
			'pref'      => 'tm_',
			'name'      => 'image',
			'label'     => 'Images',
			//'descr'     =>'Image',
			'showDel'   => 1,
			'showRadio' => 1,
			'showDescr' => 1, //комментарий к картинке
			'multy'     => 0
		];
	    
		$imagesFormOpt['images'] = ($l->user_avatar ? $l->user_avatar : '');
		$forms['imagesForm'] = imageForm($imagesFormOpt);
		

		$data = [
			'title' 		=> 'Редактирование пользователя',
			//'wysiwygScript' => $this -> wysiwyg -> getScript(),
			'vars' 			=> $vars,
			'forms' 		=> $forms,
			//'menu' 			=> $articlesList,
			'errors'		=> $this -> getErrors(),
			'save' 			=> 'Сохранить',
		];
		
		// Устанавливаем поле для полного описания новости
		
		//$this -> wysiwyg -> setField('descr', $vars['descr']);
		//$data['descr'] = $this -> wysiwyg -> CreateHtml();
		
		//$this -> wysiwyg -> setField('descrfull', $vars['descrfull']);
		//$data['descrfull'] = $this -> wysiwyg -> CreateHtml();
		
		return $this -> view -> show('admin/users/addEdit', $data);
		
	}
	
	
	public function del(){
		$request = new Request();
		$id = $request -> get('id');
		
		$users = new Users(PREFIX.'_user');
		$users -> imgTbl = PREFIX.'_shop_images';
		
		$users -> delById($id);

		redirect('admin/?mod='.$request -> get('mod').'&m=3');
		
	}
	
	
	private function validateUser(Request $request){
		//проверка данных
		
		$hash = new Hash();
		
		$sql_query_data = [];

		$pass = $request -> post('pass');
		$repass = $request -> post('repass');
		$name = $request -> post('name');
		
		if(isset($name) && !empty($name)){
			$sql_query_data['uname'] = trim(addslashes($request -> post('name')));
		}elseif(isset($name) && empty($name)){
			$this -> errors['noName']='Не указан логин';
		}
		
		if(!checkUname($request -> post('name'), $request -> get('id'))){
			$this -> errors['noName']='Логин должен быть уникален';
		}
		
		
		if($request -> post('email')){
			$sql_query_data['email'] = trim(addslashes($request -> post('email')));
		}else{
			$this -> errors['email']='Не указан Email';
		}
		
		if($request -> post('pass')){
			$sql_query_data['pass'] = $hash -> make(trim(addslashes($request -> post('pass'))));
		}else{
			$this -> errors['pass']='Поле пароль обязательно для заполнения';
		}
		if(!$request -> post('repass')){
			$this -> errors['repass']='Поле Повтор пароль обязательно для заполнения';
		}
		

		if($pass != $repass){
			$this -> errors['pass']='Пароль и повтор пароля не одинаковы';
		}
		
		if($request -> post('fio')){
			$sql_query_data['name'] = trim(addslashes($request -> post('fio')));
		}
		
		if($request -> post('phone')){
			$sql_query_data['phone'] = trim(addslashes($request -> post('phone')));
		}
		if($request -> post('phone_mobile')){
			$sql_query_data['phone_mobile'] = trim(addslashes($request -> post('phone_mobile')));
		}
		if($request -> post('icq')){
			$sql_query_data['icq'] = trim(addslashes($request -> post('icq')));
		}
		if($request -> post('job')){
			$sql_query_data['job'] = trim(addslashes($request -> post('job')));
		}
		if($request -> post('region_id')){
			$sql_query_data['region_id'] = trim(addslashes($request -> post('region_id')));
		}
		if($request -> post('imgdel')){
			$sql_query_data['imgdel'] = trim(addslashes($request -> post('imgdel')));
		}
		
		
		if($request -> post('status')){
			$sql_query_data['status'] = trim(addslashes($request -> post('status')));
		}else{
			$sql_query_data['status'] = 1;
		}
		
		if($request -> post('ip')){
			$sql_query_data['ip'] = trim(addslashes($request -> post('ip')));
		}else{
			$sql_query_data['ip'] = $_SERVER['REMOTE_ADDR'];
		}
		
		if($request -> post('user_regdate')){
			$sql_query_data['user_regdate'] = trim(addslashes($request -> post('user_regdate')));
		}else{
			$sql_query_data['user_regdate'] = date('Y-m-d H:i:s');
		}

		
		$kugroup = is_numeric($request -> post('ugroup'))?$request -> post('ugroup'):3; //3 - саамый обычный юзер ($kugroup а не $ugroup чтобы не сбивалась сессия)
		$sql_query_data['ugroup'] = $kugroup;
		
		
		if(!empty($this -> errors)){
			return false;
		}

		return $sql_query_data;
	}
	
	
}
