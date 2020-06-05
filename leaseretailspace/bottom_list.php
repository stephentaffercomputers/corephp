<?php
$sql_list_cities = "SELECT *  FROM retail_listing WHERE ListingID >= (SELECT FLOOR( MAX(ListingID) * RAND()) FROM `retail_listing` ) ORDER BY ListingID LIMIT 50;";

	try{
		$mysqli = mysqli_connect("localhost", "leaseret_db", "dtWDmtmHZpUgrd4R", "leaseret_db");
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