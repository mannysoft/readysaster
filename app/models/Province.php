<?php

class Province extends BaseModel {

	protected $table = 'provinces';
		
	protected $fillable = array(
						'name', 
						);
						
	protected $guarded = array();
	
	protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	
	protected static $rules = array();
	
}