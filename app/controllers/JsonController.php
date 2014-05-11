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
		//return Input::all();

		$locations = ExposureData::all();

		$locationData 	= array();
		$iconsData		= array();

		
		if (Input::get('region_id') != 0)
		{
			$locations = ExposureData::assetData(Input::get('asset_id'))
						->construction(Input::get('construction_id'))
						->where('region_id', '=', Input::get('region_id'))->get();
		}

		if (Input::get('province_id') != 0)
		{
			$locations = ExposureData::assetData(Input::get('asset_id'))
						->construction(Input::get('construction_id'))
						->where('province_id', '=', Input::get('province_id'))->get();
		}

		if (Input::get('town_id') != 0)
		{
			$locations = ExposureData::assetData(Input::get('asset_id'))
						->construction(Input::get('construction_id'))
						->where('town_id', '=', Input::get('town_id'))->get();
		}

		$i = 0;



		foreach ($locations as $exposure)
		{
			$locationData[] = array('<div style="display:block !important; width:300px !important; height:auto !important;">
									<img src="'.Request::root().'/'.$exposure->image.'"/>
									<br>Complete Address:
									<br /><strong>'.$exposure->line_address.'
									<br />'.$exposure->town->name.', '.$exposure->province->name.'</strong>
									<br>Type of Exposure:
									<br /><strong>'.$exposure->asset->name.'</strong>
									</div>', 
									$exposure->lat, $exposure->lon, $i);
			$iconsData[] 	= array($exposure->marker, $i);

			$i ++;
		}

		return array(
					'location' 	=> $locationData,

					'icons' 	=> $iconsData,	



			);

		if (Input::get('town_id'))
		{
			return ExposureData::where('town_id', '=', Input::get('town_id'))->get();
		}
		return Input::all();
		exit;
		//if ()
		return array('1', 3);
		//return Town::whereProvinceId($id)->orderBy('name')->lists('name', 'id');
	}

	public function assets()
	{	
		return Asset::orderBy('name')->lists('name', 'id');
	}

	public function constructions()
	{	
		return Construction::orderBy('name')->lists('name', 'id');
	}
}