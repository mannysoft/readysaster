<?php

class Region extends BaseModel {

	protected $table = 'regions';
		
	protected $fillable = array(
						'name', 
						);
						
	protected $guarded = array();
	
	protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	
	protected static $rules = array();
	
}