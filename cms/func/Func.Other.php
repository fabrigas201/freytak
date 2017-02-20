<?php
function getAlias($name,$alias = null){
	$name = mb_strtolower($name);
	$alias = mb_strtolower($alias);
	
	if(!empty($alias)){
		$alias = Cms\Libs\Translit::UrlTranslit(trim($alias));
	}else{
		$alias = Cms\Libs\Translit::UrlTranslit(trim($name));
	}
	
	$alias = is_numeric($alias)?'n'.$alias:$alias;
	$alias = substr($alias,0,50);
	return $alias;
}

if(!function_exists('checkAlias')){
	
	function checkAlias($alias, $id = null, $lang = null){
		$alias = mb_strtolower($alias);
		$result = Cms\DB::query('SELECT `id`, `alias`, `news_id` FROM `'.PREFIX.'_news_description` WHERE `alias`="'.$alias.'"');
		
		if(!empty($result -> row)){
			
			if(!empty($lang)){
				$postfix = '_'.$lang;
			}else{
				$postfix = '';
			}

			if($result -> row -> news_id != $id){
				$alias .= $postfix.'_'.$id;
			}
		}
		return $alias;
	}
}

if(!function_exists('checkUname')){
	
	function checkUname($alias, $id = null){
		$alias = mb_strtolower($alias);
		$result = Cms\DB::query('SELECT `uid`, `uname` FROM `'.PREFIX.'_user` WHERE `uname`="'.$alias.'"');

		if(!empty($result -> row)){
			if($result -> row -> uid != $id){
				return false;
			}
		}
		return true;
	}
}

function asset_cache($old, $attr = []){

	if(!is_array($attr)){
		$attr = [$attr];
	}

	$pref = '';
	if(isset($attr['pref'])){
		
		if(is_dir(SITE_DIR.'i/thumb/'.$attr['pref'])){
			$pref = $attr['pref'].'/';
		}else{
			mkdir(SITE_DIR.'i/thumb/'.$attr['pref'], 0777, true);
			chmod(SITE_DIR.'i/thumb/'.$attr['pref'], 0777);
		}
		
	}
	if(isset($attr['width'])){
		$width = $attr['width'];
	}else{
		$width = 190;
	}
	if(isset($attr['height'])){
		$height = $attr['height'];
	}else{
		$height = 190;
	}
	if(isset($attr['path'])){
		$path = $attr['path'];
	}else{
		$path='i/news/';
	}
	
	if(isset($attr['resize'])){
		$resize = $attr['resize'];
	}else{
		$resize='portrait';
	}

	
	

	$image_file = SITE_DIR.$path.$old;

	if(!is_file($image_file)){
	   return;
	}

	$extension = pathinfo($image_file, PATHINFO_EXTENSION);


	$cache_file = $pref.substr($old, 0, strrpos($old, '.')).'_'.$width.'x'.$height.'.'.$extension;
	//$cache_file = $pref.substr($old, 0, strrpos($old, '.')).'.'.$extension;

	if(!is_file(SITE_DIR.'i/thumb/'.$cache_file) || (filectime($image_file) > filectime(SITE_DIR.'i/thumb/'.$cache_file))){

		list($w, $h) = getimagesize($image_file);

		if ($w != $width || $h != $height) {
			$resizeObj = new Cms\Libs\Resize($image_file);
			$resizeObj -> resizeImage($width, $height , $resize);
			$resizeObj -> saveImage(SITE_DIR.'i/thumb/'.$cache_file);
		}else{
			copy($image_file, SITE_DIR.'i/thumb/'.$cache_file);
		}
	}

	return get_url('i/thumb/'.$cache_file);
	
}


function the_tags($tas_alias, $single=false){
	return Cms\Api\TagsSystem::getTagInfo($tas_alias, $single);
}

?>
<?php
function delImages($tbl,$imgdel){
	if(!$tbl) return;
	if(!is_array($imgdel)) return;
	
	foreach($imgdel as $k=>$v){
		$imgdel[$k]=intval($v);
	}
	
	$q='SELECT * FROM '.$tbl.' WHERE id IN ('.implode(',',$imgdel).') LIMIT '.count($imgdel);
	$r=@mysql_query($q);
	
	if(@mysql_num_rows($r)){
		while($l=@mysql_fetch_assoc($r)){
			$prefixes=explode(',',$l['prefixes']);
			
			@unlink(SITE_ROOT_PATH.$l['path'].$l['name']);
			
			foreach($prefixes as $v){
				@unlink(SITE_ROOT_PATH.$l['path'].$v.$l['name']);
			}	
		}
	}
	
	@mysql_query('DELETE FROM '.$tbl.' WHERE id IN ('.implode(',',$imgdel).') LIMIT '.count($imgdel));
}



function getCourseUSD(){
	//получаем курсы $ за 15 последних дней
	$l=file('http://export.rbc.ru/free/cb.0/free.fcgi?period=DAILY&tickers=USD&d1=&m1=&y1=&d2=&m2=&y2=&lastdays=15&separator=%3B&data_format=BROWSER');
	$l=array_pop($l);
	$l=explode(';',$l);
	$course=$l[5];
	return round($course,2);
}
function getCourseEUR(){
	//получаем курсы $ за 15 последних дней
	$l=file('http://export.rbc.ru/free/cb.0/free.fcgi?period=DAILY&tickers=EUR&d1=&m1=&y1=&d2=&m2=&y2=&lastdays=15&separator=%3B&data_format=BROWSER');
	$l=array_pop($l);
	$l=explode(';',$l);
	$course=$l[5];
	return round($course,2);
}


function format_price($price,$no_kop=1){
		
	
	//разбить по разрядам
	if ($no_kop)
		$price=preg_replace("/[\.]{1,}[0-9]{1,}/","",$price);
	
	$price=strrev($price);
	
	for($i=1,$j=strlen($price);$i<=$j;$i++){
		
		$new_price.=$price[$i-1];
		if ($i%3==0) $new_price.=" ";

	}
	
	return strrev($new_price);

}



//удаляет папку со всему подпапками и файлами
function rmdir_recursive($path){
	if(is_dir($path) && !is_link($path)){
		if($dir=opendir($path)){
			while(($file=readdir($dir))!==false){
				if($file=='.' || $file=='..')
					continue;

				if(!rmdir_recursive($path.'/'.$file)){
					//echo $path.'/'.$sf.' could not be deleted.';
					//throw new Exception($path.'/'.$sf.' could not be deleted.');
				}
			}
			closedir($dir);
		}
		return @rmdir($path);
	}
	return @unlink($path);
}


// Функции дебага
if(!function_exists('dd')){
	function dd($dump){
		
		echo '<pre style="color:#00ff00; background:#000; padding:10px;">';
		if(isset($dump)){
			print_r($dump);
		}else{
			echo 'Empty';
		}
		echo '</pre>';
		
	}
}

if(!function_exists('dd_die')){
	function dd_die($dump){
		
		echo '<pre style="color:#00ff00; background:#000; padding:10px;">';
		if(isset($dump)){
			print_r($dump);
		}else{
			echo 'Empty';
		}
		echo '</pre>';
		die();
		
	}
}

if(!function_exists('dump')){
	function dump($dump){
		
		echo '<pre style="color:#00ff00; background:#000; padding:10px;">';
		if(isset($dump)){
			var_dump($dump);
		}else{
			echo 'Empty';
		}
		echo '</pre>';
		die();
		
	}
}

function get_url() {
    $args = func_get_args();

    if (count($args) === 1) return BASE_URL . trim($args[0], '/');

    $url = '';
    foreach ($args as $param) {
        if (strlen($param)) {
            $url .= $param{0} == '#' ? $param: '/'. $param;
        }
    }
    return BASE_URL . preg_replace('/^\/(.*)$/', '$1', $url);
}



function get_current_url() {
	$result = '';
	if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
		$result .= 'https://';
	} else {
		$result .= 'http://';
	}
	
	$result .= $_SERVER['SERVER_NAME'];
	$result .= $_SERVER['REQUEST_URI'];
	return $result;
}



if(!function_exists('redirect')){
	function redirect($url) {
		header('Location: '.get_url($url)); exit();
	}
}

if(!function_exists('style')){
	function  style($style){
		return '<link rel="stylesheet" type="text/css" href="'.get_url($style).'">';
	}
}



if( ! function_exists('date_to')){
    function date_to($date){
		
		$month = [
			'01'=>'января',
			'02'=>'феврал¤',
			'03'=>'марта',
			'04'=>'апреля',
			'05'=>'мая',
			'06'=>'июня',
			'07'=>'июля',
			'08'=>'августа',
			'09'=>'сентября',
			'10'=>'октября',
			'11'=>'ноября',
			'12'=>'декабря',
		];

		
		if(isset($date) && $date != '0000-00-00 00:00:00'){
			$data = explode(' ', $date);
			if(isset($data[0])){
				$first_data = explode('-', $data[0]);
				return implode(' ',[$first_data[2],$month[$first_data[1]],$first_data[0]]);
			}
			return $data[0];
		}
		return false;
	}
}


if(!function_exists('text_to_array')){
	function text_to_array($emails){
		$explode_emails =  explode("\n",  str_replace(array("\r\n", "\n\r"), "\n", $emails));

		$emails_as_array = [];
		foreach($explode_emails as $email){
			if(trim($email) == "") continue;
			$emails_as_array[] = $email;
		}

		return $emails_as_array ;
	}
}

if(!function_exists('array_to_text')){
	function array_to_text($array){
		
		if(!is_array($array)){
			return;
		}

		$text = '';

		foreach($array as $key => $value){
			
			if(next($array)){
				$text .=$value."\r";
			}else{
				$text .=$value;
			}
		}
		return $text ;
	}
}

function config($conf){
	return Cms\Config::get($conf);
}

if(!function_exists('fio')){
	
	function fio($fio){
		
		$fio = explode(' ', mb_strtoupper($fio) );
		
		if(isset($fio[0])){
			$lastname = $fio[0];
		}else{
			$lastname = '';
		}
		
		if(isset($fio[1])){
			$name =  mb_substr($fio[1], 0, 1);
		}else{
			$name = '';
		}
		
		if(isset($fio[2])){
			$patr = mb_substr($fio[2], 0, 1);
		}else{
			$patr = '';
		}
		
		
		
		return $lastname.' '. $name. '. '.$patr. '.';
	}
}

if(!function_exists('__')){
	function __($text){
		return Cms\Language::line($text);
	}
}



		/* $query = DB::query('SELECT * FROM `a_menu`');
		foreach($query -> rows as $item){
			$sql_query['menu_id'] = $item -> id;
			$sql_query['alias'] = empty($item -> alias) ? $item -> id : $item -> alias;
			$sql_query['title'] = $item -> name;
			$sql_query['description'] = $item -> descr;
			$sql_query['text'] = $item -> descrfull;
			$sql_query['metaK'] = $item -> metaK;
			$sql_query['metaD'] = $item -> metaD;
			$sql_query['for_smi'] = $item -> for_smi;
			$sql_query['lang'] = 'ru';
			DB::insert('a_menu_description', $sql_query);
		} */
