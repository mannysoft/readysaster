<?php 
class MessageApiController extends BaseController{

	function __construct()
	{
		$this->check();
	}
	
	public function spam()
    {
        if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		$message = Message::find(Request::get('message_id'));
		$message->spam = 'Spam';
		$message->save();
		
		return Response::json(array(
			'status' => 'ok'
			),
			200
		);
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
		
		$ms = Message::whereTopicId(Request::get('topic_id'))->paginate(20);
		
		$messages = array();
		
		foreach ($ms as $message)
		{
			$messages[] = $message->toArray();
		}
	
		return Response::json(array(
			'totalPages' => $ms->getPerPage(),
			'currentPage' => $ms->getCurrentPage(),
			'messages' => $messages),
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
		
		// POST
		$message = new Message(Request::all());
				
		// Save the image
		$file = Input::file('image');
		
		if (Input::hasFile('image'))
		{
			$destinationPath 	= 'uploads/'.str_random(8);
				
			$filename 			= $file->getClientOriginalName();
			
			$upload_success 	= $file->move($destinationPath, $filename);
			
			$photo = $destinationPath.'/'.$filename;
				
			$message->image = $photo;
		}
		
		// Increment the post_count
		$topic 				= Topic::find(Request::get('topic_id'));
		$add 				= $topic->post_count;
		$topic->post_count 	= $add + 1;
		$topic->save();
		
		$message->time = time();
		$message->save();
	 
		return Response::json(array(
			'status' => 'ok',
			'message' => $message->toArray()),
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
		return 25;
		// GET 
		 //$user = User::whereUserId($id)->get();
		 
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
