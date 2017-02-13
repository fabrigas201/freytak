<?php namespace Cms\Modules;

use Cms\DB;
use Cms\Modules;
use Cms\Libs\Wysiwyg;
use Cms\Api\ContactsModel;
use Cms\Libs\Trees;
use Cms\Libs\Pagination;
use Cms\Api\Translit;
use Cms\Api\Upload;
use Cms\Request;

class Contacts extends Modules {

	public function index(){
		

		/* $query = DB::query('SELECT * FROM `a_other`');
		foreach($query -> rows as $item){
			$sql_query['other_id'] = $item -> id;
			$sql_query['alias'] = empty($item -> alias) ? $item -> id : $item -> alias;
			$sql_query['title'] = $item -> name;
			$sql_query['descr'] = $item -> descr;
			$sql_query['metaK'] = $item -> metaK;
			$sql_query['metaD'] = $item -> metaD;
			$sql_query['lang'] = 'ru';
			DB::insert('a_other_description', $sql_query);
		}  */
	 
		$request = new Request();
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 100;
		
		$sqlParam = [
			'start'=>($page-1)*$limit,
			'limit'=>$limit,
		];
		
		$contactsModel = new ContactsModel();
		$contacts = $contactsModel -> getContacts($sqlParam);
		$total 	= $contactsModel -> getContactsCount($sqlParam);
		
		$pagination = new Pagination();
		$pagination -> limit = $limit;
		$pagination -> page = $page;
		$pagination -> url = get_url('/admin/?mod=contacts&page={page}');
		$pagination -> total = $total;
		
		$data = [
			'title'		=> 'Контакты',
			'result' 	=> $contacts,
			'pagesList' => $pagination -> createLinks()
		];
		return $this -> view -> show('admin/other/list', $data);
		
	}
	

	public function edit(){

		$vars = [];
		
		$request = new Request();
		$id = $request -> get('id');
		$mod = $request -> get('mod');
		
		
		$contactsModel = new ContactsModel();
		$result = $contactsModel -> getContact($id);
		
		$sql_query_data = [];
		
		if(isset($result['langs'])){
			foreach($result['langs'] as $item){
				$vars['langs'][$item -> lang]['name']  			= $_POST ? $_POST['field'][$item -> lang]['name']  		: htmlspecialchars($item -> title, ENT_QUOTES, 'UTF-8');
				$vars['langs'][$item -> lang]['metaD'] 			= $_POST ? $_POST['field'][$item -> lang]['metaD'] 		: $item -> metaD;
				$vars['langs'][$item -> lang]['metaK'] 			= $_POST ? $_POST['field'][$item -> lang]['metaK'] 		: $item -> metaK;
				$vars['langs'][$item -> lang]['alias'] 			= $_POST ? $_POST['field'][$item -> lang]['alias'] 		: $item -> alias;
				$vars['langs'][$item -> lang]['descr']			= $_POST ? $_POST['field'][$item -> lang]['descr'] 		: $item -> descr;
				$vars['langs'][$item -> lang]['descrfull'] 		= $_POST ? $_POST['field'][$item -> lang]['descrfull'] 	: $item -> descrfull;
			}
		}
		
		if(isset($result['base'])){
			$vars['posi'] 		= $_POST ? $request -> post('posi')			: 	$result['base'] -> posi;
			$vars['isHidden'] 	= $_POST ? $request -> post('isHidden')		:	$result['base'] -> isHidden;
			$vars['mod'] 		= $_POST ? $request -> post('mod')			:	$result['base'] -> mod;
			$vars['categories'] = $_POST ? $request -> post('categories')	:	$result['base'] -> category;
			$vars['data'] 		= $_POST ? $request -> post('data') 		: 	$result['base'] -> data;
			$vars['posi']  		= $_POST ? $request -> post('posi') 		: 	$result['base'] -> posi;
			
		}
		
		
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateData($request) != false){

			$sql_query_data = $this -> validateData($request);
			
			if(isset($sql_query_data['langs'])){
				DB::delete('a_other_description', ['other_id' => $id]);
				
				foreach($sql_query_data['langs'] as $lang => $description){
					DB::insert('a_other_description', array_merge($description, ['lang' => $lang, 'other_id' => $id]));
				}
			}
			if(isset($sql_query_data['base'])){
				DB::update('a_other', $sql_query_data['base'], ['id' => $id]);
			}

			if($request -> post('goto')){
				redirect('admin/?mod='.$_GET['mod'].'&m='.($_GET['action']=='add'?1:2));
			}else{
				redirect('admin/?mod='.$_GET['mod'].'&action=edit&id='.$id.'&m='.($_GET['action']=='add'?1:2));
			}
		}
		
		$categories = new Trees();
		$categories -> getTree(0);
		$categoriesList = $categories -> getCatList();

		$data = [
			'title' 		=> 'Редактирование контактов',
			'wysiwygScript' => $this -> wysiwyg -> getScript(),
			'vars' 			=> $vars,
			'errors'		=> $this -> errors,
			'menu'          => $categoriesList,
			'save_and_list' => 'Сохранить',
		];
		
		// Устанавливаем поле для полного описания новости
		if(config('lang.langs')){
			foreach(config('lang.langs') as $lang){
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descr]', isset($vars['langs'][$lang['key']]['descr']) ? $vars['langs'][$lang['key']]['descr'] : '' );
				$data['descr'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
				
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descrfull]', isset($vars['langs'][$lang['key']]['descrfull']) ? $vars['langs'][$lang['key']]['descrfull'] : '' );
				$data['descrfull'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
			}
		}
		
		return $this -> view -> show('admin/other/kontact_addEdit', $data);
		
	}
	
	
	public function del(){
		$request = new Request();
		$id = $request -> get('id');
		
		$shop = new Shop(PREFIX.'_other');
		$shop -> imgTbl = PREFIX.'_shop_images';
		$shop -> mod    = 'contacts';
		
		$shop -> delGoodsById($id);

		redirect('admin/?mod='.$request -> get('mod').'&submod='.$request -> get('submod').'&m=3');
		
	}
	
	
	
	protected function validateData(Request $request){
		//проверка данных
		$sql_query_data = [];
		
		foreach($request -> post('field') as $lang => $field){
			if(isset($_POST['field'][$lang]['name']) && !empty($_POST['field'][$lang]['name'])){
				$sql_query_data['langs'][$lang]['title'] = trim(addslashes($_POST['field'][$lang]['name']));
				
				// Устаналиваем alias
				$alias	= getAlias($_POST['field'][$lang]['name']);
				$alias = checkAlias($alias, $request -> get('id'));
				$sql_query_data['langs'][$lang]['alias'] = $alias;
				
			}else{
				$this -> errors[$lang]['noName']='Не указано наименование';
			}
			
			if(isset($_POST['field'][$lang]['descr'])){
				$sql_query_data['langs'][$lang]['descr'] = addslashes($_POST['field'][$lang]['descr']);
			}
			
			if(isset($_POST['field'][$lang]['descrfull'])){
				$sql_query_data['langs'][$lang]['descrfull'] = addslashes($_POST['field'][$lang]['descrfull']);
			}
			if(isset($_POST['field'][$lang]['metaD'])){
				$sql_query_data['langs'][$lang]['metaD'] = addslashes($_POST['field'][$lang]['metaD']);
			}
			if(isset($_POST['field'][$lang]['metaK'])){
				$sql_query_data['langs'][$lang]['metaK'] = addslashes($_POST['field'][$lang]['metaK']);
			}
				
		}
		
		$sql_query_data['base']['inIndex'] 	= $request -> post('inIndex')?1:0;
		$sql_query_data['base']['isHidden'] = $request -> post('isHidden')?1:0;
		
		if($request -> post('data')){
			$sql_query_data['base']['data'] = serialize($request -> post('data'));
		}
		
		if($request -> post('categories')){
			$sql_query_data['base']['category'] = addslashes(htmlspecialchars($request -> post('categories'),ENT_QUOTES));
		}else{
			$sql_query_data['base']['category'] = '';
		}
		if($request -> post('dateAdd')){
			$sql_query_data['base']['dateAdd'] 	= trim($request -> post('dateAdd'));
		}
		

		if($request -> get('mod')){
			$sql_query_data['base']['mod'] 	= trim(addslashes($request -> get('mod')));
		}else{
			$sql_query_data['base']['mod']	= '';
		}
		
		if(!empty($this -> errors)){
			return false;
		}
		return $sql_query_data;
	}
}
