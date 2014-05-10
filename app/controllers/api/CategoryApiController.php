<?php 
class CategoryApiController extends BaseController{

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
		
		//$category = Category::all()->boards;
		$category = Category::with('boards')->get();
 
		return Response::json(array(
			//'error' => false,
			'categories' => $category->toArray()),
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
		$this->checkToken();
		
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		// POST
		$category = new Category();
		$category->title 		= Request::get('title');
		$category->save();
	 
		return Response::json(array(
			'status' => 'ok',
			'category' => $category->toArray()),
			201
		);
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
	
	public function missingMethod($parameters)
	{
		return Response::json(array(
			'error' => true,
			'message' => 'Not Found'),
			200
			);
	}
	
	

	
	/*
	session_destroy();
	session_start();
	include ($_SERVER['DOCUMENT_ROOT'].'/functions/config.php');
	require_once (HOME . '/functions/classes/functions/helpers.php');
	require_once (HOME . '/functions/classes/prsnl.php');
	require_once (HOME . '/functions/classes/prsnl_desc.php');
	require_once (HOME . '/functions/classes/prsnl_school_reltn.php');
	require_once (HOME . '/functions/classes/user.php');
	
	$user['email'] = $_POST['email'];
	$user['created'] = date('Y-m-d H:i:s;');
	$user['password'] = $_POST['cat'];
	$user['user_id'] = NULL;
	$user['active_ind'] = 1;
	$user['pepper'] = generatePepper(7);
	
	$user['password'] = crypt($user['password'], '$2y$08$' . $salt . $user['pepper']);
	$dbuser = new user($user);
	$email = $dbuser->save();
	unset($dbuser);
	
	//create entry on prsnl table
	$prsnl['prsnl_id'] = NULL;
	$prsnl['first_name'] = $_POST['first_name'];
	$prsnl['last_name'] = $_POST['last_name'];
	$prsnl['date_created'] = date('Y-m-d');
	$prsnl['date_modified'] = date('Y-m-d');
	$prsnl['modified_by'] = NULL;
	$prsnl['active_ind'] = 1;
	$dbprsnl = new prsnl($prsnl);
	$prsnl_id = $dbprsnl->save();
	
	//update the user_id on the user table
	$user['user_id'] = $prsnl_id;
	$user['email'] = $_POST['email'];
	$dbuser = new user($user);
	$email = $dbuser->setUserID();
	
	$prsnl_school_reltn['prsnl_school_reltn_id'] = NULL;
	$prsnl_school_reltn['prsnl_id'] = $prsnl_id;
	$prsnl_school_reltn['school_id'] = $_POST['school_id'];
	$prsnl_school_reltn['date_created'] = date('Y-m-d');
	$prsnl_school_reltn['date_modified'] = date('Y-m-d');
	$prsnl_school_reltn['modified_by'] = NULL;
	$prsnl_school_reltn['active_ind'] = 1;
	$dbprsnl_school_reltn = new prsnl_school_reltn($prsnl_school_reltn);
	$prsnl_school_reltn_id = $dbprsnl_school_reltn->save();
	
	header('Location: ' . HOME_URL . 'prsnl_main.php?prsnl_id=' . $prsnl_id); 
	*/
}
?>
