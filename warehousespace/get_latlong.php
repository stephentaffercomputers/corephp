<?php

$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

$search_sql_stmt = "select * from warehouse_listing WHERE ListingIsActive = 'y'";
$result = $mysqli->query($search_sql_stmt);

$image_data = array();
$i=0;
while ($row = mysqli_fetch_array($result)) {

if(empty($row['Latitude']) && empty($row['Longitude'])) {
echo $address = $row['StreetAddress'].",". $row['CityName'].",".$row['StateProvCode'];
$image_data[$i]['ListingID'] = $row['ListingID'];
$image_data[$i]['address'] = $address;
$i++;
}    
}

foreach($image_data as $image) {
$url = "http://maps.google.com/maps/api/geocode/json?address=".urlencode($image['address']);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
$responseJson = curl_exec($ch);
curl_close($ch);

$response = json_decode($responseJson);

if ($response->status == 'OK') {
    $latitude = $response->results[0]->geometry->location->lat;
    $longitude = $response->results[0]->geometry->location->lng;
echo "<br>" . $image['address'];
echo "<br>" . $update_sql = "UPDATE warehouse_listing SET Latitude= '".$latitude."', Longitude= '".$longitude."' where ListingID= '".$image['ListingID']."'";
$result = $mysqli->query($update_sql);
}
}


?>