<?php 
class Comment extends BaseModel{
	
	protected $table = 'comments';
	
	public $timestamps = false;
	
	protected static $rules = array();
	
	protected $fillable = array(
						'facebook_id', 
						'video_id', 
						'comments',
						'date',
						);
	
	public function video()
    {
        return $this->belongsTo('Video');
    }
}
?>