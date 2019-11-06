<?php
$sql_list_cities = "SELECT *  FROM medical_listing WHERE ListingID >= (SELECT FLOOR( MAX(ListingID) * RAND()) FROM `medical_listing` ) ORDER BY ListingID LIMIT 50;";

	try{
		$mysqli = mysqli_connect("localhost", "medicalo_db", "7CuDaKPPeXgpKT46", "medicalo_db");
		$result = $mysqli->query($sql_list_cities);
		
		}
	catch(exception $e) { var_dump($e);}
$tmpCount =0;	
print '<div class="row-fluid offset1">' ;
	while ($row = mysqli_fetch_array($result)) 
{
  print '<div class="span2" id="'.$tmpCount.'img">';
   print '<img class="img-polaroid " alt="Property Photo" src="'.$row['PhotoURL'].'"> <br><br>';
      print '<small class="img-poloroid">';
  print $row['StreetAddress'].", ". $row['CityName'];
   print '</small>';
  print '</div>'; 
	$tmpCount ++;
  if(is_int($tmpCount/5)){
  //print $tmpCount;
  print '</div><div class="row-fluid offset1" id="'.$tmpCount.'img-row">';
  }
}

print '</div>';

?>