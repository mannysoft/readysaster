<?php

class JsonController extends BaseController {
	
	/**
	 * The template to use.
	 *
	 * @var string
	 */
	protected $layout = 'template';
	
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

		if (Input::get('list') == 1)
		{
			$return = '<table width="100%" id="myTable" class="table">
              <thead>
              <tr>
                <th width="2%">&nbsp;</th>
                <th width="27%">Complete Address</th>
                <th width="28%"><strong>Type of Exposure</strong></th>
                <th width="8%">Cost of Property</th>
                <th width="10%">Number of Person</th>
                <th width="13%">Number of Family</th>
                <td width="12%">
                  <strong>Average Income per month</strong></td>
              </tr>
              </thead>
              <tbody>';

              $i=1;
              
              $total = '';

              foreach($locations as $row){
              $return .='
              <tr>
                <td></td>
                <td>'.$row->line_address.'</td>
                <td>'.'<img src="'.$row->marker.'" />'.$row->asset->name.'</td>
                <td>'.$row->cost.'</td>
                <td>'.$row->persons.'</td>
                <td>'.$row->families.'</td>
                <td>'.$row->income_month.'</td>
              </tr>';
              }
              
              $return .='</tbody></table>';

            return $return;
		}



		foreach ($locations as $exposure)
		{
			$locationData[] = array('<div style="display:block !important; width:300px !important; height:auto !important;">
									<img src="http://rustancapal.rtechsoft.com/pinxala/images/'.$exposure->image.'"/>
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