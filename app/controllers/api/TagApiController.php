<?php use Carbon\Carbon;

class TagApiController extends BaseController{

	function __construct()
	{
		$this->check(Request::header('User-Agent'));
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

		return Response::json(
					Tag::orderBy('tags_count', 'DESC')
					->orderBy('tags')
					->take(100)
					->lists('tags'), 
					200
			);
	}

	public function search()
	{
		if(strlen(Request::get('search_value')) >= 1)
		{
			$has_more = true;

			$count = Request::get('count');

			if($count == null)
			{
				$count = 20;
			}
			
			if($count > 100)
			{
				return Response::json(null, 400);
			}

			$tags = Tag::where('tags', 'LIKE', Request::get('search_value').'%')
						->orderBy('tags_count', 'DESC')
						->paginate($count);

			$postsCount = $tags->count();

			$tagsArray = array();

			foreach ($tags as $tag) 
			{
				$tagsArray[] = array(
							'id' 	=> $tag->id,
							'tag' 	=> $tag->tags,
							'posts' => (int)$tag->tags_count,
							);		
			}

			if (Request::get('count')) 
			{
				if ($postsCount < Request::get('count')) 
				{
					$has_more = false;
				}
			}
			else
			{
				if ($postsCount < 20) 
				{
					$has_more = false;
				}
			}

			return Response::json(array(
						'tags' 		=> $tagsArray, 
						'has_more' 	=> $has_more), 
						200
						);

		}
		else
		{
			return Response::json(null, 400);
		}
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
		
		
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
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
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
	}
	
}
?>