@extends('template')
@section('content')
<br />
<h4 class="text-success">{{ $title }}</h4>

<table width="100%" border="0">
  <tr>
    <td width="12%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="34%">&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Region:</td>
    <td>{{ Form::select('region_id', $regions, '', array('id' => 'region_id')) }}</td>
    <td>Type of Exposure</td>
    <td>{{ Form::select('asset_id', $assets, '', array('id' => 'asset_id')) }}</td>
  </tr>
  <tr>
    <td align="right">Province:</td>
    <td>{{ Form::select('province_id', array(), '', array('id' => 'province_id')) }}</td>
    <td>Type of Construction</td>
    <td>{{ Form::select('construction_id', $constructions, '', array('id' => 'construction_id')) }}</td>
  </tr>
  <tr>
    <td align="right">City/Municipality:</td>
    <td>{{ Form::select('town_id', array(), '', array('id' => 'town_id')) }}&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="show" id="show" value="Show" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>



<!-- Example row of columns -->
  <div class="row">
    <div class="span6">
     	<div id="map_div">&nbsp;</div>
    </div>
    <div class="span6">
      <div id="stats">
          <table width="100%" id="myTable" class="table">
              <thead>
              <tr>
                <th width="2%">&nbsp;</th>
                <th width="27%">Complete Address</th>
                <th width="28%"><strong>Type of Exposure</strong></th>
                <th width="8%">Cost of Property</th>
                <th width="10%">Number of Person</th>
                <th width="13%">Number of Family</th>
                <td width="12%">
                  <!--<a href="{{Request::root()}}/add" class="btn btn-primary btn-small">Add</a>-->
                  <strong>Average Income per month</strong></td>
              </tr>
              </thead>
              <tbody>
              <?php $i=1;?>
              
              @foreach($rows as $row)
              <tr>
                <td><?=$i++;?></td>
                <td>{{ $row->line_address }}</td>
                <td><img src="{{ $row->marker }}" />{{ $row->asset->name }}</td>
                <td>{{ $row->cost }}</td>
                <td>{{ $row->persons }}</td>
                <td>{{ $row->families }}</td>
                <td>{{ $row->income_month }}</td>
              </tr>
              @endforeach
              </tbody>
            </table>
            
        </div>

   </div>


<script>
$(document).ready(function(){ 
	
	showMap(0); // show the map
		
	$.getJSON('{{ Request::root()}}/json/regions', null, function (data) {
				
		$('#region_id').empty().append("<option value='0'>--All--</option>");
		$.each(data, function (key, val) {
			$('#region_id').append("<option value='" + key + "'>" + val + "</option>");

		});
	});
	
	
	$('#region_id').change(function(){
		
		var region_id = $(this)[0].value.toString();
		
		$.getJSON('{{ Request::root()}}/json/provinces/'+ region_id, null, function (data) {
				
			$('#province_id').empty().append("<option value='0'>--All--</option>");
			$.each(data, function (key, val) {
				$('#province_id').append("<option value='" + key + "'>" + val + "</option>");

			});
		});
		
	});
	
	$('#province_id').change(function(){
		
		var province_id = $(this)[0].value.toString();
		
		$.getJSON('{{ Request::root()}}/json/towns/'+ province_id, null, function (data) {
				
			$('#town_id').empty().append("<option value='0'>--All--</option>");
			$.each(data, function (key, val) {
				$('#town_id').append("<option value='" + key + "'>" + val + "</option>");
	
			});
		});	
		
	});
	
	
	$('#show').click(function(){
		
		showMap(1); // show the map
		
		// Reload new data
		
	});
	
	//------- Google Maps ---------//
	
	function showMap(submitted)
	{
		$.post( "{{ Request::root()}}/json/exposure-data-table", 
					{ 
						region_id: $('#region_id').val(),
						province_id: $('#province_id').val(),
						town_id: $('#town_id').val(),
						asset_id: $('#asset_id').val(),
						construction_id: $('#construction_id').val(),
						op: submitted,
						list: 1,
						time: "2pm" 
					}, 
					
					function( data ) {
		  			//console.log(data.location)
					//alert("test123")
					$('#stats').html(data);
					
		});
		
		$.post( "{{ Request::root()}}/json/exposure-data", 
					{ 
						region_id: $('#region_id').val(),
						province_id: $('#province_id').val(),
						town_id: $('#town_id').val(),
						asset_id: $('#asset_id').val(),
						construction_id: $('#construction_id').val(),
						op: submitted,
						time: "2pm" 
					}, 
					
					function( data ) {
		  			//console.log(data.location)
					
					
					locations = data.location;
    
		var map = new google.maps.Map(document.getElementById('map_div'), {
			zoom: 9,
			center: new google.maps.LatLng(14.24685, 121.50039),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
			
		var infowindow = new google.maps.InfoWindow();
					
		var icons = data.icons;
		
		var shape = {
			  coord: [1, 1, 1, 32, 37, 37, 18 , 1],
			  type: 'poly'
		};
		
		var marker, i;
		
		for (i = 0; i < locations.length; i++) {  
				marker = new google.maps.Marker({
				position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				map: map,
				icon: new google.maps.MarkerImage(icons[i][0], new google.maps.Size(32, 37), new google.maps.Point(0,0), new google.maps.Point(0, 32)),
				shape: shape
			});
		  
			google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
				return function() {
					infowindow.setContent(locations[i][0]);
					infowindow.open(map, marker);
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							switch(i) {
									 <?php //for ($i = 0; $i < $RS_Count; $i++) { ?>
										case <?php //echo $i ?>1:
											location.href = "<?php //echo $view_station[$i]; ?>";
										break;
									 <?php //} ?>
							   }
						}
					})(marker, i));
				}
			})(marker, i));
		  
		}
					
		  
		});
		
	
			 

	}// Show map end

	


});

</script>  

@stop

