<?php 
class CommentApiController extends BaseController{

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
		
		return Response::json(array(
			'total' => $total,
			'offset' => $offset,
			'limit' => $limit,
			'videos' => $v->toArray()),
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
			//return Response::json($this->response);
		}
		
		$this->checkToken();
		
		if ($this->status == 'error')
		{
			//return Response::json($this->response);
		}
		
		// POST
		$comment = new Comment(Request::all());
		$comment->date = time();
		$comment->save();
		
		// Increment
		DB::table('videos')
				->where('id', '=', Request::get('video_id'))
				->increment('comments_count');
		
		
		return Response::json(array(
			'status' => 'ok',
			'comments' => $comment->toArray()),
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
	
}
?>
