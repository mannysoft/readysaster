<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::any('myaccount',   'HomeController@myaccount');


Event::listen('illuminate.query', function($sql)
{
    //var_dump($sql);
}); 

// Public Routes

// Login
Route::any('login',   'LoginController@login');
Route::any('logout',  'LoginController@logout');

// Route group for API versioning
Route::group(array('prefix' => 'api/v1'), function()
{
	Route::get('videos/{id}/comments', 'VideoApiController@comments');
	Route::get('videos/search', 'VideoApiController@search');
						
});
	

// Secure routes
Route::group(array('before' => 'auth'), function()
{
	Route::any('dashboard', 			'AdminController@index');
	
	Route::any('officers', 				'OfficerController@index');
	Route::any('officers/add', 	  		'OfficerController@add');
	Route::any('officers/edit/{id}', 	'OfficerController@edit');
	Route::any('officers/delete/{id}',  'OfficerController@delete');
	
	Route::any('profile', 			'AccountController@profile');
	Route::any('profile/edit', 		'AccountController@edit');
	Route::any('change_password', 	'AccountController@changePassword');
	
});


Route::any('/',   'LoginController@login');
