<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function home()
	{
		$data = array();
		
		$data['featured'] = Restaurant::featured()->orderBy('updated_at', 'desc')->take(2)->get();
		
		$data['reviews'] = Review::featured()->take(2)->get();
		
		return View::make('home.index', $data);
	}
	
	public function search($key = '')
	{
		$data = array();
		
		$data['restaurants'] = Restaurant::RestoName(Input::get('restaurant'))->get();
		
		$address = Restaurant::RestoName(Input::get('restaurant'))->take(1)->get();
		
		$data['address'] = '';
		
		foreach ($address as $addres)
		{
			$data['address'] = $addres->description;
		}
		
		return View::make('home.search', $data);
	}
	
	public function map($id = '')
	{
		$data = array();
		
		$data['restaurant'] = Restaurant::find($id);
		
		$data['address'] = $data['restaurant']->description;
						
		return View::make('home.map', $data);
	}
	
	public function help()
	{
		$data = array();
				
		return View::make('home.help', $data);
	}
	
	public function myaccount()
	{
		$data = array();
				
		return View::make('home.myaccount', $data);
	}
	
	public function review()
	{
		$data = array();
				
		return View::make('home.review', $data);
	}
	
	public function online()
	{
		$data = array();
        
		// We are going to get all users who is active with in the past 1 hour
		$sessions = DB::table('sessions')->where('last_activity', '>=', time() - 3600)->get();
		
		$sessio_ids = array();
		
		foreach ($sessions as $session)
		{
			$sessio_ids[] = $session->id;
		}
		
		$u = new User;
		
		$data['rows']  = $u->whereIn('session_id', $sessio_ids)->get();
		$data['count'] = $u->whereIn('session_id', $sessio_ids)->count();
		
		return View::make('dashboard.online', $data);

	}
	
	public function ajaxonline()
	{
		$data = array();
        
		$u = new User;
		
		$data['rows']  = $u->where('status', 'active')->get();
		$data['count'] = $u->where('status', 'active')->count();
		
		return View::make('dashboard.ajax-online', $data);

	}

}