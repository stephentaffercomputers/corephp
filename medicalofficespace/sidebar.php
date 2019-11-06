<?
//var_dump($_POST["mapbounds"]);


	while ($row = mysqli_fetch_array($result)) 
{
  $sidebar_content .= '<br><div class="row-fluid pull-middle" id="prop_disp_small">';
  $sidebar_content .=  '<img class="img-polaroid span8" id="'.$row['PhotoID'].'" alt="Property Photo" src="'.$row['PhotoURL'].'&amp; data-name="Photo"></div>'; 
  $sidebar_content .= '<a href="/'.$row['StateProvCode'].'/'.$row['CityName'].'/'.$row['ListingID'].'"> view details </a><br>';
  $sidebar_content .=  '<small>'.$row['StreetAddress'].", ". $row['CityName'].'</small>';	
     
  $markers_script .= "var marker_".$row['PhotoID']." = markers.addLayer(new L.marker([".$row['Latitude'].", ".$row['Longitude']."]).bindPopup('".$row['StreetAddress']." <Br> ".$row['CityName']." <br> Propertytype: ".$row['PropertyType']." | ".$row['PropertySubType']."')).addTo(map);";
  $table_rows .= "<tr> <td>".$row['PropertyType']."</td><td>".$row['PropertySubType'].$row['Zoning']."</td><td>"."</td><td>".$row['BuildingSize']."</td><td>".$row['LotSize']."</td><td>".$row['YearBuilt']."</td><td>".$row['RentalRateMin']." - ".$row['RentalRateMax']."</td><td>".$row['SpaceAvailableMin']." - ".$row['SpaceAvailableMax']."</td><td>".$row['StreetAddress']."</td><td>".$row['CityName']."</td></tr>";
 	
	}
	
print $sidebar_content;
?>