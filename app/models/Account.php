<?php 
class Account extends BaseModel {

	public static function profile()
    {
		return User::find(Auth::user()->id);
    }
	
	public function user()
    {
           return $this->belongsTo('User');
    }	
}