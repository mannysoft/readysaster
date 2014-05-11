<?php 
class Account extends BaseModel {

	public static function profile()
    {
		$id = Auth::user()->id;
		
		$user = User::find($id);
		
		return $user;

    }
	
	public function user()
    {
           return $this->belongsTo('User');
    }
	
	// Scope method
	
	public function scopeActive($query)
    {
          $query->where('allow_track', '=', 'yes');
    }
	
	
}