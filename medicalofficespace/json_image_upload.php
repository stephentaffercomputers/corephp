<?php
$mysqli = mysqli_connect("localhost", "medicalo_db", "7CuDaKPPeXgpKT46", "medicalo_db");

//$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

//echo $search_sql_stmt = "select * from medical_listing WHERE ListingIsActive = 'y' limit 5299 OFFSET 5305";
echo $search_sql_stmt = "select * from medical_listing WHERE ListingIsActive = 'y' and ListingID='2384'";

$result = $mysqli->query($search_sql_stmt);

$image_data = array();
$i=0;
while ($row = mysqli_fetch_array($result)) {

	if(empty($row['PhotoURL']) && !empty($row['Latitude']) && !empty($row['Longitude'])) {
$image_data[$i]['ListingID'] = $row['ListingID']; 
$image_data[$i]['Latitude'] = $row['Latitude'];
$image_data[$i]['Longitude'] = $row['Longitude'];
$image_data[$i]['Title'] = $row['Title'];
/*		$listing_id = $row['ListingID']."_".preg_replace("/[^a-zA-Z0-9]/", "_", $row['Title']);
			$image = file_get_contents('https://maps.googleapis.com/maps/api/streetview?size=640x640&location='.$row['Latitude'].','.$row['Longitude'].'&key=AIzaSyDO2jhnIVC1uq_zYEodibtHbGiFi3lMG3Y');

			$fp  = fopen('images/json_photos/'.$listing_id.".jpg", 'w+'); 
			fputs($fp, $image); 
$photo_url = "https://www.warehousespaces.com/images/json_photos/".$listing_id.".jpg";
        echo "<br>" .  $update_sql = "UPDATE warehouse_listing SET PhotoURL= '".$photo_url ."' where ListingID= '".$row['ListingID']."'";
            $result = $mysqli->query($update_sql);
*/
$i++;
}
}
//echo "<pre>";
//print_r($image_data);
//exit;

foreach($image_data as $data) { 
$listing_id = $data['ListingID']."_".preg_replace("/[^a-zA-Z0-9]/", "_", $data['Title']);
			$image = file_get_contents('https://maps.googleapis.com/maps/api/streetview?size=640x640&location='.$data['Latitude'].','.$data['Longitude'].'&key=AIzaSyDO2jhnIVC1uq_zYEodibtHbGiFi3lMG3Y');

			$fp  = fopen('images/json_photos/'.$listing_id.".jpg", 'w+'); 
			fputs($fp, $image); 
$photo_url = "https://medicalofficespace.us/images/json_photos/".$listing_id.".jpg";
        echo "<br>" .  $update_sql = "UPDATE medical_listing SET PhotoURL= '".$photo_url ."' where ListingID= '".$data['ListingID']."'";
            $result = $mysqli->query($update_sql);
}

?>