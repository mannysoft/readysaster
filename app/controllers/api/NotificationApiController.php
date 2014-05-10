<?php 
class NotificationApiController extends BaseController{

	function __construct()
	{
		$this->check(Request::header('User-Agent'));
	}

	public function index()
	{
		function text($post_id, $type, $username)
		{
			if ($type == 'new_message')
			{
				return '@'.$username.' sent you a message';
			}
			if ($type == 'new_follower')
			{
				return '@'.$username.' is now following you';
			}
			if ($type == 'new_comment')
			{
				$post = Post::find($post_id);
				return '@'.$username.' commented on your post:"'.$post->text.'"';
			}
			if ($type == 'new_like')
			{
				$post = Post::find($post_id);

				if ($post == NULL)
				{
					//var_dump($post_id).'123'; exit;
				}
				return '@'.$username.' liked your post:"'.$post->text.'"';
			}
		}

		if ($this->status == 'error')
		{
			return Response::json($this->response);
		}

		$has_more = true;
		
		$posts = Notification::LastPostId(Request::get('last_notification_id'))
					 	->AfterNotification(Request::get('after_notification_id'))
					 	->CountIt(Request::get('count'))
					 	->Missed(Request::get('is_missed'))
					 	->where('receiver_user_id', '=', Auth::user()->id)
						->orderBy('id', 'DESC')
						->get();

						

		$postsCount = $posts->count();

		// Lets turn sent to 1 for the current user
		if(Request::get('is_missed') == 1)
		{
			foreach ($posts as $post)
			{
				Notification::where('id', '=', $post->id)
							->update(array('sent' => 1));
				//Notification::where('receiver_user_id', '=', Auth::user()->id)
							//->update(array('sent' => 1));
			}

			
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

		$postArray = array();


		if($posts != NULL)
		{

			foreach ($posts as $post) 
			{
				$user = User::find($post->sender_user_id);

				$p = Post::find($post->post_id);

				$image_url 			= NULL;
				$video_thumb_url 	= NULL;

				if ($p != NULL)
				{
					$image_url 			= ($p->image_url == '') ? null : Request::root().'/images/'.$p->image_url;
					$video_thumb_url 	= ($p->video_thumb == '') ? null : Request::root().'/images/'.$p->video_thumb;
				}

				$postArray[] = array(
							'id' 				=> $post->id,
							'notification_type' => $post->type,
							'user_name' 		=> $user->username,
							'text' 				=> text($post->post_id, $post->type, $user->username),
							'post_id' 			=> $post->post_id,
							'message_id' 		=> $post->message_id,
							'user_first_name' 	=> $user->first_name,
							'user_last_name' 	=> $user->last_name,
							'image_url' 		=> $image_url,
							'video_thumb_url' 	=> $video_thumb_url,
							'user_picture_sm' 	=> ($user->picture == '') ? null : Request::root().'/images/50-'.$user->picture,
							'timestamp' 		=> (int)$post->timestamp,
							'is_unviewed' 		=> ($post->is_viewed == 0) ? true : false,
							);

				

				
			}

		}			
		
		return Response::json(array(
			//'error' => false,
			'notifications' => $postArray,
			'has_more' 	=> $has_more,
			),
			200
		);
		
	}
	
	public function decrease()
    {
        $deviceToken = Request::get('device_token');

        $badge = Badge::where('device_token', '=', $deviceToken)->first();
        $badge = Badge::where('user_id', '=', Auth::user()->id)->first();
        
        if ($badge != null) 
        {
        	$badge->badge = $badge->badge - 1;
        	$badge->save();
        }
       
        return Response::json(NULL, 200); 
    }

    public function reset()
    {
        //return Request::header('User-Agent');

        $deviceToken = Request::get('device_token');

        $badge = Badge::where('device_token', '=', $deviceToken)->first();
        $badge = Badge::where('user_id', '=', Auth::user()->id)->first();
        if ($badge != null) 
        {
        	$badge->badge = 0;
        	$badge->save();
        }
        
        return Response::json(NULL, 200); 
    }

    public function notificationViewed()
    {
        $notification = Notification::find(Request::get('notification_id'));

        if($notification)
        {
        	$notification->is_viewed = 1;
        	$notification->save();
        }
       
       
        return Response::json(NULL, 200); 
    }

    public function unviewedNotificationsCount()
    {
        $count = Notification::where('is_viewed', '=', 0)
        				   ->where('receiver_user_id', '=', Auth::user()->id)
        				   ->count();

        return Response::json(array('unviewed_notifications_count' => (int)$count), 200);
    }

    public function viewedAllNotifications() 
    { 
        Notification::where('is_viewed', '=', 0) 
                    ->where('receiver_user_id', '=', Auth::user()->id) 
                    ->update(array('is_viewed' => 1)); 
  
        return Response::json(NULL, 200); 
    } 
	
	
}
?>