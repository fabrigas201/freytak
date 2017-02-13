<?php namespace App\Controllers\Web;

use Cms\DB;
use Cms\Libs\Trees;
use Cms\Controller;
use Cms\Request;
use Cms\Api\Shop;
use Cms\Api\Pages;
use Cms\Api\ArticlesModel;
use Cms\Libs\Pagination;

use Cms\Exception\NotFoundException;

class Roster extends BaseController{
    
	
	
	public function index(){
		$request = new Request();
		
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 20;
		
		if($request -> segment(4) == null){
			$id = $request -> segment(3);
		}else{
			$id = $request -> segment(4);
		}
		
		
		if($request -> get('date')){
			$date = $request -> get('date');
		}else{
			$date = '';
		}
		
		if(!empty($date)){
		   
		   $date = explode('-', $date);
		   $month = $date[1];
		   $sqlParam['conditions'][] = ' DATE_FORMAT(dateAdd,\'%m\') = '.$month;
		   
		   $date = implode('-', $date);
		   
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
		
		
		$articlesModel = new ArticlesModel();
		
		$resultsTotal = $articlesModel -> getArticlesCount($sqlParams);
		$results = $articlesModel -> getArticles($sqlParams);
		
		
		$pageMenu = Pages::getPage($id);
		
		$tree = new Trees(PREFIX.'_menu');
		$tree -> getParents($pageMenu -> id);
		$bread = $tree -> catList;


		$pagination = new Pagination();
		$pagination -> limit = $limit;
		$pagination -> page = $page;
		$pagination -> url = get_url(config('lang.weblang'), 'articles' , $pageMenu -> alias.'?page={page}');
		$pagination -> total = $resultsTotal;

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
					$aliasMenu = get_url(config('lang.weblang'),$item -> typeMenu,$item -> alias);
				}
				
				$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.$aliasMenu .'">'.$item -> title.'</a>';
			}
			
		}
		
		$vars = [
			'title' 		=> $pageMenu -> title,
			'metaD' 		=> $pageMenu -> metaD,
			'metaK' 		=> $pageMenu -> metaK,
			'results' 		=> $results,
			'breadcrumbs' 	=> $breadcrumbs,
			'segment' 		=> $request -> segment(1),
			'pagesList'		=> $pagination -> createLinks(),
			//'subnews'		=> $subnews,
			'date'			=> $date,
			
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
		
		return $this -> view -> show('public/articles/list3', $vars);
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
	   
		$articlesModel = new ArticlesModel();
		
		$result = $articlesModel -> getArticle($sqlParams);
	   
		if(empty($result)){
			return new NotFoundException;
		}
		
		
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
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang'),'item/'.$result -> alias).'">'.$result -> title.'</a>';

		$vars = [
			'title' => $result -> title,
			'result' => $result,
			'breadcrumbs' => $breadcrumbs,
			'date' => $date,
			'segment' => $request -> segment(1),
			'metaK' => $result -> metaK,
			'metaD' => $result -> metaD,
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