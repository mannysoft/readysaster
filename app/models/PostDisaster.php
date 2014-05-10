<?php

class PostDisaster extends BaseModel {

	protected $table = 'post_disasters';
		
	protected $fillable = array(
						//'lat', 
						);
						
	protected $guarded = array();
	
	protected static $rules = array(
				//'lat'  => 'required|max:64',
	);

	public function disaster()
    {
        return $this->belongsTo('Disaster');
    }

}