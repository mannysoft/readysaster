<?php 
class PhotoApiController extends BaseController{

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
		$kind = Request::get('kind');
		$offset = Request::get('offset');
		$limit = Request::get('limit');
		$state = Request::get('state');
		
		if ($offset == '')
		{
			$limit = 0;
		}
		
		$p = Photo::get();
		$total = Photo::count();
		
		if ($state ==  'new')
		{
			$v = Video::orderBy('id', 'DESC')->skip($offset)->take($limit)->get();
			$total = Video::skip($offset)->take($limit)->count();
		}
		if ($state ==  'top-rated')
		{
			//$v = Video::orderBy('ratings', 'DESC')->skip($offset)->take($limit)->get();
			//$total = Video::skip($offset)->take($limit)->count();
			
			if (Request::get('period') == 'day')
			{
				$v = DB::table('ratings')
                     ->select(DB::raw('count(*) as ratings, facebook_id, video_id'))
                     ->where('date', '=', date('Y-m-d'))
					 ->where('rating', '=', 'Up')
					 ->orderBy('ratings', 'DESC')
                     ->groupBy('video_id')
                     ->get();
			}
			
			if (Request::get('period') == 'week')
			{
				$v = DB::table('ratings')
                     ->select(DB::raw('count(*) as ratings, facebook_id, video_id'))
					 ->whereIn('date', $this->getCurrentWeek())
					 ->where('rating', '=', 'Up')
					 ->orderBy('ratings', 'DESC')
                     ->groupBy('video_id')
                     ->get();
			}
			
			if (Request::get('period') == 'month')
			{
				$v = DB::table('ratings')
                     ->select(DB::raw('count(*) as ratings, facebook_id, video_id'))
					 ->where(DB::raw('MONTH(date)'), '=',Request::get('month_number'))
					 ->where(DB::raw('YEAR(date)'), '=', Request::get('year'))
					 ->where('rating', '=', 'Up')
					 ->orderBy('ratings', 'DESC')
                     ->groupBy('video_id')
                     ->get();
					 
					// return;
					 
			}
			
			if (Request::get('period') == 'overall')
			{
				$v = DB::table('ratings')
                     ->select(DB::raw('count(*) as ratings, facebook_id, video_id'))
					 ->where('rating', '=', 'Up')
					 ->orderBy('ratings', 'DESC')
                     ->groupBy('video_id')
                     ->get();
			}
			
			$videos = array(0);
			
			foreach ($v as $video)
			{
				$videos[] = $video->video_id;
			}
			
			$v = DB::table('videos')
				->whereIn('id', $videos)
				->orderBy('ratings', 'DESC')
				->skip($offset)
				->take($limit)->get();
			
			$total = DB::table('videos')
				->whereIn('id', $videos)
				->skip($offset)
				->take($limit)->count();	 
				
				return Response::json(array(
					'total' => $total,
					'offset' => $offset,
					'limit' => $limit,
					'period' => Request::get('period'),
					'videos' => $v),
					200
				);
			
			
		}
		
		return Response::json(array(
			'total' 	=> $total,
			'offset' 	=> $offset,
			'limit' 	=> $limit,
			'photos' 	=> $p->toArray()),
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
		
		$this->checkToken();
		
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		// POST
		$board = new Board(Request::all());
		$board->save();
	 
		return Response::json(array(
			'status' => 'ok',
			'board' => $board->toArray()),
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
		 $video = Photo::find($id);
		 
			return Response::json(array(
				'error' => false,
				'photos' => ($video == NULL) ? NULL : $video->toArray()),
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
	
	public function comments($id)
	{
		//return 'a';
		// GET 
		 $comments = Comment::whereVideoId($id)->get();
		 
			return Response::json(array(
				'error' => false,
				'comments' => ($comments == NULL) ? NULL : $comments->toArray()),
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
	
	function getCurrentWeek()
	{
		$weekArray = array();
	
		// set current date1
		$date = date("m/d/Y"); //'04/30/2009';
	
		// parse about any English textual datetime description into a Unix timestamp
		$ts = strtotime( $date );
	
		// calculate the number of days since Monday
		$dow = date('w', $ts);
		$offset = $dow - 1;
		if ($offset < 0) $offset = 6;
	
		// calculate timestamp for the Monday
		$ts = $ts - $offset*86400;
	
		// loop from Monday till Sunday
		for ($i=0; $i<7; $i++, $ts+=86400){
			$temp_date = date("Y-m-d", $ts);  // here I set it to the same format as my database
			array_push( $weekArray, $temp_date );
		}
	
		return $weekArray;

	}
	
	public function search()
	{
		// GET 
		 $video = Video::where('title', 'like', '%'.Request::get('q').'%')->orderBy('date', 'DESC')->get();
		 
			return Response::json(array(
				'error' => false,
				'videos' => ($video == NULL) ? NULL : $video->toArray()),
				200
			);
	}

	
}
?>
