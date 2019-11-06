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
</style>
<script src="/js/leaflet/leaflet.js"></script>
<script src="/js/leaflet/leaflet.markercluster.js"></script>


<?php
$markers_script = "";
$table_rows = "";
$q = '';
$query = "";
$limit=0;
$skip= 0;

if(isset($_GET["q"])) $q= explode(",", $_GET["q"]);

if (sizeof($q) > 1 ){
$query = array('$or' => array ("Address.CityName" => trim($q[0]), "Address.PostalCode" => trim($q[1])));
var_dump($query);
}
else
{
$query =  array ("Address.CityName" => trim($q[0]));
}
//$query = "\$OR{ CityName : '"+$q+"' }, { 'StateProvCode' : "+$q+"{ 'StateProvName' : '"+$q+" {'PostalCode' :"+$q+"'}'"; // ."LIMIT ".$limit.";";


try{
$m = new MongoClient();
$db = $m->selectDB('medicalo_db');
$collection = new MongoCollection($db, 'wlist');
$cursor = $collection->find($query);

$sidebar_content = "";
$markers_script = "";
$detail_rows = "";

//var_dump($cursor);
		
	 foreach ($cursor as $row) {
//	var_dump($row);
		  $sidebar_content .= '<br><div class="row-fluid pull-middle prop_disp_small">';
		  $sidebar_content .=  '<img class="img-polaroid span8" id="'.$row['Photo']['Id'].'" alt="Property Photo" src="http://images.loopnet.com/xnet/mainsite/HttpHandlers/attachment/ServeAttachment.ashx?FileGuid='.$row['Photo']['Id'].'&Extension='.$row['Photo']['Ext'].'&Width=98&height=75&amp; data-name="Photo"></div>'; 
		  $sidebar_content .= '<a href="/'.$row['Address']['StateProvCode'].'/'.$row['Address']['CityName'].'/'.$row['Id'].'"> view details </a><br>';
		  $sidebar_content .=  '<small>'.$row['Address']['StreetAddress'].", ". $row['Address']['CityName'].'</small>';	
			 
		  $markers_script .= "var marker_".$row['Photo']['Id']." = markers.addLayer(new L.marker([".$row['Address']['Geopoint']['Latitude'].", ".$row['Address']['Geopoint']['Longitude']."]).bindPopup('".$row['Address']['StreetAddress']." <Br> ".$row['Address']['CityName']." <br> Propertytype: ).addTo(map);";
		  //$row['PropertyType']." | ".
		  foreach($row['Details'] as $details ){
			$table_rows .= "<tr> <td>".$details['Name']." </td><td> ". $details['Value'][0]. "</td></tr>";
		// var_dump($details);
			}
		/*	
		  $table_rows .= "<tr> <td>".//$row['PropertyType']."</td><td>".
		  $row['Details']['Property Sub-type'].$row['Zoning']."</td><td>"."</td><td>".$row['Details']['BuildingSize']."</td><td>".$row['Details']['LotSize']."</td><td>".$row['Details']['YearBuilt']."</td><td>".$row['Details']['RentalRateMin']." - ".$row['Details']['RentalRateMax']."</td><td>".$row['Details']['SpaceAvailableMin']." - ".$row['SpaceAvailableMax']."</td><td>".$row['Address']['StreetAddress']."</td><td>".$row['Adress']['CityName']."</td></tr>";
		  */

//			echo $sidebar_content;
			}
//		$conn->close();
	
	}
	
catch(exception $e) { var_dump($e);}
?>
 <div class="row-fluid" id="main-body">
			<div class="span10" id="results-div">
			  <!--Body content-->
				<!---
				<form>
					<input type="text" class="search-query span4" placeholder="Search">
				</form>
				--->
				<div class="row-fluid btn-toolbar pull-left">
				<ul class="breadcrumb">
				  <li><a href="/">Home</a> <span class="divider">/</span></li>
				  <li><a href="/">Library</a> <span class="divider">/</span></li>
				  <li class="active">Data</li>
				</ul>
					<div class="btn-group" data-toggle="buttons-checkbox"> Select Multiple:
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
				<div class="row-fluid" id="map"></div>
			</div>
			<div class="span2" id="sidebar1">
			  <!--Sidebar content-->
			 <? include 'sidebar.php' ?>
			</div>
	</div>
   <div class="row-fluid span12" id="list-div">
		<?php include 'list_grid.php' ?>
	</div>	
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
	var map = L.map('map').setView([33.800903676512, -118.27401280403], 8);
	// google map key: AIzaSyCBFaQqcXtl2KcmpT8a5EQuXn-z2Ap0oDI
	L.tileLayer('http://{s}.tile.cloudmade.com/f77f44499fa343adae59fd7c4fe012a6/997/256/{z}/{x}/{y}.png').addTo(map);
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
	});

	});
	
	function highlight_property(x){
		//alert(x);
	}
	
	</script>
  </body>
</html>