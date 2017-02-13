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
		$pages = Pages::getPageWeb($sqlParams);
		
		// Хлебные крошки
		$breadcrumbs = [];
		
		if(!empty($pageMenu)){
			$tree = new Trees(PREFIX.'_menu');
			$tree -> getParents($pageMenu -> id);
			$bread = $tree -> catList;
			
			if(count($bread) > 0){
			
				$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang')).'">Главная</a>';
				if(is_array($bread)){
					foreach($bread as $item){
						
						if(empty($item -> typeMenu) && $item -> isIndex !='1'){
							$aliasMenu = 'javascript:void(0)';
						}elseif(empty($item -> typeMenu) && $item -> isIndex =='1'){
							$aliasMenu = get_url(config('lang.weblang'));
						}else{
							$aliasMenu = get_url(config('lang.weblang'),$item -> typeMenu,$item -> alias);
						}
						
						$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.$aliasMenu .'">'.$item -> title.'</a>';
					}
					
				}
			}
			
		}
		

		$vars = [
			'title' => isset($pages -> title) ? $pages -> title : '',
			'metaK' => isset($pages -> metaK) ? $pages -> metaK : '',
			'metaD' => isset($pages -> metaD) ? $pages -> metaD : '',
			'result' => $pages,
			'breadcrumbs' => $breadcrumbs,
			'segment' => $request -> segment(1),

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