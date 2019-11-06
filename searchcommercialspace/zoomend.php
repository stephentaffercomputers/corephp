<?php
if (!isset($result) && isset($_POST["mapbounds"]) )
{
$mapbounds = json_decode($_POST["mapbounds"]);
//var_dump($mapbounds);
//print $mapbounds->{"_southWest"}->{"lat"};
print '<script>$markers_script = ""; $table_rows = "";</script>';

$search_sql_stmt = "SELECT *  FROM listings WHERE ListingIsActive = 'y' AND Latitude between ".$mapbounds->{"_southWest"}->{"lat"}." and ".$mapbounds->{"_northEast"}->{"lat"}." and Longitude between ".$mapbounds->{"_southWest"}->{"lng"}." and ".$mapbounds->{"_northEast"}->{"lng"}." ORDER BY ListingID;";

//print $search_sql_stmt;

try{
		$mysqli = mysqli_connect("localhost", "searchco_db", "7T2kHlRhuyLHFEKU", "searchco_db");
		$result = $mysqli->query($search_sql_stmt);
		
		}
	catch(exception $e) { var_dump($e);}
}

while ($row = mysqli_fetch_array($result)) 
{
  $sidebar_content .= '<div class="row-fluid pull-middle prop_disp_small">';
  $sidebar_content .=  '<img class="img-polaroid span8" id="'.$row['PhotoID'].'" alt="Property Photo" src="'.$row['PhotoURL'].'&amp; data-name="Photo"></div>'; 
  $sidebar_content .=  '<small>'.$row['StreetAddress'].", ". $row['CityName'].'</small>';	
   
  $markers_script .= "var marker_".$row['PhotoID']." = markers.addLayer(new L.marker([".$row['Latitude'].", ".$row['Longitude']."]).bindPopup('".$row['StreetAddress']." <Br> ".$row['CityName']." <br> Propertytype: ".$row['PropertyType']." | ".$row['PropertySubType']."')).addTo(map);";
  $table_rows .= "<tr> <td>".$row['PropertyType']."</td><td>".$row['PropertySubType'].$row['Zoning']."</td><td>"."</td><td>".$row['BuildingSize']."</td><td>".$row['LotSize']."</td><td>".$row['YearBuilt']."</td><td>".$row['RentalRateMin']." - ".$row['RentalRateMax']."</td><td>".$row['SpaceAvailableMin']." - ".$row['SpaceAvailableMax']."</td><td>".$row['StreetAddress']."</td><td>".$row['CityName']."</td></tr>";
 	
	}
	
	$ret_array = array();
	$ret_array["sidebar"] = $sidebar_content;
	$ret_array["markers"] = $markers_script;
	$ret_array["table"] = $table_rows;
	
	echo json_encode($ret_array);
?>