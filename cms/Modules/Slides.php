<?php namespace Cms\Modules;

use Cms\DB;
use Cms\Modules;
use Cms\Libs\Wysiwyg;
use Cms\Api\Shop;
use Cms\Libs\Pagination;
use Cms\Api\Translit;
use Cms\Api\Upload;
use Cms\Request;


class Slides extends Modules {

	public function index(){
		
		/* $query = DB::query('SELECT * FROM `a_images`');
		foreach($query -> rows as $item){
			$sql_query['images_id'] = $item -> id;
			$sql_query['alias'] = empty($item -> alias) ? $item -> id : $item -> alias;
			$sql_query['title'] = $item -> name;
			$sql_query['descr'] = $item -> descr;
			$sql_query['descrfull'] = $item -> descrfull;
			$sql_query['metaK'] = $item -> metaK;
			$sql_query['metaD'] = $item -> metaD;
			$sql_query['lang'] = 'ru';
			DB::insert('a_images_description', $sql_query);
		}  */
		
		
		$request = new Request();
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 100;
		
		$shop = new Shop(PREFIX.'_images');
		$shop -> imgTbl = PREFIX.'_shop_images';
		$shop -> mod    = 'slides';
		
		
		$sqlParam = [
			'start'=>($page-1)*$limit,
			'limit'=>$limit,
			'orderby'=> [
				'dateAdd'=>'desc'
			]
		];
		
		$allNews 		= $shop -> getListImages($sqlParam);
		$total 			= $shop -> getCountRec($sqlParam);
		
		$pagination = new Pagination();
		$pagination -> limit = $limit;
		$pagination -> page = $page;
		$pagination -> url = get_url('/admin/?mod=slides&page={page}');
		$pagination -> total = $total;
		
		
		$data = [
			'title'		=> 'Баннеры',
			'result' 	=> $allNews,
			'pagesList' => $pagination -> createLinks()
		];
		return $this -> view -> show('admin/news/list', $data);
		
	}
	
	public function add(){
		
		$request = new Request();
		$id = $request -> get('id');
		$mod = $request -> get('mod');
		$shop = new Shop(PREFIX.'_images');
		
		$shop -> mod    = 'slides';
		$l = $shop -> getById($id);
		
		$shop -> imgTbl = PREFIX.'_shop_images';
		
		$vars = [];
		
		if(config('lang.langs')){
			foreach(config('lang.langs') as $item){
				$vars['langs'][$item['key']]['name']  			= $_POST ? stripslashes($_POST['field'][$item['key']]['name'])  		: '';
				$vars['langs'][$item['key']]['metaD'] 			= $_POST ? stripslashes($_POST['field'][$item['key']]['metaD']) 		: '';
				$vars['langs'][$item['key']]['metaK'] 			= $_POST ? stripslashes($_POST['field'][$item['key']]['metaK']) 		: '';
				$vars['langs'][$item['key']]['alias'] 			= $_POST ? stripslashes($_POST['field'][$item['key']]['alias']) 		: '';
				$vars['langs'][$item['key']]['descrfull'] 		= $_POST ? stripslashes($_POST['field'][$item['key']]['descrfull']) 	: '';
			}
		}
		
		$vars['posi'] 		= $_POST ? $request -> post('posi')			: 	'';
		$vars['dateAdd']	= $_POST ? $request -> post('dateAdd')		:	date('Y-m-d H:i:s');
		$vars['mod'] 		= $_POST ? $request -> post('mod')			:	'';
		
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateData($request) != false){
			
			$sql_query_data =  $this -> validateData($request);
			
			if(isset($sql_query_data['base'])){
				$last_insert_id = DB::insert($shop -> mainTbl, $sql_query_data['base']);
			}
			
    		if(isset($sql_query_data['langs'])){
				foreach($sql_query_data['langs'] as $lang => $description){
					DB::insert('a_images_description', array_merge($description, ['lang' => $lang, 'images_id' => $last_insert_id]));
				}
			}

			if(isset($last_insert_id)){

				$imagesInfo = [
					'modid'  => $last_insert_id,
					'mod'    => addslashes($mod),
					'path' => '/i/other/',
				];
				
				if(isset($_FILES['image'])){
					$generator = new \FileUpload\FileNameGenerator\Random(10);
					$validator = new \FileUpload\Validator\Simple('10M', ['image/png', 'image/jpg', 'image/jpeg', 'image/gif']);
					$pathresolver = new \FileUpload\PathResolver\Simple(SITE_DIR.'i/other');
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
				redirect('admin/?mod='.$request -> get('mod').'&m='.($request -> get('action') == 'add'?1:2));
			else
				redirect('admin/?mod='.$request -> get('mod').'&action=edit&id='.$last_insert_id.'&m='.($request -> get('action') == 'add'?1:2));
		}

		
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
		
		
		$data = [
			'title' => 'Добавить слайд на главной',
			'wysiwygScript' => $this -> wysiwyg -> getScript(),
			'forms' => $forms,
			'errors' => $this -> getErrors(),
			'save_and_list' => 'Добавить и вернуться к списку',
			'save_and_edit' => 'Добавить и продолжить редактирование',
			'vars' => $vars
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
		

		return $this -> view -> show('admin/other/addEditSlides', $data);
		
	}
	
	public function edit(){

		$vars = [];
		
		$request = new Request();
		$id = $request -> get('id');
		$mod = $request -> get('mod');
		
		
		$shop = new Shop(PREFIX.'_images');
		$shop -> imgTbl = PREFIX.'_shop_images';
		$shop -> mod    = 'slides';

		$result = $shop -> getByImagesId($id);
		
		
		if(isset($result['langs'])){
			foreach($result['langs'] as $item){
				$vars['langs'][$item -> lang]['name']  			= $_POST ? $_POST['field'][$item -> lang]['name']  		: stripslashes($item -> title);
				$vars['langs'][$item -> lang]['metaD'] 			= $_POST ? $_POST['field'][$item -> lang]['metaD'] 		: stripslashes($item -> metaD);
				$vars['langs'][$item -> lang]['metaK'] 			= $_POST ? $_POST['field'][$item -> lang]['metaK'] 		: stripslashes($item -> metaK);
				$vars['langs'][$item -> lang]['alias'] 			= $_POST ? $_POST['field'][$item -> lang]['alias'] 		: stripslashes($item -> alias);
				$vars['langs'][$item -> lang]['descr'] 			= $_POST ? $_POST['field'][$item -> lang]['descr'] 		: stripslashes($item -> descr);
				$vars['langs'][$item -> lang]['descrfull'] 		= $_POST ? $_POST['field'][$item -> lang]['descrfull'] 	: stripslashes($item -> descrfull);
			}
		}
		
		if(isset($result['base'])){
			$vars['posi'] 		= $_POST ? $request -> post('posi')			: 	$result['base'] -> posi;
			$vars['dateAdd']	= $_POST ? $request -> post('dateAdd')		:	($result['base'] -> dateAdd ? $result['base'] -> dateAdd : date('Y-m-d H:i:s'));
			$vars['mod'] 		= $_POST ? $request -> post('mod')			:	$result['base'] -> mod;
			
		}
		
		$sql_query_data = [];
		if($request -> isPost() && $request -> post('save') == '1' && $this -> validateData($request) != false){
			
			$sql_query_data =  $this -> validateData($request);
			

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

			$imgPosis = array();
			
			if(is_array($request -> post('imgposi'))){
				foreach($_POST['imgposi'] as $k=>$v){
					if(md5($v)!=$_POST['imgposiMD5'][$k])
						$imgPosis[$k]=addslashes($v);
				}
			}


			$imgUrls=array();
			if(is_array($request -> post('imgurlMD5'))){
				foreach($_POST['imgurlMD5'] as $k=>$v){
					$imgurlVal=$_POST['imgurl'][$k]?1:0;
					if($v!=md5($imgurlVal))
						$imgUrls[$k]=$imgurlVal;
				}
			}
			//end проверка данных

    		//добавляем картинки для карточки товара
			$imagesInfo = [
				'modid'  => $id,
				'mod'    => addslashes($mod),
				'path' => '/i/other/',
				'imgdelIds' => $imgdel,
				'newCoverId'=> $newCoverId,
				'imgDescr' => $imgdescr
				
			];
			
			if(isset($_FILES['image'])){
				$generator = new \FileUpload\FileNameGenerator\Random(10);
				$validator = new \FileUpload\Validator\Simple('10M', ['image/png', 'image/jpg', 'image/jpeg', 'image/gif']);
				$pathresolver = new \FileUpload\PathResolver\Simple(SITE_DIR.'i/other');
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
					DB::update(PREFIX."_shop_images", ['descr' => $v], ['id' => $k]);
				}
			}

			if(isset($sql_query_data['langs'])){
				DB::delete('a_images_description', ['images_id' => $id]);
				
				foreach($sql_query_data['langs'] as $lang => $description){
					DB::insert('a_images_description', array_merge($description, ['lang' => $lang, 'images_id' => $id]));
				}
			}
			if(isset($sql_query_data['base'])){
				DB::update($shop -> mainTbl, $sql_query_data['base'], ['id' => $id]);
			}
			
			//меняем/заносим позицию картинки
			if(count($imgPosis)>0 && is_array($imgPosis)){
				foreach($imgPosis as $k=>$v){
					DB::update($shop->imgTbl, ['posi' => $v], ['id' => $k]);
				}
			}
			//меняем/заносим ссылку картинки
			if(count($imgUrls)>0 && is_array($imgUrls)){
				foreach($imgUrls as $k=>$v){
					DB::update($shop->imgTbl, ['uploadinfo' => $v], ['id' => $k]);
				}
			}
			
			if($request -> post('goto')){
				redirect('admin/?mod='.$_GET['mod'].'&m='.($_GET['action']=='add'?1:2));
			}else{
				redirect('admin/?mod='.$_GET['mod'].'&action=edit&id='.$id.'&m='.($_GET['action']=='add'?1:2));
			}
		}
		
		
		$imagesFormOpt = [
			'pref'      => 'tm_',
			'path' 		=> '/i/other/',
			'name'      => 'image',
			'label'     => 'Images',
			//'descr'     =>'Image',
			'showDel'   => 1,
			'showRadio' => 1,
			'showDescr' => 1, //комментарий к картинке
			'multy'     => 0
		];
	    
		$imagesFormOpt['images'] = ($result['base']->images ? $result['base']->images : '');
		$forms['imagesForm'] = imageForm($imagesFormOpt);
		
		$data = [
			'title' 		=> 'Редактирование слайда на главной',
			'wysiwygScript' => $this -> wysiwyg -> getScript(),
			'vars' 			=> $vars,
			'forms' 		=> $forms,
			'errors'		=> $this -> getErrors(),
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
		
		return $this -> view -> show('admin/other/addEditSlides', $data);
		
	}
	
	
	public function del(){
		$request = new Request();
		$id = $request -> get('id');
		
		$shop = new Shop(PREFIX.'_images');
		$shop -> imgTbl = PREFIX.'_shop_images';
		$shop -> mod    = 'slides';
		
		$shop -> delGoodsById($id);

		redirect('admin/?mod='.$request -> get('mod').'&m=3');
		
	}
}
