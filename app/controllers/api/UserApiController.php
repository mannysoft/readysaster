<?php 
class UserApiController extends BaseController{

	function __construct()
	{
		$this->check();
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		// GET
		$fields = Request::get('fields');
		
		$users = User::all();
 
		return Response::json(array(
			'error' => false,
			'users' => $users->toArray()),
			200
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		$rules = array(
			'email' 		=> 'required|email|unique:users,email',
			'username'  	=> 'required|unique:users,username',
			'password'  	=> 'required',
			'confirmation'  => 'required|same:password',
		);
		
		$validation = Validator::make(Request::all(), $rules);
		
		$data['errors'] = array();
		
		if ( $validation->fails() )
		{
			$data['errors'] = $validation->messages()->all();
			
			return Response::json(array(
				'status' => 'error',
				'message' => $data['errors']),
				200
				);
		}
		else
		{
			$user = new User(Request::all());
			$user->password 	= Hash::make(Request::get('password'));
			$user->save();
			
			return Response::json(array(
					'status' => 'ok',
					'token' => session_id()),
					200
					);
			
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// GET 
		 $user = User::whereUserId($id)->get();
		 
			return Response::json(array(
				'error' => false,
				'users' => ($user == NULL) ? NULL : $user->toArray()),
				200
			);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// PUT - the parameter here is in the URL
		
		$user = User::find($id);	
		Request::get('email') and $user->title = Request::get('email');
		Request::get('password') and $user->title = Hash::make(Request::get('password'));
		$user->save();
	 
		return Response::json(array(
			'error' => false,
			'message' => 'user updated'),
			200
		);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		// DELETE
		$user = User::whereUserId($id)->delete();
 
		return Response::json(array(
			'error' => false,
			'message' => 'user deleted'),
			200
			);
	}
	
	public function login()
	{
		//Session::regenerate();
		
		$input = Input::all();
				
		$rules = array(
			'username'  	=> 'required|max:50',
			'password'  	=> 'required|max:50',
		);
		
		$validation = Validator::make($input, $rules);
		
		$data['errors'] = array();
		
		if ( $validation->fails() )
		{
			$data['errors'] = $validation->messages()->all();
		}
		else
		{
			$credentials = array('username' => Input::get('username'), 'password' => Input::get('password'));
	
			if (Auth::attempt($credentials))
			{						
				return Response::json(array(
					'status' => 'ok',
					'token' => session_id()),
					200
					);
			}
			else
			{
				return Response::json(array(
					'status' => 'error',
					'message' => 'Wrong username or password'),
					200
					);
			}
			
		}
	
	}
	
	
	public function logout()
	{
		$this->checkToken();
		
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		Auth::logout();
		return Response::json(array(
			'status' => 'ok'
			),
			200
		);
	}
	
	public function missingMethod($parameters)
	{
		return Response::json(array(
			'error' => true,
			'message' => 'Not Found'),
			200
			);
	}
	
}
?>
