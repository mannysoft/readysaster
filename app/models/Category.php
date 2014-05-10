<?php 
class Category extends BaseModel{
	
	protected $table = 'categories';
	
	public $timestamps = false;
	
	protected static $rules = array();
	
	public function boards()
    {
        return $this->hasMany('Board');
    }

}
?>