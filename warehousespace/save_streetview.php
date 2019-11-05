<?
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

$geodata = geocode("Union Square, San Francisco, CA");

if ($geodata)
{
	$image = file_get_contents('https://maps.googleapis.com/maps/api/streetview?size=640x640&location='.$geodata[0].','.$geodata[1].'&key=AIzaSyDO2jhnIVC1uq_zYEodibtHbGiFi3lMG3Y');

	$fp  = fopen('listing.jpg', 'w+'); 

	fputs($fp, $image); 
	fclose($fp); 
	unset($image);
}
?>