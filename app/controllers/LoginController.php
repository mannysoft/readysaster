<?php

class LoginController extends BaseController {
	
	public function login()
	{				
		// If already logged in redirect to admin dashboard
		if (Auth::check()) return Redirect::to('dashboard');

		$data['status'] = '';
				
		$input = Input::all();
				
		$rules = array(
			'username'  	=> 'required|max:50',
			'password'  	=> 'required|max:50',
		);
		
		$validation = Validator::make($input, $rules);
		
		$data['errors'] = array();
		
		if (Input::get('op'))
		{
			$credentials = array(
					'username' => Input::get('username'), 
					'password' => Input::get('password')
					);
	
			if (Auth::attempt($credentials))
			{				
				return Redirect::to('dashboard');
			}
			
			$data['errors'] = array('error' => 'Invalid Username or Password');	
		}
		
		return View::make('login', $data);
	}
	
	public function logout()
	{					
		Auth::logout();
		return Redirect::to('/');
	}

	
}