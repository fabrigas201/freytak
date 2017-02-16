<?php namespace App\Controllers\Web;

use Cms\Controller;
use Cms\Request;
use Cms\Libs\Trees;
use Cms\View;
use Cms\Config;
use Cms\Api\NewsModel;
use Cms\Api\HistoryModel;
use Cms\Api\ArticlesModel;
use Cms\Language;


class BaseController extends Controller {

	public $view;

	public function __construct(){
		parent::__construct();
		$request = new Request();
		$language = $request -> segment(1);
		
		
		if(empty($language)){
			$language = config('lang.weblang');
		}

		$langs = config('lang.langs');

		// Язык по умолчанию
		$DefaultLang = config('lang.weblang');

		$langs_collection = [];
		
		if(is_array($langs)){
			foreach($langs as $item){
				$langs_collection[] = $item['key'];
			}
		}

		// Если язык уже выбран и сохранен в сессии отправляем его скрипту
		if(isset($_SESSION['lang'])){
			if(!in_array($_SESSION['lang'], $langs_collection)) {
				$_SESSION['lang'] = $DefaultLang;
			}else{
				$_SESSION['lang'] = $language;
			}
		}
		// Выбранный язык отправлен скрипту через GET
		if(isset($language)) {
			if(!in_array($language, $langs_collection)){
				$_SESSION['lang'] = $DefaultLang;
			}
			else {
				$_SESSION['lang'] = $language;
			}
		}
		
		Config::set('lang.weblang', $_SESSION['lang']);
		
		Language::load('text');
		
		// Для меню 
		$sqlParams = [
			'lang' => config('lang.weblang'),
			'isHidden' => false
		];
		
		$menu = new Trees();
		$menu -> noPrefix = true;
		// Верхнее меню
		$topMenu=$menu->getTreeMenu(0, $sqlParams);

		// Меню для футера
		$menu -> getChilds(0, $sqlParams);
		$menuFooter = $menu -> getCatList();
		//sort($menuFooter);
		
		// Подкатегория "Юридические услуги они же Практики"
		$menu = new Trees();
		$menu -> noPrefix = true;
		$menu -> getChilds(113, $sqlParams);
		$practics = $menu -> getCatList();
		
		// Подкатегория "БЮРО"
		$menu = new Trees();
		$menu -> noPrefix = true;
		$menu -> getChilds(122, $sqlParams);
		$subBuro = $menu -> getCatList();
		
		// Подкатегория "ИНФОЦЕНТР"
		$menu = new Trees();
		$menu -> noPrefix = true;
		$menu -> getChilds(117, $sqlParams);
		$subInfo = $menu -> getCatList();
		
		
		
		// Выводим События для календаря
		$sqlParams = [];
		$sqlParams['limit'] = 5;
		$sqlParams['sort'] = 'date_events_desc';
		$sqlParams['inCalendar'] = '1';
		
		// События для календаря
		$event = new NewsModel();
		$events = $event -> getNews($sqlParams);

		// Наши проекты
		$sqlParams = [];
		$sqlParams['menu_id'] = 115;
		$sqlParams['limit'] = 4;
		//$sqlParams['sort'] = 'date_events_desc';
		//$sqlParams['inCalendar'] = '1';
		
		$myProjects = new ArticlesModel();
		$projects = $myProjects -> getArticles($sqlParams);

		// Галлерея исторической слава фотографии
		$historyModel = new HistoryModel();
		$historyModel -> image_table = 'a_shop_images';
		$historyAvatars = $historyModel -> getHistory();
		shuffle($historyAvatars);
		
		// Определение текущей страницы
		$current = explode('/', $_SERVER['REQUEST_URI']);
		array_splice($current, 0, 1);
		if(empty($current[count($current) - 1])) {
			array_splice($current, count($current) - 1, 1);
		}
		
		$links = [];
		$folders = '';
		if(!empty($current)){
			$end_current = $current[count($current) - 1];
		
			if(count($subBuro) > 0){
				foreach($subBuro as $aboutBuro){
					$links[] = $aboutBuro -> alias;
				}
				
				if(!empty($links)){
					if(in_array($end_current, $links)){
						$f_name = 'Бюро';
						$folders = $subBuro;
					}
				}
			}
			
			$links = [];
			if(count($subInfo) > 0){
				foreach($subInfo as $aboutInfo){
					$links[] = $aboutInfo -> alias;
				}
				
				if(!empty($links)){
					if(in_array($end_current, $links)){
						$f_name = 'Инфоцентр';
						$folders = $subInfo;
					}
				}
			}
		}
	
		
		$f_links = [];
		if(!empty($folders)){
			foreach($folders as $_f){
				$flinks[] = [
					'href' => get_url(config('lang.weblang'), $_f -> typeMenu, $_f -> alias),
					'title' => $_f -> title,
					'uri' => '/'.config('lang.weblang').'/'.$_f -> typeMenu.'/'.$_f -> alias
				];
			}
		}
		
		
		$vars = [
			'mainTitle' 	=> stripslashes(__('bureau_attorneys')) .' "'.stripslashes(__('freytak_and_sons')).'"',
			'topMenu'   	=> $topMenu,
			'practics'  	=> $practics,
			'subBuro'		=> $subBuro,
			'subInfo'		=> $subInfo,
			'menuFooter'	=> $menuFooter,
			'events'    	=> $events,
			'projectsData'	=> $projects,
			'historyAvatars'=> $historyAvatars,
			'f_links'       => isset($flinks) ? $flinks : false,
			'f_name'        => isset($f_name) ? $f_name : false,

		];
		
		$this -> view -> vars($vars);

		$this -> view -> vars('logo', 'public/part/logo.tpl');
		$this -> view -> vars('monthDate', 'public/part/month.tpl');
		$this -> view -> vars('assoc', 'public/part/assoc.tpl');
		$this -> view -> vars('header_inner', 'public/part/header_inner.tpl');
		$this -> view -> vars('header_index', 'public/part/header_index.tpl');
		$this -> view -> vars('search', 'public/part/search.tpl');
		$this -> view -> vars('folders', 'public/part/menu_folders.tpl');
		$this -> view -> vars('projects', 'public/part/menu_real-projects.tpl');
		$this -> view -> vars('menu_calendar', 'public/part/menu_calendar.tpl');
		$this -> view -> vars('menu_calendar_future', 'public/part/menu_calendar-future.tpl');
		
	}
}

