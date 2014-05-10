<?php

class AccountController extends BaseController {
	
	//protected $layout = 'template';
	protected $user;
	
	public function __construct()
	{
		$this->user = new User;
	}
		
	public function register()
	{		
		$data = array();
				
		$input = Input::all();
		
		//Rules		
		$rules = array(
			'email' 		=> 'required|max:50|email|unique:users,email',
			'username'  	=> 'required|max:50|unique:users,username',
			'password'  	=> 'required|max:50',
			'password2'  	=> 'required|max:50|same:password',
			'fname'  	  	=> 'required|max:50',
			'lname'  		=> 'required|max:50',
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
				$this->user->email 		= Input::get('email');
				$this->user->username 	= Input::get('username');
				$this->user->password 	= Hash::make(Input::get('password'));
				$this->user->fname 		= Input::get('fname');
				$this->user->mname 		= Input::get('mname');
				$this->user->lname 		= Input::get('lname');
				$this->user->code 		= $code = md5(uniqid(mt_rand()));
				$this->user->status 	= 'inactive';
				$this->user->user_type 	= 'user';
				$this->user->save();
				
				$email_data = array(
						'code' 		=> $code, 
						'heading' 	=> 'Please confirm your account.',
						'fname'		=> Input::get('fname'),
						);
				
				
				// Lets send confirmation email
				Mail::send('emails.welcome', $email_data, function($m)
				{
					$m->to(Input::get('email'), 'Footnote This')->subject('Confirm your email.');
					
					//var_dump( $m);
				});
				
				//Create session
				Session::flash('username', Input::get('username'));
				Session::flash('email',    Input::get('email'));
		
				return Redirect::to('success');
				
			}
		}
		
				
		//View
		return View::make('account.register', $data);
	}
	
	/*
	  Regitration Success
	*/
	
	public function success()
	{
		//Retrieve data from session
		$data['username'] 	= Session::get('username');
		$data['email'] 		= Session::get('email');
		
		return View::make('account.success', $data);
	}
	
	public function verify($code = '')
	{
		$data = array();
		
		$user = User::where('code', '=', $code)->get()->first();
		
		if ($user == null)
		{
			return 'Invalid code!';
		}
		
		// if exists activate the account
		$data['username'] = $user->username;
		
		$user->status = 'active';
		$user->save();
		
		return View::make('account.verify', $data);
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
	/*
	  Account Profile
	*/
	
	public function edit()
	{
		$data = array();
		
		$id = Auth::user()->id;
		
		$data['info'] = Account::profile();
		
		$input = Input::all();
	
		$pass = Auth::user()->password;
				
	
		//Rules		
		$rules = array(
			'email' 		=> 'required|max:50|email',
			'username'  	=> 'required|max:50',
			'fname'  	  	=> 'required|max:50',
			'lname'  		=> 'required|max:50'
		);
		
		//Messages
		$messages = array(
		    'email.required'  	  => 'The <b>Email</b> field is required.',
			'email.email'  	      => 'The <b>Email</b> format is invalid.',
			'username.required'   => 'The <b>Username</b> field is required.',
			'fname.required' 	  => 'The <b>First Name</b> field is required.',
			'lname.required' 	  => 'The <b>Last Name</b> field is required.',	
			'password.required'   => 'The <b>Password</b> field is required.',			
		);
		
		
		//Validation
		$validation = Validator::make($input, $rules, $messages);
		
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
				User::where('id', $id)
				->update(array(
					'email' 	=> Input::get('email'), 
					'username' 	=> Input::get('username'),
					'fname' 	=> Input::get('fname'),
					'mname' 	=> Input::get('mname'),
					'lname' 	=> Input::get('lname')
					));
			
				return Redirect::to('profile');
			}
		}
						
		return View::make('account.edit-profile', $data);
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
		
		//echo $id;
		
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