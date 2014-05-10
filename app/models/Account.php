<?php 
class Account extends BaseModel {

	
	public static function profile()
    {
		$id = Auth::user()->id;
		
		$user = User::find($id);
		
		return $user;

    }

	public function doc_type()
    {
          return $this->hasOne('Doctype');
    }
	
	public function history()
    {
          //return $this->has_one('History');// use this for one expected
		  return $this->hasMany('History'); // for many
		  //return $this->has_one('History', 'my_foreign_key');
		  //if you want to use a different column name as the foreign key, 
		  //just pass it in the second parameter to the method
    }
	
	public function user()
    {
           return $this->belongsTo('User');
    }
	
	public function office()
    {
           return $this->belongsTo('Office');
    }
	
	public function station()
    {
           return $this->belongsTo('Station');
    }
	
	public function doctype()
    {
          return $this->belongsTo('Doctype');
    }
	
	
	
	public function getFirstNameAttribute($value)
    {
        // Status lets get the last history entry
		$history = History::with(array('office', 'station'))
									->where('document_id', '=', $this->id)
									->orderBy('date_time', 'DESC')
									->take(1)
									->first();
									
		return $history;
		//echo  $history->office()->name;
    }
	
	
	
	// http://laravel.com/docs/database/eloquent (Getter & Setter Methods)
	public function get_for()
	{	
		$fors = Action::where_in('id', json_decode($this->actions_needed))->get();
		
		$actions_needed = '';
		
		foreach ($fors as $for)
		{
			$actions_needed .= $for->description .', ';
		}
		
		return $actions_needed;
	}
	
	function get_current_office()
	{
		// Status lets get the last history entry
		$history = History::with(array('office', 'station'))
									->where('doc_id', '=', $this->id)
									->orderBy('date_time', 'DESC')
									->take(1)
									->first();
									
		//return $history->station->name.', '.$history->office->name;
		
		return $history;
	}
	
	
	
	public static function saveDocument()
	{
		$document = new Document;
				
		$document->tracking_no 		= Input::get('tracking_no');
		$document->user_id 			= Input::get('user_id');
		$document->office_id 		= Input::get('office_id');
		$document->title 			= Input::get('title');
		$document->doctype_id 		= Input::get('doctype_id');
		$document->actions_needed 	= json_encode(Input::get('actions_needed'));
		$document->remarks 			= Input::get('remarks');
		$document->allow_track 		= Input::get('allow_track');
		
		$document->save();
		
		return $document;
	}
	
	// Scope method
	
	public function scopeActive($query)
    {
          $query->where('allow_track', '=', 'yes');
    }
	
	
}