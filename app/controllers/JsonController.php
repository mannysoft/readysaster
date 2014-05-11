<?php

class JsonController extends BaseController {
	
	/**
	 * The template to use.
	 *
	 * @var string
	 */
	protected $layout = 'template';
	
	/**
	 * Constructor.
	 *
	 * @var interface
	 */
	public function __construct()
	{
		
	}
	
	public function regions()
	{	
		return Region::orderBy('name')->lists('name', 'id');
	}

	public function provinces($id)
	{	
		return Province::whereRegionId($id)->orderBy('name')->lists('name', 'id');
	}

	public function towns($id)
	{	
		return Town::whereProvinceId($id)->orderBy('name')->lists('name', 'id');
	}

	public function exposureData()
	{	
		if (Input::get('town_id'))
		{
			return Disaster::where('town_id', '=', Input::get('town_id'))->get();
		}
		return Input::all();
		exit;
		//if ()
		return array('1', 3);
		//return Town::whereProvinceId($id)->orderBy('name')->lists('name', 'id');
	}
}