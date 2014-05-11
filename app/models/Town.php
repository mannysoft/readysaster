<?php

class Town extends BaseModel {

	protected $table = 'towns';
		
	protected $fillable = array(
						'name', 
						);
						
	protected $guarded = array();
	
	protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	
	protected static $rules = array(
				//'name'  => 'required|max:64',
	);
	
}