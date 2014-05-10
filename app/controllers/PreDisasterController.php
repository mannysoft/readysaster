<?php

class PreDisasterController extends BaseController {
	
	/**
	 * The template to use.
	 *
	 * @var string
	 */
	protected $layout = 'template';
	
	protected $disaster;
	
	/**
	 * Constructor.
	 *
	 * @var interface
	 */
	public function __construct(Disaster $disaster)
	{
		$this->disaster = $disaster;
	}
	
	public function index()
	{	
		$data = array();
		
		$data['title'] = 'Pre Disaster';
		
		$search = Input::get('search');
				
		$data['rows'] =  $this->disaster->with('user', 'lgu')->orderBy('created_at')->paginate(10);
		$data['count'] = $this->disaster->count();
		
		return View::make('pre.index', $data);
	}
	
	
	public function map($id)
	{	
		//return Data::find($id);

		$data = array();
		
		$data['title'] = 'Map View';
				
		$data['rows'] =  $this->data->with('user', 'lgu')->orderBy('created_at')->paginate(10);
		$data['count'] = $this->data->count();
		
		return View::make('pre.map', $data);
	}
	
	public function delete($id)
	{
		$this->officer->find($id)->delete();
	}
}