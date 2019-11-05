<?php 
 //$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");
 $con = mysql_connect("localhost","warehous_root","7Pt2iFKOy8WYeCwOh");
mysql_select_db("warehous_main", $con) or die(mysql_error());

     
		

		
		
	  $querymultiple = " SELECT * FROM `warehouse_listing` WHERE `CityName` = ' Chatsworth' AND `PhotoURL` = '' ";
$res_multiple= mysql_query($querymultiple) or die(mysql_error());
while($rowmultiple1=mysql_fetch_array($res_multiple))

{
	$rowmultiple1['ListingID']."here<br>";
  $url='https://www.warehousespaces.com/images/property_photos/'.$rowmultiple1['ListingID'].'.jpg';
  
 $query3= "update  `warehouse_listing` set `PhotoURL` = '".$url."' where `ListingID`='".$rowmultiple1['ListingID']."'";

  $res3 = mysql_query($query3) ;
}
?>

