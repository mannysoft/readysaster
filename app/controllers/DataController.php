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
	
	
	public function add()
	{	
		$data = array();
	
		$data['title'] = 'Add Photo';
		
		if (Input::get('op'))
		{
			$file = Input::file('photo');
			
			$p = $this->photo->fill(Input::all());
			
			
			$p->date = time();
				
			if($p->save()) return Redirect::to('admin/photo');
			
			$data['errors'] = $p->errors;
			
		}
		
		return View::make('officers.add', $data);
	}
	
	public function edit($id)
	{	
		
		$data = array();
				
		$data['title'] = 'Edit Photo';
		
		if (Input::get('op'))
		{
			$file = Input::file('photo');
			
			
			
			$p->date = time();
			
			if($p->save()) return Redirect::to('admin/photo');
			
			$data['errors'] = $p->errors;
			
		}
		
		$data['info'] = $this->photo->find($id);
		
		return View::make('officers.edit', $data);
	}
	
	public function delete($id)
	{
		$p = $this->officer->find($id);
	}
}