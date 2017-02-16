<?php namespace App\Controllers\Web;

use Cms\Controller;
use Cms\Request;
use Cms\Api\Shop;
use Cms\Api\Articles;
use Cms\Api\NewsModel;
use Cms\Api\ArticlesModel;



class Home extends BaseController {


   public function index(){

		$request = new Request();

		$page  = is_numeric($request -> get('page')) ? $request -> get('page') : 1;
		$limit = 100;


		$sqlParam = [
			'limit'=>$limit,
			'orderby'=> [
				'dateAdd'=>'desc'
			]
		];


		// Выводим слайдеры
		$shop = new Shop(PREFIX.'_images');
		$shop -> mod    = 'slides';
		$shop -> imgTbl = PREFIX.'_shop_images';
		$slides = $shop -> getListImages($sqlParam);

		$sqlParam = [
			'limit'=>0,
			'orderby'=> [
				'dateAdd'=>'desc'
			]
		];
		
		// Выводим статьи для главной
		$pages = new Shop(PREFIX.'_news');
		$pages -> mod    = 'inIndex';
		$pages -> imgTbl = PREFIX.'_shop_images';
		$inIndex = $pages -> getList($sqlParam);

		$vars = [
			'slides' 		=> $slides,
			'inindex' 		=> $inIndex,
			'page' 			=> 'index'
		];


		$meta = config('settings.settings');

		if(isset($meta['title'])){
			$vars['title'] = $meta['title'];
		}else{
			$vars['title'] = '';
		}
		if(isset($meta['description'])){
			$vars['metaD'] = $meta['description'];
		}else{
			$vars['metaD'] = '';
		}
		if(isset($meta['keywords'])){
			$vars['metaK'] = $meta['keywords'];
		}else{
			$vars['metaK'] = '';
		}

		return $this -> view -> show('public/inIndex/inIndex', $vars);
	}

	public function index2 () {
		// формируем новости
		
		$newsModel = new NewsModel();
		
		
		$news_1 = $newsModel -> getNews([
			'menu_id' => 120,
			'limit' => 1
		]);

		$news_2 = $newsModel -> getNews([
			'menu_id' => 121,
			'limit' => 1
		]);
		
		$articlesModel = new ArticlesModel();
		$news_3 = $articlesModel -> getArticles([
			'menu_id' => 129,
			'limit' => 1
		]);
		
		$treatment = $articlesModel -> getArticle([
			'menu_id' => 438,
		]);
		
		$vars = [
			'title'     => __('bureau_attorneys'),
			'news_1'    => $news_1,
			'news_2'    => $news_2,
			'news_3'    => $news_3,
			'treatment' => !empty($treatment) ? $treatment : '',
		];

		return $this -> view -> show('public/inIndex/buro', $vars);
	}
}

