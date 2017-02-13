<?php namespace Cms\Modules;

use Cms\DB;
use Cms\Modules;
use Cms\Request;
use Cms\Libs\Wysiwyg;
use Cms\Api\TagsSystem;
use Cms\Libs\Pagination;

class Tags extends Modules {
	
	public $errors;
	
	public function index(){
		
		$request = new Request();
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 100;
		
		$sqlParam = [
			'start'	=> ($page-1)*$limit,
			'limit'	=> $limit,
			'orderby'	=> [
				'dateAdd'=>'desc'
			]
		];
		
		$result = TagsSystem::getTags($sqlParam);
		
		$sqlParam = [
			'orderby'	=> [
				'dateAdd'=>'desc'
			]
		];
		
		$total 	= TagsSystem::getTagsCount($sqlParam);

		$pagination = new Pagination();
		$pagination -> limit = $limit;
		$pagination -> page = $page;
		$pagination -> url = get_url('/admin/?mod=tags&page={page}');
		$pagination -> total = $total -> total;
		
		
		
		$data = [
			'title'		=> 'Система тегов',
			'result' 	=> $result,
			'pagesList' => $pagination -> createLinks()
		];
		return $this -> view -> show('admin/tags/list', $data);
		
		
		return $this -> view -> show('admin/tags/list', $data);
		
	}
	
	
	public function add(){
		$request = new Request();
		
		$vars = [];
		
		if(config('lang.langs')){
			foreach(config('lang.langs') as $item){
				$vars['langs'][$item['key']]['name']  			= $request -> isPost() ? $_POST['field'][$item['key']]['name']  : '';
				$vars['langs'][$item['key']]['descr']			= $request -> isPost() ? $_POST['field'][$item['key']]['descr'] : '';
			}
		}
		
		$vars['posi'] 		= $request -> isPost() ? $request -> post('posi')			: 	'';
		$vars['alias'] 		= $request -> isPost() ? $request -> post('alias')			: 	'';
		$vars['dateAdd']	= $_POST ? $request -> post('dateAdd')						:	date('Y-m-d H:i:s');
		$vars['isHidden'] 	= $request -> isPost() ? $request -> post('isHidden')		:	'';
		
		
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateData($request) != false){

			$sql_query_data = $this -> validateData($request);

			
			if(isset($sql_query_data['base'])){
				$last_insert_id = DB::insert('a_tags_system', $sql_query_data['base']);
			}
			
    		if(isset($sql_query_data['langs'])){
				foreach($sql_query_data['langs'] as $lang => $description){
					DB::insert('a_tags_system_description', array_merge($description, ['lang' => $lang, 'tgs_id' => $last_insert_id]));
				}
			}
			
			
			if($request -> post('goto')){
				redirect('admin/?mod='.$request -> get('mod').'&m='.($request -> get('action')=='add'?1:2));
			}else{
				redirect('admin/?mod='.$request -> get('mod').'&action=edit&id='.$last_insert_id.'&m='.($request -> get('action')=='add'?1:2));
			}
			
			
			
		}
		
		$data = [
			'title' => 'Добавить тег',
			'vars' => $vars,
			'errors' => $this -> getErrors(),
			'save_and_list' => 'Добавить и вернуться к списку',
			'save_and_edit' => 'Добавить и продолжить редактирование',
		];
		
		if(config('lang.langs')){
			foreach(config('lang.langs') as $lang){
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descr]', $_POST ? $_POST['field'][$lang['key']]['descr'] : '');
				$data['descr'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
			}
		}
		
		return $this -> view -> show('admin/tags/addEdit', $data);
	}
	
	
	
	public function edit(){
		$request = new Request();
		$id = $request -> get('id');
		
		
		$vars = [];
		
		$id = $request -> get('id');
		
		$result = TagsSystem::getTag($id);

		if(!empty($result['langs'])){
			foreach($result['langs'] as $item){
				$vars['langs'][$item -> lang]['name']  			= $request -> isPost() ? $_POST['field'][$item -> lang]['name']  : $item -> title;
				$vars['langs'][$item -> lang]['descr']			= $request -> isPost() ? $_POST['field'][$item -> lang]['descr'] : $item -> descr;
			}
		}
		
		$vars['posi'] 		= $request -> isPost() ? $request -> post('posi')			: 	$result['base'] -> posi;
		$vars['alias'] 		= $request -> isPost() ? $request -> post('alias')			: 	$result['base'] -> alias;
		$vars['dateAdd']	= $_POST ? $request -> post('dateAdd')						:	($result['base'] -> dateAdd ? $result['base'] -> dateAdd : date('Y-m-d H:i:s'));
		$vars['isHidden'] 	= $request -> isPost() ? $request -> post('isHidden')		:	$result['base'] -> isHidden;
		
		
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateData($request) != false){

			$sql_query_data = $this -> validateData($request);

			
			if(isset($sql_query_data['base'])){
				DB::update('a_tags_system', $sql_query_data['base'], ['id' => $id]);
			}
			
    		if(isset($sql_query_data['langs'])){
				DB::delete('a_tags_system_description', ['tgs_id' => $id]);
				
				foreach($sql_query_data['langs'] as $lang => $description){
					DB::insert('a_tags_system_description', array_merge($description, ['lang' => $lang, 'tgs_id' => $id]));
				}
			}
		}
		

		$data = [
			'title' => 'Добавить тег',
			'vars' => $vars,
			'errors' => $this -> getErrors(),
			'save_and_list' => 'Добавить и вернуться к списку',
			'save_and_edit' => 'Добавить и продолжить редактирование',
		];
		
		if(config('lang.langs')){
			foreach(config('lang.langs') as $lang){
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descr]', $_POST ? $_POST['field'][$lang['key']]['descr'] : '');
				$data['descr'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
			}
		}
		
		return $this -> view -> show('admin/tags/addEdit', $data);
	}

	protected function validateData(Request $request){
		//проверка данных
		$sql_query_data = [];
		
		foreach($request -> post('field') as $lang => $field){
			if(isset($_POST['field'][$lang]['name']) && !empty($_POST['field'][$lang]['name'])){
				$sql_query_data['langs'][$lang]['title'] = trim(addslashes($_POST['field'][$lang]['name']));
			}else{
				$this -> errors[$lang]['noName']='Не указано наименование';
			}
			
			if(isset($_POST['field'][$lang]['descr'])){
				$sql_query_data['langs'][$lang]['descr'] = addslashes($_POST['field'][$lang]['descr']);
			}	
		}
		
		if(isset($_POST['alias']) && !empty($_POST['alias'])){
			$sql_query_data['base']['alias'] = trim(addslashes($_POST['alias']));
		}else{
			$this -> errors['noAlias']='Не указан Alias';
		}
		
		
		if($request -> post('posi')){
			$sql_query_data['base']['posi'] = intval($request -> post('posi'));
		}
		
		$sql_query_data['base']['isHidden'] = $request -> post('isHidden')?1:0;

		if(!empty($this -> errors)){
			return false;
		}
		return $sql_query_data;
	}
}
