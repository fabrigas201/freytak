<?php
$langs_config = config('lang.langs');
$langs_collections = [];

if(is_array($langs_config)){
	foreach($langs_config as $lng){
		$langs_collections[] = $lng['key'];
	}
}


$route -> middleware('auth', function(){
	$auth = new Cms\Auth;
	$request = new Cms\Request;
	if(!$auth -> isLogged() && $request -> segment(1) != 'admin'){
		redirect('admin');
	}
});


$route -> group(['prefix' => 'admin', 'namespace' => 'App\\Controllers\\Admin', 'middleware' => ['auth']], function($route){
	$route -> get('/', 'Admin@index');
	$route -> post('/', 'Admin@index');
	// Выход
	$route -> get('logout', 'Admin@logout');
});



$route -> group(['namespace' => 'App\\Controllers\\Web'], function($route){
    $route -> get('/', 'Home@index');

	$route -> post('contact/send', 'Contacts@send');
	$route -> post('contact/subscribe', 'Contacts@subscribe');
});



$route -> group(['namespace' => 'App\\Controllers\\Web', 'prefix' => $langs_collections], function($route){
    $route -> get('/', 				'Home@index');
    $route -> get('index_buro/', 	'Home@index2');
	$route -> get('news/:any',		'News@index');
	$route -> get('vacancy/:any', 	'Vacancy@index');
	$route -> get('articles/:any', 	'Roster@index');
	$route -> get('article/:any', 	'Roster@read');
	$route -> get('item/:any', 		'News@read');
	$route -> get('page/:any', 		'Page@read');
	$route -> get('search', 		'Search@index');
	$route -> get('history',		'History@index');
	$route -> get('history/:any',	'History@read');

	

	//Контакты
	$route -> get('contact/:any', 	'Contacts@index');
	
	$route -> post('contact/events', 	'Contacts@events');
});

