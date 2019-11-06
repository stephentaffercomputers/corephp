<?php 
include 'header.php';

function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if ($resp['status']=='OK') {
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if ($lati && $longi && $formatted_address) {
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        } else {
            return false;
        }
         
    } else {
        return false;
    }
}

if ($_POST['is_submit'] && $_POST['listing_have_right'])
{
	try {
        $mysqli = mysqli_connect("localhost", "searchco_db", "7T2kHlRhuyLHFEKU", "searchco_db");
	}
	catch(exception $e) { var_dump($e);}

	if (is_numeric($_POST['listing_size'])) $listing_size = "'".number_format(intval($_POST['listing_size']))." SF'";
	else $listing_size = "NULL";

	if ($_POST['listing_city'] && $_POST['listing_state']) $geodata = geocode(trim($_POST['listing_address'])." ".trim($_POST['listing_city']).", ".trim($_POST['listing_state'])." ".trim($_POST['listing_zip']));
	else $geodata = array();

	$sql = "INSERT INTO listings (Title, SpaceNumber, SpaceAvailable, RentalRate, StreetAddress, CityName, StateProvCode, PostalCode, LotSize, LastUpdate, SpaceAvailableMax, SpaceAvailableMin, PropertySubType, PropertyType, RentalRateMin, SpaceAvailableTotal, Description, Latitude, Longitude, ListingIsActive, AgentName, AgentEmail, AgentPhone) VALUES (";
	
	$sql .= "'".mysqli_real_escape_string($mysqli, trim($_POST['listing_title']))."', ";
	$sql .= "NULL, ";
	$sql .= $listing_size.", ";
	$sql .= "'Negotiable', ";
	$sql .= "'".mysqli_real_escape_string($mysqli, trim($_POST['listing_address']))."', ";
	$sql .= "'".mysqli_real_escape_string($mysqli, trim($_POST['listing_city']))."', ";
	$sql .= "'".mysqli_real_escape_string($mysqli, trim($_POST['listing_state']))."', ";
	$sql .= "'".mysqli_real_escape_string($mysqli, trim($_POST['listing_zip']))."', ";
	$sql .= "NULL, ";
	$sql .= "NULL, ";
	$sql .= $listing_size.", ";
	$sql .= $listing_size.", ";
	$sql .= "'".mysqli_real_escape_string($mysqli, trim($_POST['listing_type']))."', ";
	$sql .= "'Industrial', ";
	$sql .= "'Negotiable', ";
	$sql .= $listing_size.", ";
	$sql .= "'".mysqli_real_escape_string($mysqli, $_POST['listing_description'])."', ";
	$sql .= ($geodata[0] ? $geodata[0] : "NULL").", ";
	$sql .= ($geodata[0] ? $geodata[1] : "NULL").", ";
	$sql .= "'n', ";
	$sql .= "'".mysqli_real_escape_string($mysqli, trim($_POST['AgentName']))."', ";
	$sql .= "'".mysqli_real_escape_string($mysqli, trim($_POST['AgentEmail']))."', ";
	$sql .= "'".mysqli_real_escape_string($mysqli, trim($_POST['AgentPhone']))."'";	
	$sql .= ")";

	$result = $mysqli->query($sql);
	$last_insert_id = mysqli_insert_id($mysqli);
	
	if ($_FILES['listing_image'] && $_FILES['listing_image']['error'] == UPLOAD_ERR_OK) {
		$path = "images/property_photos/";
	
		$img = $_FILES['listing_image']['tmp_name'];
		$dst = $path.$last_insert_id."_property_photo.jpg";

		if (($img_info = getimagesize($img)) !== FALSE) {
			$width = $img_info[0];
			$height = $img_info[1];

			switch ($img_info[2]) {
  				case IMAGETYPE_GIF  : $src = imagecreatefromgif($img);  break;
  				case IMAGETYPE_JPEG : $src = imagecreatefromjpeg($img); break;
  				case IMAGETYPE_PNG  : $src = imagecreatefrompng($img);  break;
  				default : die("Unknown filetype");
			}

			$tmp = imagecreatetruecolor($width, $height);
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
			imagejpeg($tmp, $dst);
			
			$sql = "UPDATE listings SET ";
			$sql .= "PhotoURL = 'http://www.searchcommercialspace.com/".$dst."' ";
			$sql .= "WHERE ListingID = ".$last_insert_id;

			$result = $mysqli->query($sql);
		}
	}

	mail("johngalaxidas@synreg.com", "searchcommercialspace.com - New Listing Submission", "A new listing was submitted in the searchcommercialspace.com database.<br /><br />Please go to <a href='http://www.searchcommercialspace.com/listing_preview.php?id=".$last_insert_id."'>http://www.searchcommercialspace.com/listing_preview.php?id=".$last_insert_id."</a> and approve or delete the listing.", 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n");
}
?>
		<div class="section section-breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1>Add Listing</h1>
					</div>
				</div>
			</div>
		</div>      
        <div class="section">
	        <div class="container">
	        	Thank you for submitting your listing!<br /><br />You can expect your listings to appear on our site within two business days of your submission. 
	        </div>
	    </div>
<?php include 'footer.php'; ?>    
	        	