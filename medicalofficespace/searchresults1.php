<?php include 'header.php'; ?>
 <link rel="stylesheet" href="/js/leaflet/leaflet.css" />
 <!--[if lte IE 8]>
     <link rel="stylesheet" href="/leaflet/leaflet.ie.css" />
 <![endif]-->
 <link rel="stylesheet" href="/js/leaflet/MarkerCluster.Default.css" />
 <!--[if lte IE 8]>
     <link rel="stylesheet" href="/leaflet/MarkerCluster.Default.ie.css" />
 <![endif]-->
 <style type="text/css" title="currentStyle">
    @import "/js/datatables/css/demo_table.css";
	#map{width: 300px; height:300px;}
</style>
<script src="/js/leaflet/leaflet.js"></script>
<script src="/js/leaflet/leaflet.markercluster.js"></script>
<script src="/js/leaflet/leaflet.zoomfs.js"></script>

<?php
$markers_script = "";
$table_rows = "";
$q = '';
$limit=8;

if(isset($_GET["q"])) $q= explode(",", $_GET["q"]);

if (sizeof($q) > 1 )
$search_sql_stmt = "select * from medical_listing where CityName ='".trim($q[0])."' and PostalCode = '".trim($q[1])."';";
else
$q= $search_sql_stmt = "select * from medical_listing WHERE concat( CityName,StateProvCode, StateProvName, PostalCode) like '%".$q[0]."%' ORDER BY CityName"; // ."LIMIT ".$limit.";";

try{
	$mysqli = mysqli_connect("localhost", "medicalo_db", "7CuDaKPPeXgpKT46", "medicalo_db");
	$result = $mysqli->query($search_sql_stmt);
	while ($row = mysqli_fetch_array($result)) 
		{
		  $sidebar_content .= '<br><div class="row-fluid pull-middle prop_disp_small">';
		  $sidebar_content .=  '<img class="img-polaroid span2" id="'.$row['PhotoID'].'" alt="Property Photo" src="'.$row['PhotoURL'].'&amp; data-name="Photo"></div>'; 
		  $sidebar_content .= '<a href="/'.$row['StateProvCode'].'/'.$row['CityName'].'/'.$row['ListingID'].'"> view details </a><br>';
		  $sidebar_content .=  '<small>'.$row['StreetAddress'].", ". $row['CityName'].'</small>';	
			 
		  $markers_script .= "var marker_".$row['PhotoID']." = markers.addLayer(new L.marker([".$row['Latitude'].", ".$row['Longitude']."]).bindPopup('".$row['StreetAddress']." <Br> ".$row['CityName']." <br> Propertytype: ".$row['PropertyType']." | ".$row['PropertySubType']."')).addTo(map);";
		  $table_rows .= "<tr> <td>".$row['PropertyType']."</td><td>".$row['PropertySubType'].$row['Zoning']."</td><td>"."</td><td>".$row['BuildingSize']."</td><td>".$row['LotSize']."</td><td>".$row['YearBuilt']."</td><td>".$row['RentalRateMin']." - ".$row['RentalRateMax']."</td><td>".$row['SpaceAvailableMin']." - ".$row['SpaceAvailableMax']."</td><td>".$row['StreetAddress']."</td><td>".$row['CityName']."</td></tr>";
		  
		  $statecode = $row['StateProvCode'] ;
		  $cityname = $row['CityName'];
			
			}
	}
catch(exception $e) { var_dump($e);}
?>
<div id="nav-div" class="row-fluid">
	<div class="row-fluid ">
		<div class="span4">
		<ul class="breadcrumb">
		  <li><a href="/">Home</a> <span class="divider">/</span></li>
		  <li><a href="/<?=$statecode?>"><?=$statecode?></a> <span class="divider">/</span></li>
		  <li class="active"><?=$cityname?></li>
		</ul>
		</div>
		<div class="span8 btn-toolbar pull-left">
			<div class="btn-group " data-toggle="buttons-checkbox" > Select Multiple:
			  <button class="btn">Left</button>
			  <button class="btn">Middle</button>
			  <button class="btn">Right</button>
			</div>
			<div class="btn-group" data-toggle="buttons-radio"> Select One: 
			  <button type="button" class="btn btn-primary">Left</button>
			  <button type="button" class="btn btn-primary">Middle</button>
			  <button type="button" class="btn btn-primary">Right</button>
			</div>
		</div>
	</div>
</div>
 <div class="row-fluid" id="main-body">
	<div class="span3">
		<div class="row-fluid" id="map"></div>
		<div class="row-fluid" id="filter-form">
		Filter Results by: <Br>
		</div>
	</div>
 	<div class="span9" id="results-div">
		<? print $sidebar_content; ?>
	</div>
			
		<!--	<div class="span2" id="sidebar1"> -->
			  <!--Sidebar content-->
			 <?// include 'sidebar.php' ?>
			<!-- </div> -->
	</div>
	<?/*
   <div class="row-fluid span12" id="list-div">
		<?php include 'list_grid.php' ?>
	</div>	*/?>
<? include 'footer.php'; 
	
	
	/*
	if($result->num_rows > 0){
		print 'var listing_arr =[];';
		while ($row = mysqli_fetch_array($result)) {
			print "var id".$row['ListingID']."= L.marker([".$row['Latitude'].", ".$row['Longitude']."]).bindPopup('".$row['StreetAddress']."').addTo(map);";
			print "listing_arr.push('".$row['ListingID']."');";
		}
	}
	*/
?>		

	<script>
	$(document).ready( function () {

//	var listing_layer = L.layerGroup(listing_arr);
	var map = L.map('map',{ zoomControl:false }).setView([33.800903676512, -118.27401280403], 8);
	// google map key: AIzaSyCBFaQqcXtl2KcmpT8a5EQuXn-z2Ap0oDI
	L.tileLayer('http://{s}.tile.cloudmade.com/f77f44499fa343adae59fd7c4fe012a6/997/256/{z}/{x}/{y}.png').addTo(map);
	
	var zoomFS = new L.Control.ZoomFS(); 
	map.addControl(zoomFS);
	
	var markers = new L.MarkerClusterGroup();
	<? print $markers_script; ?>
	//markers.layer.zoomToBounds();
	 map.fitBounds(markers.getBounds()); 
	// listing_layer.addTo(map);
	map.on('popupopen', function(e) {
	  var clicked_marker = e.popup._source;
	  highlight_property(clicked_marker);
	});
	
	map.on("zoomend", function( e ) {
    // runs this code after you finishing the zoom

	//var new_boundry = map.getBounds().toString();
/*	 
	$.ajax({
		  url: "zoomend.php",
		  type: "post", 
		  data: {mapbounds: JSON.stringify(e.target.getBounds())},
		}).done(function(data) {
		  //refresh sidebar
		  $("#sidebar1").html(data['sidebar']);
		  //add markers to map
		   eval(data['markers']);
		  //refresh the table below
		  oTable.fnClearTable();
		 // oTable.fnAddData(data['table']);
		  oTable.fnAddTr(data['table']);
		  oTable.fnDraw();
		  
		});		
		//console.log(JSON.stringify(e.target.getBounds()));
*/	
if(map.height == '400px' )
$("#results-div").addClass('move-down');
else $("#results-div").removeClass('move-down');

console.log(" called from zoomend");
	});

	});
	
	function highlight_property(x){
		//alert(x);
	}
	
	</script>
  </body>
</html>