<?php 
class TopicApiController extends BaseController{

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
		
		$topic = Topic::whereBoardId(Request::get('board_id'))->get();
 
		return Response::json(array(
			//'error' => false,
			'topics' => $topic->toArray()),
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
		// POST
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		$this->checkToken();
		
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		$photo = '';
		
		// POST
		$topic = new Topic(Request::all());
		
		// Save the image
		$file = Input::file('image');
		
		if (Input::hasFile('image'))
		{
			$destinationPath 	= 'uploads/'.str_random(8);
				
			$filename 			= $file->getClientOriginalName();
			
			$upload_success 	= $file->move($destinationPath, $filename);
			
			$photo = $destinationPath.'/'.$filename;
				
			$topic->image = $photo;
		}
		
		// Get the post count per creator
		$topic->post_count = 1;
		
		$topic->save();
		
		// Also create new message
		$message = new Message();
		$message->topic_id 		= $topic->id;
		$message->creator_name 	= Request::get('creator_name');
		$message->time = time();
		$message->text 			= Request::get('text');
		$message->image = $photo;
		$message->save();
	 
		return Response::json(array(
			'status' => 'ok',
			'topics' => $topic->toArray()),
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
				'topics' => ($user == NULL) ? NULL : $user->toArray()),
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
			'message' => 'topics updated'),
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
			'message' => 'topics deleted'),
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
	
	public function search()
	{
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		// GET
		$fields = Request::get('fields');
		
		$topic = Topic::where('subject', 'like', '%'.Request::get('query').'%')->get();
 
		return Response::json(array(
			//'error' => false,
			'topics' => $topic->toArray()),
			200
		);
	}
	
	public function popular()
	{
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		// GET
		$fields = Request::get('fields');
		
		$topic = Topic::where('popular', '=', 'Yes')->get();
 
		return Response::json(array(
			//'error' => false,
			'topics' => $topic->toArray()),
			200
		);
	}
	

	
	
}
?>
