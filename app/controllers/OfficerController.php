<?php

class OfficerController extends BaseController {
	
	/**
	 * The template to use.
	 *
	 * @var string
	 */
	protected $layout = 'template';
	
	protected $officer;
	
	/**
	 * Constructor.
	 *
	 * @var interface
	 */
	public function __construct(Officer $officer)
	{
		$this->officer = $officer;
	}
	
	public function index()
	{	
		$data = array();
		
		$data['title'] = 'Officers';
		
		$search = Input::get('search');
				
		$data['rows'] =  $this->officer->orderBy('name')->paginate(10);
		$data['count'] = $this->officer->count();
		
		return View::make('officers.index', $data);
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