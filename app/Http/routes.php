<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
$app->get('/admin', function()
{	
	$_SESSION = Session::all();
	if(!isset($_SESSION["id"]) || !isset($_SESSION["name"]))
		return view('admin.login');
	return view('admin.admin');
});

$app->get('/', function () use ($app) {
	return view('front/index'); 
});
$app->get('/book/id', function () use ($app) {
	return view('front/book/show'); 
});

// 分享你的生活
$app->get('/share', function () use ($app) {
	return view('front/share'); 
});

// 个人
$app->get('/my/book', function () use ($app) {
	return view('front/my/book'); 
});
$app->get('/my/book/add', function () use ($app) {
	return view('front/my/add'); 
});
$app->get('/my/share', function () use ($app) {
	return view('front/my/share'); 
});

$app->group(array('prefix'=>'/'),function() use ($app){
	
	//用户
	$app->post('/login',                								'App\Http\Controllers\Front\UserController@login');
	$app->post('/pass',                   								'App\Http\Controllers\Front\UserController@pass');
	$app->post('/store',                   								'App\Http\Controllers\Front\UserController@store');
	
	// 借书+求帮忙
	$app->get('/borrow',                						    	'App\Http\Controllers\Front\PartyController@indexBorrow');
	$app->get('/help',                						    	    'App\Http\Controllers\Front\PartyController@indexHelp');
	$app->get('/{type}/{id}',                   						'App\Http\Controllers\Front\PartyController@show');
	$app->post('{type}/{book_id}/timeline',                 			'App\Http\Controllers\Front\PartyController@timeLineAdd');
	$app->post('{type}/{book_id}/timeline/{id}',                		'App\Http\Controllers\Front\PartyController@timeLineUpdate');

	// news
	$app->post('news',                 					         		'App\Http\Controllers\Front\NewsController@store');
	$app->get('news',                						         	'App\Http\Controllers\Front\NewsController@index');
	$app->delete('news/{id}',                						    'App\Http\Controllers\Front\NewsController@delete');

	// comments 等待做ing
	$app->post('news/{id}/comments',                 					'App\Http\Controllers\Front\CommentsController@store');
	$app->get('news/{id}//comments',                					'App\Http\Controllers\Front\CommentsController@index');

	// 发布分享书或求帮忙信息
	$app->post('/book/{type}',                 					        'App\Http\Controllers\Front\BookController@store');
	$app->get('/tag',                 					        	'App\Http\Controllers\Front\BookController@tagIndex');
	$app->get('/book/{isbn}/tag',                 					    'App\Http\Controllers\Front\BookController@bookTagIndex');
	$app->post('/book',                 					    'App\Http\Controllers\Front\BookController@tagBookIndex');

});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
	$api->get 	('/menu',									'App\Http\Controllers\Admin\MenuController@index');
	$api->get 	('/menu/{id:[0-9]+}',						'App\Http\Controllers\Admin\MenuController@show');
	$api->post 	('/menu',     								'App\Http\Controllers\Admin\MenuController@store');
	$api->put 	('/menu/{id:[0-9]+}',						'App\Http\Controllers\Admin\MenuController@update');

	$api->get 	('/category',     							'App\Http\Controllers\Admin\CategoryController@index');
	$api->get 	('/category/{id:[0-9]+}',					'App\Http\Controllers\Admin\CategoryController@show');
	$api->post 	('/category',     							'App\Http\Controllers\Admin\CategoryController@store');
	$api->put 	('/category/{id:[0-9]+}',					'App\Http\Controllers\Admin\CategoryController@update');

	//通用
	$api->get 	('/{table:[A-Z_a-z]+}/config', 				'App\Http\Controllers\Admin\ApiController@config'		);

	$api->post 	('/file', 					                'App\Http\Controllers\Admin\ApiController@upload'		);
	$api->get 	('/{table:[A-Z_a-z]+}', 					'App\Http\Controllers\Admin\ApiController@index'		);
	$api->get 	('/{table:[A-Z_a-z]+}/{id:[0-9]+}', 		'App\Http\Controllers\Admin\ApiController@show'		);
	$api->post 	('/{table:[A-Z_a-z]+}', 					'App\Http\Controllers\Admin\ApiController@store'		);
	$api->put 	('/{table:[A-Z_a-z]+}/{id:[0-9]+}', 		'App\Http\Controllers\Admin\ApiController@update'		);
	$api->delete('/{table:[A-Z_a-z]+}/{id:[0-9]+}', 		'App\Http\Controllers\Admin\ApiController@destroy'	);
	$api->get 	('/config/init', 							'App\Http\Controllers\Admin\ConfigController@init'	);

	$api->get  ('/user/session',               				 'App\Http\Controllers\Admin\UserController@check');
	$api->post ('/user/login',                				 'App\Http\Controllers\Admin\UserController@login');
	$api->get  ('/user/logout',                				 'App\Http\Controllers\Admin\UserController@logout');
	$api->post ('/user/{id}/password',        				 'App\Http\Controllers\Admin\UserController@password');	
});
