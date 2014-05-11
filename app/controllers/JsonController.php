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
}