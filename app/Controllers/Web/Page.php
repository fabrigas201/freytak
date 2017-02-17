<?php namespace App\Controllers\Web;


use Cms\Request;
use Cms\Api\Shop;
use Cms\Libs\Trees;
use Cms\Api\Pages;
use Cms\Controller;
use Cms\Api\Articles;
use Cms\Api\TagsSystem;
use Cms\Libs\Pagination;
use Cms\Exception\NotFoundException;


class Page extends BaseController{

	public $image_table;

	public function read(){

		$request = new Request();
		$id = $request -> segment(3);
		
		if($request -> get('date')){
			$date = $request -> get('date');
		}else{
			$date = '';
		}
		
		$sqlParams = [
			'menu_id' 	=> $id,
			'date' 		=> $date
		];
		
		
		$pageMenu = Pages::getPage($id);
		$page = new Pages();
		$page -> image_table = 'a_shop_images';
		$pages = $page -> getPageWeb($sqlParams);
		
		if(empty($pages) && empty($pageMenu)){
			return new NotFoundException;
		}
		
		$aliases  = $page -> getAliasPages($pageMenu -> menu_id);
		
		$langs = [];
		
		if(count($aliases) > 0){
			foreach($aliases as $alias){
				$langs[] = [
					'alias' => $alias -> alias,
					'lang' => $alias -> lang,
					'href' => get_url($alias -> lang, 'page', $alias -> alias)
				];
			}
		}
		
		
		// Хлебные крошки
		$breadcrumbs = [];
		
		if(!empty($pageMenu)){
			$tree = new Trees(PREFIX.'_menu');
			$tree -> getParents($pageMenu -> id);
			$bread = $tree -> catList;
			
			if(count($bread) > 0){
			
				$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang')).'">'.__('home').'</a>';
				if(is_array($bread)){
					foreach($bread as $item){
						
						if(empty($item -> typeMenu) && $item -> isIndex !='1'){
							$aliasMenu = 'javascript:void(0)';
						}elseif(empty($item -> typeMenu) && $item -> isIndex =='1'){
							$aliasMenu = get_url($item -> lang);
						}else{
							$aliasMenu = get_url($item -> lang,$item -> typeMenu,$item -> alias);
						}
						
						$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.$aliasMenu .'">'.$item -> title.'</a>';
					}
					
				}
			}
			
		}
		
		$vars = [
			'title' => isset($pageMenu -> title) ? stripslashes($pageMenu -> title) : '',
			'metaK' => isset($pageMenu -> metaK) ? stripslashes($pageMenu -> metaK) : '',
			'metaD' => isset($pageMenu -> metaD) ? stripslashes($pageMenu -> metaD) : '',
			'result' => $pages,
			'breadcrumbs' => $breadcrumbs,
			'segment' => $request -> segment(1),
			'langs' => $langs

		];
		

		if(!empty($pages -> cover)){

			if(isset($pages -> cover -> name)){
				$vars['cover'] = asset_cache($pages-> cover -> name, ['width' => 232, 'height' => 171]);
			}else{
				$vars['cover'] = '';
			}
			if(isset($pages -> cover -> descr)){
				$vars['descr'] = $pages -> cover -> descr;
			}else{
				$vars['descr'] = '';
			}
		}


		return $this -> view -> show('public/other/text', $vars);
	}
}