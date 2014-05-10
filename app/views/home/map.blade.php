@extends('public')

@section('content')

<form method="post" action="{{Request::root()}}/restaurant/search">

<div class="input-append">
 <input name="restaurant" type="text" class="input-large" id="restaurant" value="{{Input::get('restaurant')}}" placeholder="Find: Restaurants, etc">
 <!--<input name="near" type="text" class="input-large" id="near" value="{{Input::get('near')}}" placeholder="Near: Boyle Heights, LA">-->
 <input type="submit" class="btn btn-primary" value="Find Latees" id="find_latees">
 <!--<button class="btn" type="button">Find Latees</button>-->
<input name="op" type="hidden" value="1" />
</div>
</form>


<div class="container-fluid">
  <div class="row-fluid">
    <div class="span12">
      <!--<a href=""><img src="http://placehold.it/800x150" class="img-polaroid"></a>-->
    </div>
</div>
<br />

<div class="container-fluid">
  
  <div class="row-fluid">
  
        <div class="span4">
          <a href="{{Request::root()}}/restaurant/map/{{$restaurant->id}}">
          @if($restaurant->photo != '')
            <img src="{{ Request::root()}}/{{$restaurant->photo}}" class="img-polaroid" height="150" width="150">
          @else
            <img src="http://placehold.it/150x150" class="img-polaroid">
          @endif
          </a>
           <h5><a href="{{Request::root()}}/restaurant/map/{{$restaurant->id}}">{{ $restaurant->title }}</a></h5>
           <h6>{{ $restaurant->description }}</h6>
        </div>
         <div class="span8">
              <div id="mapDiv"></div>  
            	<div id="progress" class="progress hidden">
            </div>
        </div>
   
</div>







<div class="panel" style="display:none">
    <div class="titlearea"><span id="titleMessage" class="title-message">Find Places</span></div>
    <div class="controls">
      <div class="buttons"> 
        <input id="placeInput" class="" type="text" value="{{$address}}" placeholder="San Diego, CA"/>
        <label><input id="useMapExtent" type="checkbox"/>Search map only</label> 
        <button class="btn btn-primary" id="btnSearch">Go</button>
        <button class="btn" id="btnClear">Clear</button>
      </div>
    </div>
  </div>
 







  <!-- Load the library references for ArcGIS API for JavaScript -->
  <script src="http://serverapi.arcgisonline.com/jsapi/arcgis/3.5compact"></script>
  <script>
    require(["esri/map", "esri/tasks/locator", "esri/InfoTemplate", "esri/graphic", "esri/geometry/Multipoint", "{{ Request::root()}}/assets/js/utils.js", "dojo/on", "dojo/dom", "dojo/domReady!"], 
      function(Map, Geocoder, InfoTemplate, Graphic, Multipoint, utils, on, dom) {  
        "use strict"      
               
        // Create map
        var map = new Map("mapDiv", {
          basemap: "streets",
          center: [-122.69, 45.52], //long, lat
          zoom: 5
        });
        utils.autoRecenter(map);

        // Create geoservice
        var geocodeService = new Geocoder("http://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer");
        
        // Wire events
        on(map, "load", function() {
          on(geocodeService, "address-to-locations-complete", geocodeResults);
          on(geocodeService, "error", geocodeError);
          on(map.graphics,"click", geocodeSelect);          
          on(dom.byId("placeInput"), "keypress", placeInput_onKeyPress);
          on(dom.byId("btnSearch"),"click", geoSearch);
		  //on(dom.byId("find_latees"),"click", geoSearch);
			 $(document).ready(function() {
				 geoSearch();
			 });
          on(dom.byId("btnClear"),"click", clearFindGraphics);
        });

        // Geocode against the user input
        function geoSearch() {
          var searchString = dom.byId('placeInput').value;
          var boundingBox;
          if (dom.byId('useMapExtent').checked)
            boundingBox = map.extent;
          // Set geocode options
          var options = {
            address:{"SingleLine":searchString},
            outFields:["Loc_name", "Place_addr"],  // Coming soon: Place_addr will contain full address for POI locations!
            searchExtent: boundingBox
          }
          // Execute geosearch
          geocodeService.addressToLocations(options);
          utils.setStyle("progress", "progress visible");
        }
        
        // Geocode results
        function geocodeResults(places) {
          if (places.addresses.length > 0) {
            clearFindGraphics();
            // Objects for the graphic
            var place,attributes,infoTemplate,pt,graphic;
            // Create and add graphics with pop-ups
            for (var i = 0; i < places.addresses.length; i++) {
              place = places.addresses[i];
              pt = place.location;
              attributes = { address:place.address, score:place.score, lat:pt.y.toFixed(2), lon:pt.x.toFixed(2) };   
              infoTemplate = new InfoTemplate("Geocode Result","${address}<br/>Latitude: ${lat}<br/>Longitude: ${lon}<br/>Score: ${score}<span class='popupZoom' onclick='window.zoomToPlace(" + pt.x + "," + pt.y + ",15)';>Zoom To</span>");
              graphic = new Graphic(pt,sym,attributes,infoTemplate);
              map.graphics.add(graphic);
			  
			  break; // add ko
            }
            zoomToPlaces(places.addresses);
          } else {
            alert("Sorry, address or place not found.");
          }
          utils.setStyle("progress", "progress hidden");
        }
      
        function geocodeError(errorInfo) {
          utils.setStyle("progress", "progress hidden");
          alert("Sorry, place or address not found!");
        }

        var sym = utils.createPictureSymbol("{{ Request::root()}}/assets/img/esri/blue-pin.png", 0, 0, 24);
        var selSym = utils.createPictureSymbol("{{ Request::root()}}/assets/img/esri/red-pin.png", 0, 0, 24);
        var lastSel;

        function geocodeSelect(item) {
          // Create and add a selected graphic with pop-up
          item = (item.graphic ? item.graphic : item.feature);
          if (lastSel) {
            lastSel.setSymbol(sym);
          }
          item.setSymbol(selSym);
          lastSel = item;
        }

        // Listen for enter key
        function placeInput_onKeyPress(e) {
          if (e.keyCode == 13 || e.keyCode == "13") {
            geoSearch();
          }
        }

        function zoomToPlaces(places) {
          var multiPoint = new Multipoint();
          for (var i = 0; i < places.length; i++) {
              multiPoint.addPoint(places[i].location);
          }
          map.setExtent(multiPoint.getExtent().expand(2.0)); // to zoom make the value smaller
        }

        window.zoomToPlace = function zoomToPlace(lon,lat,scale) {
          map.centerAndZoom([lon,lat],scale);
        }

        function clearFindGraphics() {
          map.infoWindow.hide();
          map.graphics.clear();
        }
      }
    );
	
	
  </script>






@stop