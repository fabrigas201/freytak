<?php namespace Cms\Modules;

use Cms\Modules;
use Cms\Libs\Wysiwyg;
use Cms\Api\Shop;
use Cms\Api\Articles;
use Cms\Libs\Pagination;
use Cms\Api\Translit;
use Cms\Api\Upload;
use Cms\Request;
use Cms\DB;

class News extends Modules {

	public $errors;

	public function index(){
		
		$request = new Request();
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 100;
		
		$shop = new Shop(PREFIX.'_news');
		$shop -> imgTbl = PREFIX.'_shop_images';
		$shop -> mod    = 'news';
		
		
		$sqlParam = [
			'start'	=> ($page-1)*$limit,
			'limit'	=> $limit,
			'orderby'	=> [
				'dateAdd'=>'desc'
			]
		];
		
		$allNews 		= $shop -> getList($sqlParam);
		$total 			= $shop -> getCountRec($sqlParam);

		
		$pagination = new Pagination();
		$pagination -> limit = $limit;
		$pagination -> page = $page;
		$pagination -> url = get_url('/admin/?mod=news&page={page}');
		$pagination -> total = $total;
		
		$data = [
			'title'		=> 'Новости',
			'result' 	=> $allNews,
			'pagesList' => $pagination -> createLinks()
		];
		return $this -> view -> show('admin/news/list', $data);
		
	}
	
	public function add(){
		
		$request = new Request();
		$id = $request -> get('id');
		$mod = $request -> get('mod');
		$shop = new Shop(PREFIX.'_news');
		
		$shop -> mod    = 'news';
		$result = $shop -> getById($id);
		
		$shop -> imgTbl = PREFIX.'_shop_images';
		
		$vars = [];
		
		if(config('lang.langs')){
			foreach(config('lang.langs') as $item){
				$vars['langs'][$item['key']]['name']  			= $_POST ? stripslashes($_POST['field'][$item['key']]['name'])  		: '';
				$vars['langs'][$item['key']]['metaD'] 			= $_POST ? stripslashes($_POST['field'][$item['key']]['metaD']) 		: '';
				$vars['langs'][$item['key']]['metaK'] 			= $_POST ? stripslashes($_POST['field'][$item['key']]['metaK']) 		: '';
				$vars['langs'][$item['key']]['alias'] 			= $_POST ? stripslashes($_POST['field'][$item['key']]['alias']) 		: '';
				$vars['langs'][$item['key']]['for_smi'] 		= $_POST ? stripslashes($_POST['field'][$item['key']]['for_smi'])	    : '';
				$vars['langs'][$item['key']]['descr']			= $_POST ? stripslashes($_POST['field'][$item['key']]['descr']) 		: '';
				$vars['langs'][$item['key']]['descrfull'] 		= $_POST ? stripslashes($_POST['field'][$item['key']]['descrfull']) 	: '';
			}
		}
		
		$vars['posi'] 		= $_POST ? $request -> post('posi')			: 	'';
		$vars['isHidden'] 	= $_POST ? $request -> post('isHidden')		:	'';
		$vars['dateAdd']	= $_POST ? $request -> post('dateAdd')		:	date('Y-m-d H:i:s');
		$vars['updated_at'] = $_POST ? $request -> post('updated_at')	:	date('Y-m-d H:i:s');
		$vars['inCalendar'] = $_POST ? $request -> post('inCalendar')	:	'';
		$vars['mod'] 		= $_POST ? $request -> post('mod')			:	'';
		$vars['eventDate'] 	= $_POST ? $request -> post('eventDate')	:	'';
		$vars['categories'] = $_POST ? $request -> post('categories')	:	'';
			
		
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateData($request) != false){
			
			//проверка данных
			$sql_query_data = $this -> validateData($request);
			
			if(isset($sql_query_data['base'])){
				$last_insert_id = DB::insert($shop -> mainTbl, $sql_query_data['base']);
			}
			
			
    		if(isset($sql_query_data['langs'])){
				foreach($sql_query_data['langs'] as $lang => $description){
					DB::insert('a_news_description', array_merge($description, ['lang' => $lang, 'news_id' => $last_insert_id]));
				}
			}
			
			if(isset($last_insert_id)){

				$imagesInfo = [
					'modid'  => $last_insert_id,
					'mod'    => addslashes($mod),
					'path' => '/i/news/',
				];
				
				if(isset($_FILES['image'])){
					$generator = new \FileUpload\FileNameGenerator\Random(10);
					$validator = new \FileUpload\Validator\Simple('10M', ['image/png', 'image/jpg', 'image/jpeg', 'image/gif']);
					$pathresolver = new \FileUpload\PathResolver\Simple(SITE_DIR.'i/news');
					$filesystem = new \FileUpload\FileSystem\Simple();
					$fileupload = new \FileUpload\FileUpload($_FILES['image'], $_SERVER);
					$fileupload -> setFileNameGenerator($generator);
					$fileupload -> setPathResolver($pathresolver);
					$fileupload -> setFileSystem($filesystem);
					$fileupload -> addValidator($validator);
					list($images, $headers) = $fileupload -> processAll();
					
					$shop -> addEditImagesInfo($imagesInfo, $images);
				}
			}	
			//Сохранить и вернуться к списку
			if($_POST['goto'])
				die(header('Location:?mod='.$_GET['mod'].'&m='.($_GET['action']=='add'?1:2)));
			else
				die(header('Location:?mod='.$_GET['mod'].'&action=edit&id='.$last_insert_id.'&m='.($_GET['action']=='add'?1:2)));
		}
			
		$imagesFormOpt = [
			'pref'		=> 'tm_',
			'name'		=> 'image',
			'label'		=> 'Images',
			//'descr'		=>'Image',
			'showDel'	=> 1,
			'showRadio'	=> 1,
			'showDescr'	=> 1,//комментарий к картинке
			'multy'		=> 1
		];

		$forms['imagesForm'] = imageForm($imagesFormOpt);
		
		$data = [
			'title' => 'Добавить новость',
			'wysiwygScript' => $this -> wysiwyg -> getScript(),
			'forms' => $forms,
			'errors' => $this -> errors,
			'save_and_list' => 'Добавить и вернуться к списку',
			'save_and_edit' => 'Добавить и продолжить редактирование',
			'vars' => $vars
		];
		

		// Устанавливаем поле для полного описания новости
		if(config('lang.langs')){
			foreach(config('lang.langs') as $lang){
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descr]', $_POST ? stripslashes($_POST['field'][$lang['key']]['descr']) : '');
				$data['descr'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descrfull]', $_POST ? stripslashes($_POST['field'][$lang['key']]['descrfull']) : '');
				$data['descrfull'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
			}
		}
		
		return $this -> view -> show('admin/news/addEdit', $data);
		
	}
	
	public function edit(){

	
	
		/* $query = DB::query('SELECT * FROM `a_news`');
		foreach($query -> rows as $item){
			$sql_query['news_id'] = $item -> id;
			$sql_query['alias'] = empty($item -> alias) ? $item -> id : $item -> alias;
			$sql_query['title'] = $item -> name;
			$sql_query['descr'] = $item -> descr;
			$sql_query['text'] = $item -> descrfull;
			$sql_query['metaK'] = $item -> metaK;
			$sql_query['metaD'] = $item -> metaD;
			$sql_query['for_smi'] = $item -> for_smi;
			$sql_query['lang'] = 'ru';
			DB::insert('a_news_description', $sql_query);
		} 
	 */
	
	
	
		$vars = [];
		
		$request = new Request();
		$id = $request -> get('id');
		$mod = $request -> get('mod');
		
		
		$shop = new Shop(PREFIX.'_news');
		$shop -> imgTbl = PREFIX.'_shop_images';
		$shop -> mod    = 'news';

		$result = $shop -> getById($id);
		
		if(isset($result['langs'])){
			foreach($result['langs'] as $item){
				$vars['langs'][$item -> lang]['name']  			= $_POST ? $_POST['field'][$item -> lang]['name']  		: stripslashes($item -> title);
				$vars['langs'][$item -> lang]['metaD'] 			= $_POST ? $_POST['field'][$item -> lang]['metaD'] 		: stripslashes($item -> metaD);
				$vars['langs'][$item -> lang]['metaK'] 			= $_POST ? $_POST['field'][$item -> lang]['metaK'] 		: stripslashes($item -> metaK);
				$vars['langs'][$item -> lang]['alias'] 			= $_POST ? $_POST['field'][$item -> lang]['alias'] 		: stripslashes($item -> alias);
				$vars['langs'][$item -> lang]['for_smi'] 		= $_POST ? $_POST['field'][$item -> lang]['for_smi'] 	: stripslashes($item -> for_smi);
				$vars['langs'][$item -> lang]['descr']			= $_POST ? $_POST['field'][$item -> lang]['descr'] 		: stripslashes($item -> descr);
				$vars['langs'][$item -> lang]['descrfull'] 		= $_POST ? $_POST['field'][$item -> lang]['descrfull'] 	: stripslashes($item -> descrfull);
			}
		}
		
		if(isset($result['base'])){
			$vars['posi'] 		= $_POST ? $request -> post('posi')			: 	$result['base'] -> posi;
			$vars['isHidden'] 	= $_POST ? $request -> post('isHidden')		:	$result['base'] -> isHidden;
			$vars['dateAdd']	= $_POST ? $request -> post('dateAdd')		:	($result['base'] -> dateAdd ? $result['base'] -> dateAdd : date('Y-m-d H:i:s'));
			$vars['updated_at'] = $_POST ? $request -> post('updated_at')	:	$result['base'] -> updated_at;
			$vars['inCalendar'] = $_POST ? $request -> post('inCalendar')	:	$result['base'] -> inCalendar;
			$vars['mod'] 		= $_POST ? $request -> post('mod')			:	$result['base'] -> mod;
			$vars['eventDate'] 	= $_POST ? $request -> post('eventDate')	:	$result['base'] -> eventDate;
			$vars['categories'] = $_POST ? $request -> post('categories')	:	$result['base'] -> categories;
			
			if(!$request -> get('parent_id')){
				$vars['pid'] = $result['base'] -> pid;
			}
		}
		
		$sql_query_data = [];
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateData($request) != false){
			
			$sql_query_data = $this -> validateData($request);
			
			if($request -> post('newCoverId')){
				$newCoverId 	= $request -> post('newCoverId');
			}else{
				$newCoverId 	= false;
			}
			
			if(is_array($request -> post('imgdel'))){
				$imgdel 	= $request -> post('imgdel');
			}else{
				$imgdel 	= '';
			}
			
			if(is_array($request -> post('imgdescr'))){
				$imgdescr 	= $request -> post('imgdescr');
			}else{
				$imgdescr 	= '';
			}
			
		
			$imgPosis = [];
			
			if(is_array($request -> post('imgposi'))){
				foreach($_POST['imgposi'] as $k=>$v){
					if(md5($v)!=$_POST['imgposiMD5'][$k])
						$imgPosis[$k]=addslashes($v);
				}
			}

			//показывать на главной
			$imgUrls = [];
			if(is_array($request -> post('imgurlMD5'))){
				foreach($_POST['imgurlMD5'] as $k=>$v){
					$imgurlVal=$_POST['imgurl'][$k]?1:0;
					if($v!=md5($imgurlVal))
						$imgUrls[$k]=$imgurlVal;
				}
			}
	
			$imagesInfo = [
				'modid'  => $id,
				'mod'    => addslashes($mod),
				'path' => '/i/news/',
				'imgdelIds' => $imgdel,
				'newCoverId'=> $newCoverId,
				'imgDescr' => $imgdescr
				
			];
			
			if(isset($_FILES['image'])){
				$generator = new \FileUpload\FileNameGenerator\Random(10);
				$validator = new \FileUpload\Validator\Simple('10M', ['image/png', 'image/jpg', 'image/jpeg', 'image/gif']);
				$pathresolver = new \FileUpload\PathResolver\Simple(SITE_DIR.'i/news');
				$filesystem = new \FileUpload\FileSystem\Simple();
				$fileupload = new \FileUpload\FileUpload($_FILES['image'], $_SERVER);
				$fileupload -> setFileNameGenerator($generator);
				$fileupload -> setPathResolver($pathresolver);
				$fileupload -> setFileSystem($filesystem);
				$fileupload -> addValidator($validator);
				list($images, $headers) = $fileupload -> processAll();
				$shop -> addEditImagesInfo($imagesInfo, $images);
			}else{
				$shop -> addEditImagesInfo($imagesInfo);
			}
			
			
			if ($request -> post('imgdescr')){
				
				foreach ($request -> post('imgdescr') as $k=>$v){
					DB::update($shop -> mainTbl, ['descr' => $v], ['id' => $k]);
				}
			}

			
			if(isset($sql_query_data['langs'])){
				DB::delete('a_news_description', ['news_id' => $id]);
				
				foreach($sql_query_data['langs'] as $lang => $description){
					DB::insert('a_news_description', array_merge($description, ['lang' => $lang, 'news_id' => $id]));
				}
			}
			if(isset($sql_query_data['base'])){
				DB::update($shop -> mainTbl, $sql_query_data['base'], ['id' => $id]);
			}
			
			
			//меняем/заносим позицию картинки
			if(count($imgPosis)>0 && is_array($imgPosis)){
				foreach($imgPosis as $k=>$v){
					DB::update($shop -> mainTbl, ['posi' => $v], ['id' => $k]);
				}
			}
			//меняем/заносим ссылку картинки
			if(count($imgUrls)>0 && is_array($imgUrls)){
				foreach($imgUrls as $k=>$v){
					DB::update($shop -> mainTbl, ['uploadinfo' => $v], ['id' => $k]);
				}
			}
			//Сохранить и вернуться к списку
			
			if($request -> post('goto')){
				redirect('admin/?mod='.$request -> get('mod').'&m='.($request -> get('action')=='add'?1:2));
			}else{
				redirect('admin/?mod='.$request -> get('mod').'&action=edit&id='.$id.'&m='.($request -> get('action')=='add'?1:2));
			}
		}
		
		
		$imagesFormOpt = [
			'pref'      => 'tm_',
			'name'      => 'image',
			'label'     => 'Images',
			//'descr'     =>'Image',
			'showDel'   => 1,
			'showRadio' => 1,
			'showDescr' => 1, //комментарий к картинке
			'multy'     => 1
		];
	    
		$imagesFormOpt['images'] = ($result['base']->images ? $result['base']->images : '');
		$forms['imagesForm'] = imageForm($imagesFormOpt);
		
		
		$articles = new Articles(PREFIX.'_menu');
		$articles -> mainTbl=PREFIX.'_menu';
		$articles -> getTree(0);
		$articlesList = $articles -> catList;
		
		$data = [
			'title' 		=> 'Редактирование новости',
			'wysiwygScript' => $this -> wysiwyg -> getScript(),
			'vars' 			=> $vars,
			'forms' 		=> $forms,
			'errors'		=> $this -> errors,
			'menu'          => $articlesList,
			'save_and_list' => 'Добавить и вернуться к списку',
			'save_and_edit' => 'Добавить и продолжить редактирование',
		];
		
		// Устанавливаем поле для полного описания новости
		if(config('lang.langs')){
			foreach(config('lang.langs') as $lang){
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descr]', isset($vars['langs'][$lang['key']]['descr']) ? stripslashes($vars['langs'][$lang['key']]['descr']) : '' );
				$data['descr'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
				
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descrfull]', isset($vars['langs'][$lang['key']]['descrfull']) ? stripslashes($vars['langs'][$lang['key']]['descrfull']) : '' );
				$data['descrfull'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
			}
		}
		return $this -> view -> show('admin/news/addEdit', $data);
		
	}
	
	
	public function del(){
		$request = new Request();
		$id = $request -> get('id');
		
		$shop = new Shop(PREFIX.'_news');
		$shop -> imgTbl = PREFIX.'_shop_images';
		$shop -> mod    = 'news';
		
		$shop -> delGoodsById($id);
		DB::delete('a_news_description', ['news_id' => $id]);
		
		redirect('admin/?mod='.$request -> get('mod').'&m=3');
		
	}
	
	
	protected function validateData(Request $request){
		//проверка данных
		$sql_query_data = [];
		
		foreach($request -> post('field') as $lang => $field){
			if(isset($_POST['field'][$lang]['name']) && !empty($_POST['field'][$lang]['name'])){
				$sql_query_data['langs'][$lang]['title'] = trim(addslashes($_POST['field'][$lang]['name']));
				
				// Устаналиваем alias
				$alias = getAlias($_POST['field'][$lang]['name'], $_POST['field'][$lang]['alias']);
				$alias = checkAlias($alias, $request -> get('id'), $lang);
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
		
		if($request -> post('posi')){
			$sql_query_data['base']['posi'] = intval($request -> post('posi'));
		}
		
		$sql_query_data['base']['inIndex'] 	= $request -> post('inIndex')?1:0;
		$sql_query_data['base']['isHidden'] = $request -> post('isHidden')?1:0;
		$sql_query_data['base']['inCalendar'] = $request -> post('inCalendar')?1:0;
		
		$sql_query_data['base']['pid'] = $request -> post('pid') ? $request -> post('pid') : 0;

		$sql_query_data['base']['categories'] = $request -> post('categories') ? $request -> post('categories') : 0;
		
		
		if($request -> post('dateAdd')){
			$sql_query_data['base']['dateAdd'] 	= trim($request -> post('dateAdd'));
		}else{
			$sql_query_data['base']['dateAdd']	= '0000-00-00 00:00:00';
		}
		
		if($request -> post('eventDate') != null){
			$sql_query_data['base']['eventDate'] 	= trim($request -> post('eventDate'));
		}else{
			$sql_query_data['base']['eventDate']	= '';
		}
		
		
		if($request -> post('datePub')){
			$sql_query_data['base']['datePub'] 	= trim($request -> post('datePub'));
		}else{
			$sql_query_data['base']['datePub']	= date('Y-m-d H:i:s');
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
