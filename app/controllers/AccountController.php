<?php

class AccountController extends BaseController {
	
	//protected $layout = 'template';
	protected $user;
	
	public function __construct()
	{
		$this->user = new User;
	}
	
	/*
	  Account Profile
	*/
	
	public function profile()
	{
		$data = array();
		
		$info = Account::profile();
		
		$data['info'] = $info;
		
		return View::make('account.profile', $data);
	}
	
	public function changePassword()
	{
		$data = array();
		
		$id = Auth::user()->id;
			
		$input = Input::all();	
		
		$pass = Auth::user()->password;
			
		//Rules		
		$rules = array(
			'old_password' 		=> 'required|max:50',
			'password'  		=> 'required|max:50',
			'password2'  	  	=> 'required|same:password',
		);
		
		//Validation
		$validation = Validator::make($input, $rules);
		
		$data['errors'] = array();
		
		//Submit form 
		if (Input::get('op'))
		{
			if ( $validation->fails() )
			{
				$data['errors'] = $validation->messages()->all();
			}
			else
			{
				if (Hash::check(Input::get('old_password'), $pass))
				{
					// Save now
					$user = User::find(Auth::user()->id);
					$user->password = Hash::make(Input::get('password'));
					$user->save();
					
					return Redirect::to('profile');
				}
				else
				{
					$data['errors'] = array('Your Current Password is not correct');
				}
			}
		}
						
		return View::make('account.change-password', $data);
	}	
	
}