<?php

class Lgu extends BaseModel {

	protected $table = 'lgus';
		
	protected $fillable = array(
						'name', 
						);
						
	protected $guarded = array();
	
	protected static $rules = array(
				'name'  => 'required|max:64',
	);

	public function user()
    {
        return $this->hasMany('User');
    }
	
	
}