<?php
if(!function_exists('getmicrotime')){
	function getmicrotime(){
		list($usec,$sec)=explode(' ',microtime());
		return ((float) $usec+(float)$sec);
	}
}
$starttime = getmicrotime();


function clean($array){
	reset($array);
    foreach($array as $k=>$v){
        if(is_array($v)){
			$array[$k]=clean($v);
        }else{
        	$array[$k]=stripcslashes($v);
        }
    }
    return $array;
}

if(get_magic_quotes_gpc()){
	$_POST   = clean($_POST);
	$_GET    = clean($_GET);
}


function db_connect(){
	global $db;
	$connect=@mysql_connect(DBHOST,DBLOGIN,DBPASS) or die(mysql_errno()." : ".mysql_error());
    @mysql_select_db(DBNAME,$connect) or die(mysql_errno()." : ".mysql_error());
	return $connect;
}
@db_connect();
@mysql_query('SET NAMES utf8');

@setlocale(LC_ALL,'ru_RU.utf8');
@setlocale(LC_NUMERIC,'en_US');


function declinationSmarty($params){
	return declination($params['num'],$params['items']);
}

function declination($num=0,$items){
	if(!is_array($items)) $items=explode(',',$items);
	$num=intval($num);

	if(preg_match('/(1\d)$/',$num))
		return $items[2];
	elseif(preg_match('/1$/',$num))
		return $items[0];
	elseif(preg_match('/(2|3|4)$/',$num))
		return $items[1];
	else
		return $items[2];
}