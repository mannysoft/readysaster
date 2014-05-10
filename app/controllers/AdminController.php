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
		//$this->photo = $photo;
	}
	
	public function index($title = '')
	{	
		$data = array();
		
		$data['title'] = 'Dashboard';
		
				
		//$data['rows'] =  $this->photo->orderBy('title')->paginate(10);
		//$data['count'] = $this->photo->count();
		
		return View::make('dashboard', $data);
	}
	
	
	
}