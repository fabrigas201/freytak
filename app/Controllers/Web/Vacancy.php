<?php namespace App\Controllers\Web;

use Cms\DB;
use Cms\Controller;
use Cms\Libs\Trees;
use Cms\Request;
use Cms\Api\Shop;
use Cms\Api\Pages;
use Cms\Api\Articles;
use Cms\Api\VacancyModel;
use Cms\Libs\Pagination;

use Cms\Exception\NotFoundException;

class Vacancy extends BaseController{
	
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
		
		
		$vacancyModel = new VacancyModel();
		
		$resultsTotal = $vacancyModel -> getVacancyCount($sqlParams);
		$results = $vacancyModel -> getVacancy($sqlParams);
		
		
		$pagination = new Pagination();
		$pagination -> limit = $limit;
		$pagination -> page = $page;
		$pagination -> url = get_url(config('lang.weblang'), 'vacancy' , $pageMenu -> alias.'?page={page}');
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
				
				$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.$aliasMenu .'">'.stripslashes($item -> title).'</a>';
			}
			
		}
		
		$vars = [
			'title' => stripslashes($pageMenu -> title),
			'metaK' => stripslashes($pageMenu -> metaK),
			'metaD' => stripslashes($pageMenu -> metaD),
			'page' => $pageMenu,
			'results' => $results,
			'breadcrumbs' => $breadcrumbs,
			'segment' => $request -> segment(1),
			'mobth' => true,
			'pagesList'		=> $pagination -> createLinks(),

		];
		
		return $this -> view -> show('public/vacancy/list3', $vars);
	}
	
	
	/* public function read(){
	   
		$request = new Request();
		$id = $request -> segment(2);
		
		if($request -> get('date')){
			$date = $request -> get('date');
		}else{
			$date = '';
		}
	   
		$shop = new Shop(PREFIX.'_news');
		$shop -> imgTbl	= PREFIX.'_shop_images';
		$shop -> mod = 'pages';

		$sqlParam = [];
	   
		if(!empty($date)){
		   
		   $date = explode('-', $date);
		   $month = $date[1];
		   $sqlParam['conditions'][] = ' DATE_FORMAT(dateAdd,\'%m\') = '.$month;
		   
		   $date = implode('-', $date);
		   
		}
	   
		$l = $shop->getById($id);

		// Хлебные крошки
		$breadcrumbs = [];
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url().'">Главная</a>';
		if(isset($l -> mod)){
			switch($l -> mod){
				case  'news' : 
					$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url('news').'">Новости бюро</a>';
					$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url('news/'.$l -> alias).'">'.$l -> name.'</a>';
				break;
				case  'news2' : 
					$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url('news-right').'">правовые новости</a>';
					$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url('news-right/'.$l -> alias).'">'.$l -> name.'</a>';
				break;
				default : $breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url('news').'">Новости бюро</a>'; break;
			}
		}
	
	   //dd_die($l);
	   
		$vars = [
			'title' => $l -> name,
			'l' => $l,
			'breadcrumbs' => $breadcrumbs,
			'date' => $date,
			'segment' => $request -> segment(1),
			
		];
		
		if(!empty($l -> cover)){
			
			if(isset($l -> cover -> name)){
				$vars['cover'] = asset_cache($l -> cover -> name, ['width' => 232, 'height' => 171]);
			}else{
				$vars['cover'] = '';
			}
			if(isset($l -> cover -> descr)){
				$vars['descr'] = $l -> cover -> descr;
			}else{
				$vars['descr'] = '';
			}
			
		}
		
		
		return $this -> view -> show('public/news/info', $vars);
	} */
	
}