<?php namespace Cms;

use Cms\View;
use Cms\Request;

class Modules extends Controller {

	public $errors;
	
	public function __construct(){
		parent::__construct();
		
		$langs = [];
		
		if(empty(config('lang.langs'))){
			
			$langs[1] = [
				'key' => 'ru',
				'text' => 'Русский'
			];
			$langs[2] = [
				'key' => 'en',
				'text' => 'English'
			];
		}else{
			$langs = config('lang.langs');
		}
		
		$vars['langs'] = $langs;
		
		$this -> view -> vars($vars);
		
	}
	
	
	protected function validateData(Request $request){
		//проверка данных
		$sql_query_data = [];
		if($request -> post('field') != null){
			foreach($request -> post('field') as $lang => $field){
				if(isset($_POST['field'][$lang]['name']) && !empty($_POST['field'][$lang]['name'])){
					$sql_query_data['langs'][$lang]['title'] = trim(addslashes($_POST['field'][$lang]['name']));
					
					
					$alias = getAlias($_POST['field'][$lang]['name'], $_POST['field'][$lang]['alias']);
					$alias = checkAlias($alias, $request -> get('id'), $lang);
					$sql_query_data['langs'][$lang]['alias'] = $alias;
					
				}else{
					$this -> errors[$lang]['noName']='Не указано наименование';
				}
				
				if(isset($_POST['field'][$lang]['descr'])){
					$sql_query_data['langs'][$lang]['descr'] = $_POST['field'][$lang]['descr'];
				}
				
				if(isset($_POST['field'][$lang]['descrfull'])){
					$sql_query_data['langs'][$lang]['descrfull'] = $_POST['field'][$lang]['descrfull'];
				}
				

				
				if(isset($_POST['field'][$lang]['metaD'])){
					$sql_query_data['langs'][$lang]['metaD'] = addslashes($_POST['field'][$lang]['metaD']);
				}
				if(isset($_POST['field'][$lang]['metaK'])){
					$sql_query_data['langs'][$lang]['metaK'] = addslashes($_POST['field'][$lang]['metaK']);
				}
			}
		}
		
		
		if($request -> post('posi')){
			$sql_query_data['base']['posi'] = intval($request -> post('posi'));
		}
		
		if($request -> post('inCalendar') != null){
			$sql_query_data['base']['inCalendar'] = $request -> post('inCalendar')?1:0;
		}
		if($request -> post('inIndex') != null){
			$sql_query_data['base']['inIndex'] = $request -> post('inIndex')?1:0;
		}
		
		
		if($request -> post('isHidden') != null){
			$sql_query_data['base']['isHidden'] = $request -> post('isHidden')?1:0;
		}

		$sql_query_data['base']['pid'] = $request -> post('pid') ? $request -> post('pid') : 0;

		if($request -> post('dateAdd')){
			$sql_query_data['base']['dateAdd'] 	= trim($request -> post('dateAdd'));
		}else{
			$sql_query_data['base']['dateAdd']	= date('Y-m-d H:i:s');
		}
		
		if($request -> post('eventDate') != null){
			$sql_query_data['base']['eventDate'] 	= trim($request -> post('eventDate'));
		}
		
		
		if($request -> post('datePub')){
			$sql_query_data['base']['datePub'] 	= trim($request -> post('datePub'));
		}else{
			$sql_query_data['base']['datePub']	= date('Y-m-d H:i:s');
		}
		
		if($request -> post('categories') != null){
			$sql_query_data['base']['categories'] = $request -> post('categories') ? $request -> post('categories') : 0;
		}
		
		if($request -> get('mod')){
			$sql_query_data['base']['mod'] 	= trim(addslashes($request -> get('mod')));
		}else{
			$sql_query_data['base']['mod']	= '';
		}
		
		
		if(!empty($this -> errors)){
			return false;
		}
		return $sql_query_data;
	}
	
	
	public function getErrors(){
		return $this -> errors;
	}
	
}
