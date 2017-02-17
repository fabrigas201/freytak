<?php namespace App\Controllers\Web;

use Cms\Controller;
use Cms\Request;
use Cms\Api\Shop;
use Cms\Api\Pages;
use Cms\Libs\Trees;
use Cms\Api\ImagesModel;
use Cms\Libs\Pagination;
use Cms\Api\HistoryModel;
use Cms\Exception\NotFoundException;


class History extends BaseController{
    
	public function index(){
		$request = new Request();
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 20;
		
		
		if($request -> get('date')){
			$date = $request -> get('date');
		}else{
			$date = '';
		}
		
		$sqlParams = [
			'start' 	=> ($page-1)*$limit,
			'limit' 	=> $limit,
			'date' 		=> $date,
		];
		
		
		$historyModel = new HistoryModel();
		$historyModel -> image_table = 'a_shop_images';
		$resultsTotal = $historyModel -> getHistoryCount($sqlParams);
		$results = $historyModel -> getHistory($sqlParams);
		
		$pagination = new Pagination();
		$pagination -> limit = $limit;
		$pagination -> page = $page;
		$pagination -> url = get_url(config('lang.weblang'), 'history?page={page}');
		$pagination -> total = $resultsTotal;
		
		
		// Хлебные крошки
		$breadcrumbs = [];
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang')).'">'.__('home').'</a>';
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang'), 'history').'">'.__('GALLERY_OF_RUSSIAN_LAW_OF_FAME').'</a>';
		
		
		$langs = [];
		
		if(count(config('lang.langs')) > 0){
			foreach(config('lang.langs') as $alias){
				$langs[] = [
					'alias' => 'history',
					'lang' => $alias['key'],
					'href' => get_url($alias['key'], 'history')
				];
			}
		}
		

		$vars = [
			'title' => stripslashes(__('GALLERY_OF_RUSSIAN_LAW_OF_FAME')),
			'metaK' => '',
			'metaD' => '',
			'results' => $results,
			'breadcrumbs' => $breadcrumbs,
			'segment' => $request -> segment(1),
			'pagesList'		=> $pagination -> createLinks(),
			'langs' => $langs

		];
		
		return $this -> view -> show('public/news/history', $vars);
	}
	
	
	
	public function read(){
	   
		$request = new Request();
		$id = $request -> segment(3);
		
		if($request -> get('date')){
			$date = $request -> get('date');
		}else{
			$date = '';
		}
	   
		$sqlParams = [
			'menu_id' => $id
		];
	   
		if(!empty($date)){
		   
		   $date = explode('-', $date);
		   $month = $date[1];
		   $sqlParam['conditions'][] = ' DATE_FORMAT(dateAdd,\'%m\') = '.$month;
		   
		   $date = implode('-', $date);
		   
		}
	   
		$historyModel = new HistoryModel();
		$historyModel -> image_table = 'a_shop_images';
		$result = $historyModel -> getHistoryItem($sqlParams);
		
		
		$aliases  = $historyModel -> getAliasHistory($result -> id);
	   
		$langs = [];
		
		if(count($aliases) > 0){
			foreach($aliases as $alias){
				$langs[] = [
					'alias' => $alias -> alias,
					'lang' => $alias -> lang,
					'href' => get_url($alias -> lang, 'history', $alias -> alias)
				];
			}
		}
		
		if(empty($result)){
			return new NotFoundException;
		}
		

		$breadcrumbs = [];
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang')).'">'.__('home').'</a>';
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang'), 'history').'">'.__('GALLERY_OF_RUSSIAN_LAW_OF_FAME').'</a>';
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang'),'history',$result -> alias).'">'.stripslashes($result -> title).'</a>';

	   
		$vars = [
			'title' 		=> stripslashes($result -> title),
			'result' 		=> $result,
			'date' 			=> $date,
			'segment' 		=> $request -> segment(1),
			'metaK' 		=> stripslashes($result -> metaK),
			'metaD' 		=> stripslashes($result -> metaD),
			'breadcrumbs' 	=> $breadcrumbs,
			'langs'			=> $langs
			
			
			
		];
		
		if(!empty($result -> cover)){
			
			if(isset($result -> cover -> name)){
				$vars['cover'] = asset_cache($result -> cover -> name, ['width' => 232, 'height' => 171]);
			}else{
				$vars['cover'] = '';
			}
			if(isset($result -> cover -> descr)){
				$vars['descr'] = $result -> cover -> descr;
			}else{
				$vars['descr'] = '';
			}
			
		}
		
		
		return $this -> view -> show('public/other/text', $vars);
	}
	
}