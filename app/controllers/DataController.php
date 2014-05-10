<?php

class DataController extends BaseController {
	
	/**
	 * The template to use.
	 *
	 * @var string
	 */
	protected $layout = 'template';
	
	protected $data;
	
	/**
	 * Constructor.
	 *
	 * @var interface
	 */
	public function __construct(Data $data)
	{
		$this->data = $data;
	}
	
	public function index()
	{	
		$data = array();
		
		$data['title'] = 'Data Submitted';
		
		$search = Input::get('search');
				
		$data['rows'] =  $this->data->with('user', 'lgu')->orderBy('created_at')->paginate(10);
		$data['count'] = $this->data->count();
		
		return View::make('submitted.index', $data);
	}
	
	
	public function map($id)
	{	
		//return Data::find($id);

		$data = array();
		
		$data['title'] = 'Map View';
				
		$data['rows'] =  $this->data->with('user', 'lgu')->orderBy('created_at')->paginate(10);
		$data['count'] = $this->data->count();
		
		return View::make('submitted.map', $data);
	}
	
	public function delete($id)
	{
		$p = $this->officer->find($id);
	}
}