<?php namespace App\Controllers\Web;

use Cms\DB;
use Cms\Controller;
use Cms\Request;
use Cms\Libs\Trees;
use Cms\Api\Shop;
use Cms\Api\NewsModel;
use Cms\Libs\Pagination;
use Cms\Api\Pages;
use Cms\Api\TagsSystem;

use Cms\Exception\NotFoundException;

class News extends BaseController{
	
	public function index(){
		$request = new Request();
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 20;
		
		$id = $request -> segment(3);
		
		$pageMenu = Pages::getPage($id);
		
		
		if($request -> get('date')){
			$date = $request -> get('date');
		}else{
			$date = '';
		}
		
		$sqlParams = [
			'menu_id' 	=> $id,
			'start' 	=> ($page-1)*$limit,
			'limit' 	=> $limit,
			'date' 		=> $date,
			'orderby' => [
				'posi' => 'ASC'
			],
		];
		
		
		$newsModel = new NewsModel();
		
		$resultsTotal = $newsModel -> getNewsCount($sqlParams);
		$results = $newsModel -> getNews($sqlParams);
		
		if(empty($results)){
			return new NotFoundException;
		}
		
		$pagination = new Pagination();
		$pagination -> limit = $limit;
		$pagination -> page = $page;
		$pagination -> url = get_url(config('lang.weblang'), 'news' , $pageMenu -> alias.'?page={page}');
		$pagination -> total = $resultsTotal;
		
		
		
		$tree = new Trees(PREFIX.'_menu');
		$tree -> getParents($pageMenu -> id);
		$bread = $tree -> catList;

		// Хлебные крошки
		$breadcrumbs = [];
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang')).'">'.__('home').'</a>';
		if(is_array($bread)){
			foreach($bread as $item){
				
				if(empty($item -> typeMenu) && $item -> isIndex !='1'){
					$aliasMenu = 'javascript:void(0)';
				}elseif(empty($item -> typeMenu) && $item -> isIndex =='1'){
					$aliasMenu = get_url(config('lang.weblang'));
				}else{
					$aliasMenu = get_url(config('lang.weblang'), $item -> typeMenu.'/'.$item -> alias);
				}
				
				$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.$aliasMenu .'">'.$item -> title.'</a>';
			}
			
		}
		
		$vars = [
			'title' => $pageMenu -> title,
			'metaK' => $pageMenu -> metaK,
			'metaD' => $pageMenu -> metaD,
			'page' => $pageMenu,
			'results' => $results,
			'breadcrumbs' => $breadcrumbs,
			'segment' => $request -> segment(1),
			'mobth' => true,
			'pagesList'		=> $pagination -> createLinks(),

		];
		
		return $this -> view -> show('public/news/list3', $vars);
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
		   //$sqlParam['conditions'][] = ' DATE_FORMAT(dateAdd,\'%m\') = '.$month;
		   
		   $date = implode('-', $date);
		   
		}
	   
		$newsModel 	= new NewsModel();
		$newsModel	-> image_table = 'a_shop_images';
		$result 	= $newsModel -> getNewsItem($sqlParams);
	   
		if(empty($result)){
			return new NotFoundException;
		}
		
		
		
		$itemPrev = $newsModel -> ItemPrev($sqlParams);
		$itemNext = $newsModel -> itemNext($sqlParams);

		
		$tree = new Trees(PREFIX.'_menu');
		$tree -> getParents($result -> categories);
		$bread = $tree -> catList;
		
		
		// Хлебные крошки
		$breadcrumbs = [];
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang')).'">'.__('home').'</a>';
		if(is_array($bread)){
			foreach($bread as $item){
				
				if(empty($item -> typeMenu) && $item -> isIndex !='1'){
					$aliasMenu = 'javascript:void(0)';
				}elseif(empty($item -> typeMenu) && $item -> isIndex =='1'){
					$aliasMenu = get_url(config('lang.weblang'));
				}else{
					$aliasMenu = get_url(config('lang.weblang'),$item -> typeMenu.'/'.$item -> alias);
				}
				
				$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.$aliasMenu .'">'.$item -> title.'</a>';
			}
			
		}
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang'),'item',$result -> alias).'">'.$result -> title.'</a>';

	   
		$vars = [
			'title' => $result -> title,
			'page' => $result,
			'breadcrumbs' => $breadcrumbs,
			'date' => $date,
			'segment' => $request -> segment(1),
			//'month'		=> true,
			'metaK' => $result -> metaK,
			'metaD' => $result -> metaD,
			'prev_item' => $itemPrev,
			'next_item' => $itemNext,
			
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
		
		
		return $this -> view -> show('public/news/info', $vars);
	}
	
}