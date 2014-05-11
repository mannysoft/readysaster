<?php

class ExposureData extends BaseModel {

	protected $table = 'exposure_data';
		
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

    public function getMarkerAttribute($value)
    {
        //return 'images/marker.png';

        $marker = array(
        		'1' => 'images/residential.jpg',
        		'2' => 'images/business.jpg',
        		);

        return $marker[$this->asset_id];
    }
	
	
}