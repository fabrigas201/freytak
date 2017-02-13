<?php namespace Cms\Modules;

use Cms\Modules;
use Cms\Libs\Wysiwyg;
use Cms\Api\Articles;
use Cms\Api\Pagination;
use Cms\Api\Translit;
use Cms\Api\Upload;
use Cms\Api\TagsSystem;
use Cms\Request;
use Cms\DB;
use Cms\Api\Shop;

class Menu extends Modules {

	public $errors;

	public function index(){
		
		$request = new Request();
		
		$articles = new Articles(PREFIX.'_menu');
		$articles -> mainTbl=PREFIX.'_menu';
		
		if($request -> isPost()){
			$imgPosis = [];
			
			if(is_array($request -> post('posi'))){
				foreach($request -> post('posi') as $k=>$v){
					if(md5($v)!=$request -> post('posiMD5')[$k]) $imgPosis[$k]=addslashes($v);
				}
			}
			
			//меняем/заносим ссылку картинки
			if(count($imgPosis)>0 && is_array($imgPosis)){
				foreach($imgPosis as $k=>$v){
					DB::update($articles -> mainTbl, ['posi' => $v], ['id' => $k]);
				}
			}
		}
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 100;
		
		$articles -> sqlWhereAdd = ' AND `amd`.`lang`="'.config('lang.base').'" ';
		$sqlParam = [
			'limit'=> 0,
			'orderby'=> [
				'posi'=>'asc'
			],
		];
		
		$allArticles 		= $articles -> getList($sqlParam);


		$data = [
			'title'		=> 'Верхнее меню',
			'l' 		=> $allArticles,
		];
		return $this -> view -> show('admin/categories/list', $data);
		
	}
	
	public function add(){
		
		$request = new Request();
		$mod = $request -> get('mod');
		$articles = new Articles(PREFIX.'_menu');
		$articles -> mainTbl=PREFIX.'_menu';
		$tagsSystem = TagsSystem::getTags();
		
		
		$shop = new Shop();
		$shop -> imgTbl = PREFIX.'_shop_images';
		$shop -> mod = 'menu';
		
		
		
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateData($request) != false){
			
			//проверка данных
			$sql_query_data = $this -> validateData($request);
			
			if(isset($sql_query_data['base'])){
				$last_insert_id = DB::insert($articles -> mainTbl, $sql_query_data['base']);
			}
			
			if(isset($sql_query_data['langs'])){
				
				foreach($sql_query_data['langs'] as $lang => $description){
					DB::insert('a_menu_description', array_merge($description, ['lang' => $lang, 'menu_id' => $last_insert_id]));
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
				redirect('admin/?mod='.$_GET['mod'].'&m='.($_GET['action']=='add'?1:2));
			else
				redirect('admin/?mod='.$_GET['mod'].'&action=edit&id='.$last_insert_id.'&m='.($_GET['action']=='add'?1:2));
		}

		
		$vars = [];
		
		if(!isset($vars['isHidden'])){
			$vars['isHidden'] = '';
		}
		if(!isset($vars['isIndex'])){
			$vars['isIndex'] = '';
		}
		
		
		if(!isset($vars['in_sitemap'])){
			$vars['in_sitemap'] = '';
		}
		

		if(!isset($vars['posi'])){
			$vars['posi'] = '';
		}
		
		if(!isset($vars['typeMenu'])){
			$vars['typeMenu'] = '';
		}
		if(!isset($vars['dateAdd'])){
			$vars['dateAdd'] = date('Y-m-d H:i:s');
		}
		
	
		
		if($request -> get('parent_id')){
			$vars['pid'] = $request -> get('parent_id');
		}
		
		$vars['tagsSystem'] = $tagsSystem;
		
		$articles -> getTree(0);
		$articlesList = $articles -> catList;
		
		
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
	    
		$forms['imagesForm'] = imageForm($imagesFormOpt);
		
		$data = [
			'title' 		=> 'Добавить пункт меню',
			'wysiwygScript' => $this -> wysiwyg -> getScript(),
			'errors' 		=> $this -> errors,
			'articles' 		=> $articlesList,
			'forms' 		=> $forms,
			'save_and_list' => 'Добавить и вернуться к списку',
			'save_and_edit' => 'Добавить и продолжить редактирование',
			'vars' 			=> $vars
		];
		
		
		if(config('lang.langs')){
			foreach(config('lang.langs') as $lang){
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descrfull]', isset($vars['langs'][$lang['key']]['descrfull']) ? $vars['langs'][$lang['key']]['descrfull'] : '' );
				$data['descrfull'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
			}
		}
		
		return $this -> view -> show('admin/menu/addEdit', $data);
		
	}
	
	public function edit(){

		$vars = [];
		
		$request = new Request();

		$id = $request -> get('id');
		$mod = $request -> get('mod');
		$articles = new Articles(PREFIX.'_menu');
		$articles->mainTbl=PREFIX.'_menu';
		$articles -> imgTbl = PREFIX.'_shop_images';
		
		$result = $articles -> getById($id);

		
		$shop = new Shop();
		$shop -> imgTbl = PREFIX.'_shop_images';
		$shop -> mod = 'menu';
		
		if(isset($result['langs'])){
			foreach($result['langs'] as $item){
				$vars['langs'][$item -> lang]['name']  			= $_POST ? $_POST['field'][$item -> lang]['name']  		: $item -> title;
				$vars['langs'][$item -> lang]['text'] 			= $_POST ? $_POST['field'][$item -> lang]['descrfull'] 	: $item -> text;
				$vars['langs'][$item -> lang]['metaD'] 			= $_POST ? $_POST['field'][$item -> lang]['metaD'] 		: $item -> metaD;
				$vars['langs'][$item -> lang]['metaK'] 			= $_POST ? $_POST['field'][$item -> lang]['metaK'] 		: $item -> metaK;
				//$vars['langs'][$item -> lang]['alias'] 			= $_POST ? $_POST['field'][$item -> lang]['alias'] 		: $item -> alias;
				//$vars['langs'][$item -> lang]['for_smi'] 		= $_POST ? $_POST['field'][$item -> lang]['for_smi'] 	: $item -> for_smi;
				//$vars['langs'][$item -> lang]['description']	= $_POST ? $_POST['field'][$item -> lang]['descr'] 		: $item -> description;
			}
		}
		
		
		if(isset($result['base'])){
			$vars['posi'] 		= $_POST ? $request -> post('posi')			: 	$result['base'] -> posi;
			$vars['isIndex'] 	= $_POST ? $request -> post('isIndex') 		:	$result['base'] -> isIndex;
			$vars['typeMenu'] 	= $_POST ? $request -> post('typeMenu') 	: 	$result['base'] -> typeMenu;
			$vars['in_sitemap'] = $_POST ? $request -> post('in_sitemap') 	:	$result['base'] -> in_sitemap;
			$vars['isHidden'] 	= $_POST ? $request -> post('isHidden')		:	$result['base'] -> isHidden;
			$vars['dateAdd']	= $_POST ? $request -> post('dateAdd')		:	($result['base'] -> dateAdd?$result['base'] -> dateAdd : date('Y-m-d H:i:s'));
			
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
					DB::update($shop -> imgTbl, ['descr' => $v], ['id' => $k]);
				}
			}
			
			//меняем/заносим позицию картинки
			if(count($imgPosis)>0 && is_array($imgPosis)){
				foreach($imgPosis as $k=>$v){
					DB::update($shop -> imgTbl, ['posi' => $v], ['id' => $k]);
				}
			}
			//меняем/заносим ссылку картинки
			if(count($imgUrls)>0 && is_array($imgUrls)){
				foreach($imgUrls as $k=>$v){
					DB::update($shop -> imgTbl, ['uploadinfo' => $v], ['id' => $k]);
				}
			}
			
			
			if(isset($sql_query_data['langs'])){
				DB::delete('a_menu_description', ['menu_id' => $id]);
				
				foreach($sql_query_data['langs'] as $lang => $description){
					DB::insert('a_menu_description', array_merge($description, ['lang' => $lang, 'menu_id' => $id]));
				}
				
			}
			if(isset($sql_query_data['base'])){
				DB::update($articles -> mainTbl, $sql_query_data['base'], ['id' => $id]);
			}

			if($request -> post('goto')){
				redirect('admin/?mod='.$_GET['mod'].'&m='.($request -> get('action')=='add'?1:2));
			}else{
				redirect('admin/?mod='.$_GET['mod'].'&action=edit&id='.$id.'&m='.($_GET['action']=='add'?1:2));
			}
		}

		$articles -> getTree(0);
		$articlesList = $articles -> catList;
		
		$tagsSystem = TagsSystem::getTags();
		$vars['tagsSystem'] = $tagsSystem;
		
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
		
		
		
		$data = [
			'title' 		=> 'Редактирование новости',
			'vars' 			=> $vars,
			'errors'		=> $this -> errors,
			'articles'      => $articlesList,
			'forms'			=> $forms,
			'save_and_list' => 'Добавить и вернуться к списку',
			'save_and_edit' => 'Добавить и продолжить редактирование',
		];
		
		
		if(config('lang.langs')){
			foreach(config('lang.langs') as $lang){
				$this -> wysiwyg -> setField('field['.$lang['key'].'][descrfull]', isset($vars['langs'][$lang['key']]['text']) ? $vars['langs'][$lang['key']]['text'] : '' );
				$data['descrfull'][$lang['key']] = $this -> wysiwyg -> CreateHtml();
			}
		}

		return $this -> view -> show('admin/menu/addEdit', $data);
		
	}
	
	
	public function del(){
		
		$request = new Request();
		$id = $request -> get('id');
		
		
		$articles = new Articles(PREFIX.'_menu');
		$articles -> mainTbl = PREFIX.'_menu';
		$l = $articles->getById($id);
		
		$articles -> delTree($id);
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
			
			if(isset($_POST['field'][$lang]['description'])){
				$sql_query_data['langs'][$lang]['description'] = addslashes($_POST['field'][$lang]['description']);
			}
			
			if(isset($_POST['field'][$lang]['descrfull'])){
				$sql_query_data['langs'][$lang]['text'] = addslashes($_POST['field'][$lang]['descrfull']);
			}
			
			
			if(isset($_POST['field'][$lang]['metaD'])){
				$sql_query_data['langs'][$lang]['metaD'] = addslashes(htmlspecialchars($_POST['field'][$lang]['metaD'],ENT_QUOTES));
			}
			if(isset($_POST['field'][$lang]['metaK'])){
				$sql_query_data['langs'][$lang]['metaK'] = addslashes(htmlspecialchars($_POST['field'][$lang]['metaK'],ENT_QUOTES));
			}
				
		}
		
		if($request -> post('posi')){
			$sql_query_data['base']['posi'] = intval($request -> post('posi'));
		}
		
		if($request -> post('tgs_id') != null){
			$sql_query_data['base']['tgs_id'] = intval($request -> post('tgs_id'));
		}
		
		
		
		
		$sql_query_data['base']['isIndex'] 	= $request -> post('isIndex')?1:0;
		$sql_query_data['base']['isHidden'] = $request -> post('isHidden')?1:0;
		$sql_query_data['base']['in_sitemap'] 	= $request -> post('in_sitemap')?1:0;
	
		$sql_query_data['base']['typeMenu'] = $request -> post('typeMenu') ?  $request -> post('typeMenu') : 0;
		$sql_query_data['base']['pid'] = $request -> post('pid') ? $request -> post('pid') : 0;

		if($request -> post('dateAdd')){
			$sql_query_data['base']['dateAdd'] 	= trim($request -> post('dateAdd'));
		}else{
			$sql_query_data['base']['dateAdd']	= date('Y-m-d H:i:s');
		}
		
		if($request -> post('datePub')){
			$sql_query_data['base']['datePub'] 	= trim($request -> post('datePub'));
		}else{
			$sql_query_data['base']['datePub']	= date('Y-m-d H:i:s');
		}
		
		if(!empty($this -> errors)){
			return false;
		}
		return $sql_query_data;
	}
}
