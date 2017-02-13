<?php
/**
 * MVC PHP Framework
 * 
 * @copyright Copyright (c) 2016, Gurev Valentin[Гурьев Валентин]
 * @author Гурьев Валентин
 * @link https://github.com/fabrigas201/freamwork
 * 
 */

//запускаю сессию
session_start();

header('Content-Type: text/html;Charset=utf-8');
require_once('config.php');


//отображаю ошибки
error_reporting(E_ALL);
ini_set('display_errors', 1);

//проверяю версию php
if(version_compare(phpversion(), '5.6.0', '<') == true) {
    die('PHP >= 5.6.0 Only (Версия PHP должна быть >= 5.6.0)');
}

require_once PATH.'vendor/autoload'.EXT;

Cms\Init::func();

require_once(CMS_DIR.'cms_init.php');


$route = new \Cms\Route();
require_once(APP.'route'.EXT);
$route -> dispatch();