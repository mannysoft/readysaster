<?php 
class Topic extends BaseModel{
	
	protected $table = 'topics';
	
	public $timestamps = false;
	
	protected static $rules = array();
	
	protected $fillable = array(
						'board_id', 
						'subject', 
						'creator_name',
						'text',
						);
	
	public function boards()
    {
        return $this->belongsTo('Board');
    }
}
?>