<?php

class Disaster extends BaseModel {

	protected $table = 'disasters';
		
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