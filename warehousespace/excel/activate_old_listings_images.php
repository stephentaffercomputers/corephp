 <?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$link = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "https://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}&key=AIzaSyDO2jhnIVC1uq_zYEodibtHbGiFi3lMG3Y";

    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if ($resp['status']=='OK') {
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
         
        // verify if data is complete
        if ($lati && $longi) {
         
            // put the data in the array
            $data_arr = array();            
             
            array_push($data_arr, $lati, $longi);

            return $data_arr;
             
        } else return false;
         
    } else return false;
}
	
$sql = "SELECT * FROM warehouse_listing WHERE Latitude IS NOT NULL AND Longitude IS NOT NULL AND PhotoURL IS NULL ORDER BY LastUpdate DESC";
	
$result = $link->query($sql);
	
while ($row = mysqli_fetch_array($result))
{	 	
 	$listing_id = $row['ListingID']."_".preg_replace("/[^a-zA-Z0-9]/", "_", $row['Title']);

//	$geodata = geocode($row['StreetAddress'].", ".$row['CityName'].", ".$row['StateProvCode']." ".$row['PostalCode']);

$geodata = array($row['Latitude'], $row['Longitude']);

	if ($geodata)
	{
		$image = file_get_contents('https://maps.googleapis.com/maps/api/streetview?size=640x640&location='.$geodata[0].','.$geodata[1].'&key=AIzaSyDO2jhnIVC1uq_zYEodibtHbGiFi3lMG3Y');

		$fp  = fopen('../images/property_photos/'.$listing_id.".jpg", 'w+'); 

		fputs($fp, $image); 
		fclose($fp); 
		unset($image);
	}

	$sql = "UPDATE warehouse_listing SET 
			PhotoURL = ".(file_exists('../images/property_photos/'.$listing_id.".jpg") ? "'http://www.warehousespaces.com/images/property_photos/".$listing_id.".jpg'" : "NULL").",
			LastUpdate = '".time()."'
			WHERE ListingID = ".$row[0];

	$result_update = $link->query($sql);
echo $sql."<br /><br />";	
}

$link->close();
?>