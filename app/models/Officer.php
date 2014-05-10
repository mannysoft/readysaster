<?php

class Officer extends BaseModel {

	protected $table = 'officers';
		
	protected $fillable = array(
						'name', 
						);
						
	protected $guarded = array();
	
	protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	
	protected static $rules = array(
				'name'  => 'required|max:64',
	);
	
	public function getTestAttribute($value)
    {
		return Request::root().'/'.$value;
    }
	
}