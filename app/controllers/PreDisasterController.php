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
	public function __construct(ExposureData $disaster)
	{
		$this->disaster = $disaster;
	}
	
	public function index()
	{	
		$data = array();
		
		$data['title'] = 'Exposure Data';
		
		$search = Input::get('search');

		$data['regions'] = array('0' => 'All', 1, 2, 3);

		$assets = Asset::orderBy('name')->lists('name', 'id');
		$assets[0] = '--All--';

		ksort($assets);

		$data['assets'] = $assets;
		
		$constructions 	= Construction::orderBy('name')->lists('name', 'id');

		$constructions[0] = '--All--';

		ksort($constructions);

		$data['constructions'] 	= $constructions;

		$data['exposures'] 	= ExposureData::all();
				
		//$data['rows'] =  $this->disaster->with('user', 'lgu')->orderBy('created_at')->paginate(10);
		$data['rows'] =  $this->disaster->orderBy('created_at')->paginate(10);
		$data['count'] = $this->disaster->count();
		
		return View::make('pre.index', $data);
	}
}