<?php 
class Rating extends BaseModel{
	
	protected $table = 'ratings';
	
	public $timestamps = false;
	
	protected static $rules = array();
	
	protected $fillable = array(
						'facebook_id', 
						'video_id', 
						'rating',
						'date',
						);
	
	public function video()
    {
        return $this->belongsTo('Video');
    }
}
?>