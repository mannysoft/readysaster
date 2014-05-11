@extends('template')
@section('content')
<br />
<h4 class="text-success">{{ $title }}<span class="badge badge-success"><?=$count;?></span></h4>

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
    <td>Type of Asset</td>
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

<div id="map_div">&nbsp;</div>


<table width="100%" id="myTable" class="table">
  <thead>
  <tr>
    <th width="2%">&nbsp;</th>
    <th width="26%">Date</th>
    <th width="13%"><strong>LGU</strong></th>
    <th width="19%">Name</th>
    <th width="17%">Address</th>
    <th width="4%">Actions</th>
    <td width="19%">
      <!--<a href="{{Request::root()}}/add" class="btn btn-primary btn-small">Add</a>-->
    </td>
  </tr>
  </thead>
  <tbody>
  <?php $i=1;?>
  
  @foreach($rows as $row)
  <tr>
    <td><?=$i++;?></td>
    <td>{{ $row->created_at->toFormattedDateString() }}</td>
    <td>{{ $row->lgu->name }}</td>
    <td>{{ $row->user->first_name }} {{ $row->user->last_name }}</td>
    <td>{{ $row->address }}</td>
    <td></td>
    <td>
      <!--<a href="{{Request::root()}}/submitted/view/{{ $row->id }}" class="btn btn-primary btn-small">Picture</a>
      <a href="{{Request::root()}}/submitted/map/{{ $row->id }}" class="btn btn-primary btn-small">Map</a>
      <a href="{{Request::root()}}/admin/photo/edit/{{ $row->id }}" class="btn btn-primary btn-small">Edit</a>
      <a href="#" class="btn btn-small" id="delete" val="{{ $row->id }}">Delete</a>-->
    </td>
  </tr>
  @endforeach
  </tbody>
</table>


<?php echo $rows->links(); ?>




<script>
$(document).ready(function(){ 
	
	//alert("")
	//var office_id = $(this)[0].value.toString();
		
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
	
	$('#municipality_id').change(function(){
		
		//alert("municipality_id");
		
	});
	
	$('#show').click(function(){
		
		$.post( "{{ Request::root()}}/json/exposure-data", 
					{ 
						region_id: $('#region_id').val(),
						province_id: $('#province_id').val(),
						town_id: $('#town_id').val(),
						asset_id: $('#asset_id').val(),
						construction_id: $('#construction_id').val(),
						time: "2pm" 
					}, 
					
					function( data ) {
		  alert(data)
		  //$( ".result" ).html( data );
		});
		
		//$.getJSON('{{ Request::root()}}/json/exposure-data', null, function (data) {
				
			/*
			$('#region_id').empty().append("<option value='0'>--All--</option>");
			$.each(data, function (key, val) {
				$('#region_id').append("<option value='" + key + "'>" + val + "</option>");
	
			});
			*/
			
			//alert(data);
		//});
		
		
	});

	
	//$("#myTable").tablesorter(); 
	
	//------- Google Maps ---------//
		  
	var locations = [
            ['<div style="display:block !important; width:300px !important; height:auto !important;"><strong>Automated Rain Gauge</strong><br /><br /><a href="http://drrm.region4a.dost.gov.ph/arg/53/batangas-balayan-patogo.html"><strong>Patugo, Balayan, Batangas</strong></a><br />as of May 10, 2014 09:00 PM<br /><br /><strong>0.0 mm/hr</strong></div>', 14.00401, 120.78477, 0],            ['<div style="display:block !important; width:300px !important; height:auto !important;"><strong>Automated Rain Gauge</strong><br /><br /><a href="http://drrm.region4a.dost.gov.ph/arg/1/balete-batangas.html"><strong>Malabanan, Balete, Batangas</strong></a><br />as of May 10, 2014 09:15 PM<br /><br /><strong>0.0 mm/hr</strong></div>', 14.01786, 121.12888, 1],            ['<div style="display:block !important; width:300px !important; height:auto !important;"><strong>Automated Rain Gauge</strong><br /><br /><a href="http://drrm.region4a.dost.gov.ph/arg/2/kumintang-ilaya-batangas.html"><strong>Kumintang Ilaya, Batangas, Batangas</strong></a><br />as of May 10, 2014 08:45 PM<br /><br /><strong>0.0 mm/hr</strong></div>', 13.7733, 121.0619, 2]    ];
			
			locations = [
            <?php $i = 0;?>
			<?php foreach ($exposures as $exposure): ?>
                ['<div style="display:block !important; width:300px !important; height:auto !important;"><?php //echo $station_type[$i]; ?><br /><br /><a href="<?php //echo $view_station[$i]; ?>"><strong><?php echo $exposure->address; ?></strong></a><br />as of <?php //echo $as_of_datetime[$i]; ?><br /><br /><strong><?php //echo $details[$i]; ?></strong></div>',  <?php echo $exposure->lat; ?>, <?php echo $exposure->lon; ?>, <?php echo $i; ?>]<?php if( ($i != ($exposures->count()-1)) ){ echo ","; } else { echo ""; } ?>
				<?php $i++;?>
            <?php endforeach; ?>
        ];
    
var map = new google.maps.Map(document.getElementById('map_div'), {
    zoom: 9,
    center: new google.maps.LatLng(14.24685, 121.50039),
    mapTypeId: google.maps.MapTypeId.ROADMAP
});
    
var infowindow = new google.maps.InfoWindow();

var icons = [
            	["images/marker.png", 0], ["images/marker.png", 1], ["images/map/rain_none.png", 2] 
			];
			
var icons = [
            	<?php $i = 0;?>
				<?php foreach ($exposures as $exposure): ?>
                	["<?php echo $exposure->marker;?>", <?php echo $i; ?>]<?php if( ($i != ($exposures->count()-1)) ){ echo ","; } else { echo ""; } ?>
            	<?php $i++;?>
            	<?php endforeach; ?>
			];			

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

</script>  

@stop

