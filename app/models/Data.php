<?php

class Data extends BaseModel {

	protected $table = 'data';
		
	protected $fillable = array(
						'lat', 
						);
						
	protected $guarded = array();
	
	protected static $rules = array(
				'lat'  => 'required|max:64',
	);

	public function user()
    {
        return $this->belongsTo('User');
    }

    public function lgu()
    {
        return $this->belongsTo('Lgu');
    }
	
	
}