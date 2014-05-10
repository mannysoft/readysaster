<?php

class Review extends BaseModel {

	protected $table = 'reviews';
	
	//protected $guarded = array();	
	
	public function user()
    {
        return $this->belongsTo('User');
    }
	
	 public function restaurant()
    {
        return $this->belongsTo('Restaurant');
    }
	
	// Scope method
	public function scopeFeatured($query)
    {
          $query->where('featured', '=', 'Yes');
    }
}