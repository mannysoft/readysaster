<?php 
class Construction extends BaseModel{
	
	protected $table = 'constructions';
	
	public $timestamps = false;
	
	protected static $rules = array();
	
	protected $fillable = array(
						'name',
						);
	
	public function user()
    {
        return $this->belongsTo('User');
    }
}
?>