<?php 
class Asset extends BaseModel{
	
	protected $table = 'assets';
	
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