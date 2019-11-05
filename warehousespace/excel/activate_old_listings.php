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
	
$sql = "SELECT * FROM warehouse_listing WHERE ListingIsActive = 'n'";
	
$result = $link->query($sql);
	
while ($row = mysqli_fetch_array($result))
{	 	
 	$listing_id = $row['ListingID']."_".preg_replace("/[^a-zA-Z0-9]/", "_", $row['Title']);

	$geodata = geocode($row['StreetAddress'].", ".$row['CityName'].", ".$row['StateProvCode']." ".$row['PostalCode']);

	if ($geodata)
	{
		$image = file_get_contents('https://maps.googleapis.com/maps/api/streetview?size=640x640&location='.$geodata[0].','.$geodata[1].'&key=AIzaSyDO2jhnIVC1uq_zYEodibtHbGiFi3lMG3Y');

		$fp  = fopen('../images/property_photos/'.$listing_id.".jpg", 'w+'); 

		fputs($fp, $image); 
		fclose($fp); 
		unset($image);
		
		if (file_exists('../images/property_photos/'.$listing_id.".jpg"))
		{
			$sql = "UPDATE warehouse_listing SET 
				RentalRate = 'Negotiable',
				RentalRateMin = 'Negotiable',
				MonthlyRate = NULL,
				PhotoURL = 'http://www.warehousespaces.com/images/property_photos/".$listing_id.".jpg',
				PhotoURL2 = NULL,
				PhotoURL3 = NULL,
				PhotoURL4 = NULL,
				PhotoURL5 = NULL,
				LastUpdate = '".time()."',
				ListingIsActive = 'y'
				".($geodata ? ", Latitude = '".$geodata[0]."', Longitude = '".$geodata[1]."'" : "")."
				WHERE ListingID = ".$row[0];

			$result_update = $link->query($sql);
			echo $sql."<br /><br />";	
		}
	}
}

$link->close();
?>