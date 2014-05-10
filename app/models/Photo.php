<?php

class Photo extends BaseModel {

	protected $table = 'photos';
		
	protected $fillable = array(
						'title', 
						'name', 
						'author',
						'description',
						'ratings',
						'photo_url',
						'thumb_small',
						);
						
	protected $guarded = array();
	
	protected $hidden = array('created_at', 'updated_at', 'deleted_at');
	
	protected static $rules = array(
		'title'  			=> 'required|max:64',
		//'photo'  			=> 'required',
		//'description'  		=> 'required',
		//'url'  				=> 'required',
		//'contact_no'  		=> 'required|max:50',
		//'email'  			=> 'required|email|max:50|unique:gym,email,:id:',
	);
	
	public function getUrlAttribute($value)
    {
        if ($this->photo_url != '')
		{
			return $this->photo_url;
		}
		
		return Request::root().'/'.$value;
		return $value;
    }
	
	public function getThumbSmallAttribute($value)
    {
		return Request::root().'/'.$value;
    }
	
	public function getThumbLargeAttribute($value)
    {
		return Request::root().'/'.$value;
    }
}