<?php namespace App\Controllers\Web;

use Cms\Controller;
use Cms\Request;
use Cms\Api\SearchModel;
use Cms\Libs\Pagination;

class Search extends BaseController{
    
    
	public function index(){
	   
		$request = new Request();
		
		if($request -> get('q')){
			$query = $request -> get('q');
		}else{
			$query = '';
		}
		
		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 20;
	   
	   
		if($request -> get('date')){
			$date = $request -> get('date');
		}else{
			$date = '';
		}
	   
		$sqlParams = [
			'start' => ($page-1)*$limit,
			'limit' => $limit,
			'query' => $query
		];
		
		if(!empty($date)){
		   
		   $date = explode('-', $date);
		   $month = $date[1];
		   $sqlParam['conditions'][] = ' DATE_FORMAT(dateAdd,\'%m\') = '.$month;

		   $date = implode('-', $date);
		   
		}

		$searchModel = new SearchModel();
		$searchModel -> image_table = 'a_shop_images';
		$result = $searchModel -> getSearch($sqlParams);
		$totalSearch = $searchModel -> getSearchCount($sqlParams);
	   
		
		$pagination = new Pagination();
		$pagination -> limit = $limit;
		$pagination -> page = $page;
		$pagination -> url = get_url(config('lang.weblang'), 'search?q='.htmlentities($query).'&page={page}');
		$pagination -> total = $totalSearch;
	   
		// Хлебные крошки
		$breadcrumbs = [];
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang')).'">Главная</a>';
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url('search?q='.htmlentities($query)).'">Результаты поиска::'.htmlentities($query).'</a>';
	   
		$vars = [
			'title' 		=> 'Результаты поиска::'.htmlentities($query),
			'result' 		=> $result,
			'breadcrumbs' 	=> $breadcrumbs,
			'pagesList' 	=> $pagination -> createLinks(),
			'date' 			=> $date,
			'segment' 		=> $request -> segment(1)
			
		];
		

		return $this -> view -> show('public/news/search', $vars);
	}
	
}