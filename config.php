<?php
define('PATH', dirname(__FILE__) . '/');
define('EXT', '.php');
define('SITE_DIR',$_SERVER['DOCUMENT_ROOT'].'/');
define('CMS_DIR',SITE_DIR.'/cms/');
define('APP',SITE_DIR.'app/');
define('TEMPLATES_DIR',SITE_DIR.'content/templates/');
define('TEMPLATES_DIR_CACHE',SITE_DIR.'content/templates_c/');
define('ADM_PATH','/content/');
define('WYSIWYG_PATH','/assets/js/');
define('SMARTY_RESOURCE_CHAR_SET','utf8');
define('BASE_URL', 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']) .'/');

if(substr($_SERVER['DOCUMENT_ROOT'],0,3)=='C:/'){
	define('LOCALMODE',true);
	define('DBHOST','localhost');
	define('DBLOGIN','root');
	define('DBPASS','');
	define('DBNAME','frey');
	define('PREFIX','a');
}else{	
	define('DBHOST','localhost');
	define('DBLOGIN','frey');
	define('DBPASS','trSyCdQN6NQN');
	define('DBNAME','vencedor340_frey');
	define('PREFIX','a');
}