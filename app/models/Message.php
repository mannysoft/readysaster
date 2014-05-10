<?php 
class Message extends BaseModel{
	
	protected $table = 'messages';
	
	public $timestamps = false;
	
	protected static $rules = array();
	
	protected $fillable = array(
						'topic_id', 
						'text', 
						'creator_name',
						'time',
						'image',
						'spam'
						);
	
	public function topics()
    {
        return $this->belongsTo('Topic');
    }
}
?>