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
Route::any('/',   'LoginController@login');

// Use for future API
// Route group for API versioning
Route::group(array('prefix' => 'api/v1'), function()
{
	Route::get('pre-disaster', 		'PreDisasterController@index');
						
});
	

// Secure routes
// You need to login to see all pages below
Route::group(array('before' => 'auth'), function()
{
	Route::any('dashboard', 					'AdminController@index');
	
	Route::any('officers', 						'OfficerController@index');
	Route::any('officers/add', 	  				'OfficerController@add');
	Route::any('officers/edit/{id}', 			'OfficerController@edit');
	Route::any('officers/delete/{id}',  		'OfficerController@delete');

	Route::any('pre-disaster', 					'PreDisasterController@index');
	Route::any('pre-disaster/map/{id}', 		'PreDisasterController@map');
	
	Route::any('profile', 						'AccountController@profile');
	Route::any('profile/edit', 					'AccountController@edit');
	Route::any('change_password', 				'AccountController@changePassword');

	

	Route::any('logout',  						'LoginController@logout');

	// Json Data
	Route::any('json/regions', 					'JsonController@regions');
	Route::any('json/provinces/{id}', 			'JsonController@provinces');
	Route::any('json/towns/{id}', 				'JsonController@towns');
	Route::any('json/exposure-data', 			'JsonController@exposureData');
	
});

