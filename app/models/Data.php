<?php

class Data extends BaseModel {

	protected $table = 'data';
		
	protected $fillable = array(
						//'lat', 
						);
						
	protected $guarded = array();
	
	protected static $rules = array(
				'name'  => 'required|max:64',
	);
	
	
}