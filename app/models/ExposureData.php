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

    public function town()
    {
        return $this->belongsTo('Town');
    }

    public function province()
    {
        return $this->belongsTo('Province');
    }

    public function getMarkerAttribute($value)
    {
        $marker = array(
        		'1' => 'images/residential.jpg',
        		'2' => 'images/business.jpg',
        		'3' => 'images/agriculture.jpg',
        		'4' => 'images/infrastructure.jpg',
        		'5' => 'images/government.jpg',
        		'6' => 'images/eletricity.jpg',
        		'7' => 'images/schools.jpg',
        		'8' => 'images/transportation.jpg',
        		);

        return $marker[$this->asset_id];
    }

    public function scopeAsset($query, $asset_id)
    {
        if ($asset_id != 0)
        {
            return $query->where('asset_id', '=', $asset_id);
        }

    }

    public function scopeConstruction($query, $construction_id)
    {
        if ($construction_id != 0)
        {
            return $query->where('construction_id', '=', $construction_id);
        }

    }
	
	
}