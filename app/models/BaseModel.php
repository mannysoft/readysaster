<?php
// https://github.com/betawax/role-model/blob/master/src/Betawax/RoleModel/RoleModel.php#L86-L101

class BaseModel extends Eloquent{
	
	public $errors;	
	
	public static function boot()
	{
		parent::boot();
		
		static::saving(function($model)
		{
			//return $post->validate();	
			if ( ! $model->force) return $model->validate();
		});
	}
	
	public function validate()
	{
		$rules = self::processRules(static::$rules);
		
		$validation = Validator::make($this->attributes, $rules);
		
		if($validation->passes()) return true;
		
		//$this->errors = $validation->messages();
		$this->errors = $validation->messages()->all();
		
		return false;
	}
	
	/**
	 * Process validation rules.
	 *
	 * @param  array  $rules
	 * @return array  $rules
	 */
	protected function processRules($rules)
	{
		array_walk($rules, function(&$item)
		{
			// Replace placeholders
			$item = stripos($item, ':id:') !== false ? str_ireplace(':id:', $this->getKey(), $item) : $item;
		});

		return $rules;
	}
}