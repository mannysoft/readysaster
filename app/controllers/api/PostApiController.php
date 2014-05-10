<?php use Carbon\Carbon;
class PostApiController extends BaseController{

	function __construct()
	{
		$this->check(Request::header('User-Agent'));
	}
	
	/**
	 * Gets the posts to show in app. By default returns 20 latest posts. 
	 * If last_post_id parameter is passed with the url, 
	 * then shows 20 (or count) posts before that post. Probably count should be limited to 
	 * something reasonable like 100.
	 * If tag parameter is passed then all posts with that tag are returned 
	 * (again starting from the latest and limiting by count). 
	 * App will allow searching only by valid (existing) tags.
	 * If user_name parameter is passed then all posts which are for user 
	 * with that username are returned (again starting from the latest and limiting by count). 
	 * App will allow searching only by existing usernames.
	 *
	 *
	 * @return Response
	 */
	public function index()
	{
		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}

		//return Push::check();

		$has_more = true;
		//var_dump(Request::get('tag'));
		//return Request::get('tag');

		$tag = Request::get('tag');

		if($tag != null)
		{
			$tag = Tag::where('tags', '=', $tag)->first();

			if($tag != null)
			{
				$in_posts = $tag->in_posts;

				$in_posts = explode(',', $in_posts);

				$tag 	= array_filter($in_posts);

				//return $tag;
			}
			else
			{
				$tag = array('xxx');
				//return $tag;
			}
		}
		
		$posts = Post::LastPostId(Request::get('last_post_id'))
					 	->CountIt(Request::get('count'))
					 	->TagIt($tag)
					 	->Username(Request::get('user_name'))
					 	->IsFollowed(Request::get('is_followed'))
						->orderBy('id', 'DESC')
						->get();

						//return DB::getQueryLog();

		$postsCount = $posts->count();
		
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

		$postArray = array();


		if($posts != NULL)
		{

			foreach ($posts as $post) 
			{
				// Is post already like by the app user
				$is_like = Like::where('post_id', '=', $post->id)
							   ->where('user_id', '=', Auth::user()->id)
							   ->first();

				$is_liked = false;

				if ($is_like != NULL)
				{
					$is_liked = true;
				}	   

				if ($post->user_id == Auth::user()->id) 
				{
					$postArray[] = array(
								'id' 				=> $post->id,
								'text' 				=> $post->text,
								'is_own' 			=> true,
								'timestamp' 		=> (int)$post->timestamp,
								'image_url' 		=> ($post->image_url == '') ? null : Request::root().'/images/'.$post->image_url,
								'video_url' 		=> ($post->video_url == '') ? null : Request::root().'/videos/'.$post->video_url,
								'video_thumb_url' 	=> ($post->video_thumb == '') ? null : Request::root().'/images/'.$post->video_thumb,
								'comments' 			=> (int)$post->comments,
								'likes' 			=> (int)$post->likes,
								'is_liked' 			=> $is_liked,
								'tags' 				=> (array)json_decode($post->tags),
								
								);
				}
				else
				{
					$user = User::find($post->user_id);

					$postArray[] = array(
								'id' 				=> $post->id,
								'text' 				=> $post->text,
								'is_own' 			=> false,
								'user_name' 		=> $user->username,
								'user_first_name' 	=> $user->first_name,
								'user_last_name' 	=> $user->last_name,
								'user_picture_sm' 	=> ($user->picture == '') ? null : Request::root().'/images/50-'.$user->picture,
								'user_picture_m' 	=> ($user->picture == '') ? null : Request::root().'/images/250-'.$user->picture,
								'timestamp' 		=> (int)$post->timestamp,
								'image_url' 		=> ($post->image_url == '') ? null : Request::root().'/images/'.$post->image_url,
								'video_url' 		=> ($post->video_url == '') ? null : Request::root().'/videos/'.$post->video_url,
								'video_thumb_url' 	=> ($post->video_thumb == '') ? null : Request::root().'/images/'.$post->video_thumb,
								'comments' 			=> (int)$post->comments,
								'likes' 			=> (int)$post->likes,
								'is_liked' 			=> $is_liked,
								'tags' 				=> (array)json_decode($post->tags),
								
								);
				}

				
			}

		}			
		
		return Response::json(array(
			//'error' => false,
			'posts' 	=> $postArray,
			'has_more' 	=> $has_more,
			),
			200
		);
		
	}

	public function singlePost()
	{

		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}
		
		$post = Post::find(Request::get('post_id'));

		if ($post == NULL)
		{
			return Response::json(
						array(
							'error_code' 		=> '7',
							'error_description' => 'No such post'
							), 403);
		}


		$postArray = array();

		// Is post already like by the app user
		$is_like = Like::where('post_id', '=', $post->id)
					   ->where('user_id', '=', Auth::user()->id)
					   ->first();

		$is_liked = false;

		if ($is_like != NULL)
		{
			$is_liked = true;
		}	   

		if ($post->user_id == Auth::user()->id) 
		{
			$postArray = array(
						'id' 				=> $post->id,
						'text' 				=> $post->text,
						'is_own' 			=> true,
						'timestamp' 		=> (int)$post->timestamp,
						'image_url' 		=> ($post->image_url == '') ? null : Request::root().'/images/'.$post->image_url,
						'video_url' 		=> ($post->video_url == '') ? null : Request::root().'/videos/'.$post->video_url,
						'video_thumb_url' 	=> ($post->video_thumb == '') ? null : Request::root().'/images/'.$post->video_thumb,
						'comments' 			=> (int)$post->comments,
						'likes' 			=> (int)$post->likes,
						'is_liked' 			=> $is_liked,
						'tags' 				=> (array)json_decode($post->tags),
						
						);
		}
		else
		{
			$user = User::find($post->user_id);

			$postArray = array(
						'id' 				=> $post->id,
						'text' 				=> $post->text,
						'is_own' 			=> false,
						'user_name' 		=> $user->username,
						'user_first_name' 	=> $user->first_name,
						'user_last_name' 	=> $user->last_name,
						'user_picture_sm' 	=> ($user->picture == '') ? null : Request::root().'/images/50-'.$user->picture,
						'user_picture_m' 	=> ($user->picture == '') ? null : Request::root().'/images/250-'.$user->picture,
						'timestamp' 		=> (int)$post->timestamp,
						'image_url' 		=> ($post->image_url == '') ? null : Request::root().'/images/'.$post->image_url,
						'video_url' 		=> ($post->video_url == '') ? null : Request::root().'/videos/'.$post->video_url,
						'video_thumb_url' 	=> ($post->video_thumb == '') ? null : Request::root().'/images/'.$post->video_thumb,
						'comments' 			=> (int)$post->comments,
						'likes' 			=> (int)$post->likes,
						'is_liked' 			=> $is_liked,
						'tags' 				=> (array)json_decode($post->tags),
						
						);
		}	
		
		return Response::json($postArray, 200);
		
	}

	public function report()
	{
		$post = Post::find(Request::get('post_id'));
		$post->reason_type = Request::get('reason_type');
		$post->save();
		return Response::json(null, 200);
	}

	public function like()
	{
		$like = new Like(Request::all());
		$like->user_id = Auth::user()->id;
		$like->timestamp = time();
		$like->save();

		// Increment the likes field
		$post 			= Post::find(Request::get('post_id'));
		$add 			= $post->likes;
		$post->likes 	= $add + 1;
		$post->save();

		// Lets check if the post is owned by an app user
		if ($post->user_id == Auth::user()->id)
		{
			return Response::json(null, 200);
		}

		$user = User::find($post->user_id);

		// Lets save the notification
		$notification = Notification::create(array(
							'type' 				=> 'new_like',
							'sender_user_id' 	=> Auth::user()->id,
							'receiver_user_id' 	=> $user->id,
							'text' 				=> '@'.Auth::user()->username.' liked your post!',
							'post_id' 			=> Request::get('post_id'),
							'timestamp' 		=> time(),
							));

		// Lets increase the badge for receiver
		//$badge = Badge::where('user_id', '=', $user->id)->first();
        
        //if ($badge != null) 
        //{
        	//$badge->badge = $badge->badge + 1;
        	//$badge->save();

        	// We are going to use this for sending push notification
	        //$badges = $badge->badge;

	        // New code
	        $count = Notification::where('is_viewed', '=', 0)
        				   ->where('receiver_user_id', '=', $user->id)
        				   ->count();
	        $badges = $count;

			// Lets send notification to receiver
			$body['aps'] = array(
					'badge' => (int)$badges,
					'alert' => '@'.Auth::user()->username.' liked your post!',
					'sound' => 'default'
					);

			$body['notification_id'] 	= $notification->id;
			$body['notification_type'] 	= 'new_like';
			$body['post_id'] 			= Request::get('post_id');
			//$body['user_name'] 			= $user->username;

	        // If the push is successful we should set the value of notification
	        // item to sent
	        $sent = Push::send($user->id, $body);

	        if($sent)
	        {
	        	$n = Notification::find($notification->id);
	        	$n->sent = 1;
	        	$n->save();
	        }

        //}

		return Response::json(null, 200);
	}

	public function unlike()
	{
		
		$post = Post::find(Request::get('post_id'));

		if ($post == NULL)
		{
			return Response::json(
						array(
							'error_code' 		=> '7',
							'error_description' => 'No such post'
							), 403);
		}

		// Remove from like table
		$like = Like::where('user_id', '=', Auth::user()->id)
					->where('post_id', '=', Request::get('post_id'))
					->delete();

		$add 			= $post->likes;
		$post->likes 	= $add - 1;
		$post->save();

		return Response::json(null, 200);

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

		//$str = '#helloworld #test #video #youtube Bon Iver are the best daily surprise :D';

		// POST
		$post = new Post(Request::all());

		$post->text = stripslashes(Request::get('text'));
		
		// Save the photo
		$photo = Input::file('photo');
		
		if (Input::hasFile('photo'))
		{
			//$destinationPath 	= 'images/'.str_random(8);
			$destinationPath 	= 'images/';
				
			$filename 			= $photo->getClientOriginalName();

			$filename			= str_random(12).$filename;
			
			$upload_success 	= $photo->move($destinationPath, $filename);
			
			$photo = $destinationPath.'/'.$filename;
				
			$post->image_url = $filename;
		}

		// Save the photo
		$video = Input::file('video');
		
		if (Input::hasFile('video'))
		{
			//$destinationPath 	= 'images/'.str_random(8);
			$destinationPath 	= 'videos/';
				
			$filename 			= $video->getClientOriginalName();

			$filename			= str_random(12).$filename;
			
			$upload_success 	= $video->move($destinationPath, $filename);
			
			$photo = $destinationPath.'/'.$filename;
				
			$post->video_url = $filename;
		}

		// Save the thumb
		$video_thumb = Input::file('video_thumb');
		
		if (Input::hasFile('video_thumb'))
		{
			//$destinationPath 	= 'images/'.str_random(8);
			$destinationPath 	= 'images/';
				
			$filename 			= $video_thumb->getClientOriginalName();

			$filename			= str_random(12).$filename;
			
			$upload_success 	= $video_thumb->move($destinationPath, $filename);
			
			//$photo = $destinationPath.'/'.$filename;
				
			$post->video_thumb = $filename;
		}

		preg_match_all('/#([^\s]+)/', Request::get('text'), $matches);

		$hashtags = implode(',', $matches[1]);

		$matches[1] = array_unique($matches[1]);

		$post->is_own = true;
		$post->user_id = Auth::user()->id;
		$post->timestamp = time();
		$post->tags = json_encode($matches[1]);
		$post->save();

		//var_dump($matches[1]);

		// Lets save the tags to database
		if(! empty($matches[1]) and is_array($matches[1]))
		{
			foreach ($matches[1] as $tags) 
			{
				// Lets see if the tag exists
				$tag = Tag::where('tags', '=', $tags)->first();
				
				if($tag)
				{
					$tag->tags_count 	= $tag->tags_count + 1;
					$tag->in_posts 		= $tag->in_posts . $post->id.',';
        			$tag->save();
				}
				else
				{
					Tag::create(array('tags' => $tags, 'tags_count' => 1, 'in_posts' => $post->id.','));
				}
			}
		}

		$user = User::find(Auth::user()->id);

		return Response::json(array(
			'id' 				=> $post->id,
			'text' 				=> $post->text,
			'is_own' 			=> $post->is_own,
			'user_picture_sm' 	=> ($user->picture == '') ? null : Request::root().'/images/50-'.$user->picture,
			'timestamp' 		=> $post->timestamp,
			'image_url' 		=> ($post->image_url == '') ? null : Request::root().'/images/'.$post->image_url,
			'video_url' 		=> ($post->video_url == '') ? null : Request::root().'/videos/'.$post->video_url,
			'video_thumb_url' 	=> ($post->video_thumb == '') ? null : Request::root().'/images/'.$post->video_thumb,
			'comments' 			=> (int)$post->comments,
			'likes' 			=> (int)$post->likes,
			'tags' 				=> (array)json_decode($post->tags),
			
			),
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
	public function destroy()
	{
		// DELETE
		$post = Post::find(Request::get('post_id'));

		if ($post) 
		{
			// Lets get the tags of the the posts
			$tags = (array)json_decode($post->tags);

			$post_id = $post->id;

			$post->delete();

			// Lets delete also the notification with post_id
			Notification::where('post_id', '=', Request::get('post_id'))->delete();

			if( ! empty($tags) )
			{
				foreach($tags as $tag)
				{
					// Lets decrease tags_count in tags table
					//Tag::decrement('tags_count', 1, array('tags' => $tag));
					$t = Tag::where('tags', '=', $tag)->first();
					$add 				= $t->tags_count;
					$t->tags_count 		= $add - 1;
					$t->save();

					// Lets remove the post_id in tags

					//$postTags = str_replace($tag, '', $t->in_posts);

					//$t->in_posts = $postTags;

					//$t->save();
				}
			}
			
			return Response::json(null, 200);
		}
		
		return Response::json(array(
			'error_code' => "7",
			'error_description' => 'No such post'),
			403
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

	public function usersLikes()
	{
		$users = Like::where('post_id', '=', Request::get('post_id'))->orderBy('timestamp')->lists('user_id');

		if ($users == NULL)
		{
			$users = array(0);
		}
		$users = User::whereIn('id', $users)->get();

		$userArray = array();

		foreach ($users as $user) 
		{
			$timestamp = Like::where('post_id', Request::get('post_id'))
								->where('user_id', $user->id)
								->pluck('timestamp');

			$userArray[] = array(
							'timestamp' 		=> $timestamp,
							'user_name' 		=> $user->username,
							'user_first_name' 	=> $user->first_name,
							'user_last_name' 	=> $user->last_name,
							'user_picture_sm' 	=> Request::root().'/images/50-'.$user->picture,
							);
		}
 
		return Response::json( $userArray, 200);
	}
	
	
}
?>