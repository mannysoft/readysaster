<?php

class AdminController extends BaseController {
	
	/**
	 * The template to use.
	 *
	 * @var string
	 */
	protected $layout = 'template';
	
	protected $photo;
	
	/**
	 * Constructor.
	 *
	 * @var interface
	 */
	public function __construct()
	{
	}
	
	public function index($title = '')
	{	
		$data = array();
		
		$data['title'] = 'Dashboard';
		
		return View::make('dashboard', $data);
	}
	
	
	
}