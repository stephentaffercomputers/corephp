 <?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$link = mysqli_connect("localhost", "medicalo_db", "7CuDaKPPeXgpKT46", "medicalo_db");

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel_IOFactory */
require_once 'Classes/PHPExcel/IOFactory.php';

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

if (!file_exists("ChicagoMedical07252017.xls")) {
	exit("Please copy ChicagoMedical07252017.xls here first.\n");
}

$amenities_to_watch = array("24[\s\-]hour security", "3\-phase electrical", "Access to major freeways", "Access to major highways", "ADA restrooms", "Adjacent parking structure", "Administrative staff space", "Ample parking", "Atrium lobby", "Available immediately", "Basement", "\s\d*\s*Bathrooms?", "Blood draw room", "Break area", "Break room", "Breakroom", "Brick building", "Building signage", "Bullpen areas?", "Business office", "Can be divided", "Card key access", "Central HVAC", "Class A building", "Cleaning service", "Clerical area", "Close to major freeways", "Close to major highways", "Close to public transportation", "Closed circuit security cameras", "Conference room", "Consult rooms", "\s\d*\s*Consultation rooms?", "Corner unit", "Covered drop\-off", "Covered parking", "Dark room", "Drop\-off area", "Easy access to public transportation", "Easy freeway access", "Efficient floorplan", "\s\d*\s*Elevators?", "Equipment room", "\s\d*\s*Exam rooms?", "\s\d*\s*Examination rooms?", "Excellent freeway access", "Excellent visibility", "File room", "File storage", "Filing room", "First floor suites", "For sale or lease", "Free parking", "Free standing building", "Freestanding building", "Freeway signage", "Front office", "Full bathroom", "Full kitchen", "Full time security", "Furnished", "Furniture available", "Garage parking( available)?", "Gated parking", "Great signage", "Handicap access", "High ceilings", "High traffic count", "High visibility", "Immediate occupancy", "Immediate availability", "Interior restroom", "Key card access", "Kitchen(ette)?", "Lab area", "Lab room", "Lab services", "Large courtyard", "Large open space", "Large windows", "LED signage", "Marble floors", "May be divisible", "Medical build\-out", "Medical office campus", "Medical office suite", "Monument signage", "Move in condition", "Move in ready", "Move\-in condition", "Move\-in ready", "Move\-in\-ready", "MRI equipment", "MRI operation office", "MRI room", "Multiple wall cabinets", "Natural light", "Near major hospitals", "New carpet", "New construction", "New HVAC", "Newly remodeled", "Newly renovated", "Nurses? station", "Nursing stations", "\d+\sOffices", "Office condo", "On site management", "On site medical disposal", "On site parking", "Onsite lab", "On\-site maintenance", "On\-site management", "Onsite MRI", "On\-site parking", "On\-site property management", "On\-site property manager", "Open floor plan", "\s\d*\s*Operating rooms?", "Operatories", "Parking stalls", "Patient drop off", "Patient rooms", "Pharmacy", "Pool area", "Private balcony", "Private bathroom", "Private entrance", "Private exam room", "Private office", "Private parking", "Private parking lot", "Private reception", "Private restroom", "Private storage", "Procedure room", "Procedure rooms", "Private balconies", "Pylon signage", "Ready for occupancy", "Ready to move in", "Recently renovated", "Reception(ist)? area", "Reception room", "Reserved parking", "Rest rooms", "\s\d*\s*Restrooms?", "Rolling file shelves", "Secure entry", "Security cameras", "Security guard", "Security system", "Server room", "Shell condition", "Single story", "Sink areas", "Sinks", "Skylights?", "Staff parking", "Storage", "Storage area", "\s\d*\s*Storage rooms?", "Street parking", "Street signage", "Surface parking", "Surgical suite", "Surveillance cameras", "\s\d*\s*Therapy rooms?", "Treatment rooms?", "Turn key space", "Ultrasound room", "Underground parking", "Valet parking", "Valet service", "Vanilla box", "Video surveillance", "Waiting areas?", "Waiting rooms?", "Wash room", "Washroom", "X\-ray( room)?", "Zoned BP", "Zoned C\-2", "Zoning: C\-2", "\d+ Buildings total", "Adjacent to I\-\d+", "After hours HVAC", "Backup generators", "Build to Suit", "Close to DFW Airport", "Conferencing facility", "Constructed in 20\d\d", "Convenience store", "Copper roof", "Easy Access to I\-\d+", "Energy efficient roof", "Exterior lighting", "Fiber available", "Five\-story office building", "Floor\-to\-Ceiling Glass", "Food service", "For sale or lease", "Frank Ogawa Plaza", "Full floors available", "Furnished office", "Furniture (is )?negotiable", "Garden office building", "Golf course views", "Granite lobby", "Hotels within walking distance", "Lab space", "Landscaped Courtyard", "Manned Security", "Miami Design District", "Near GA 400", "Near hotels?", "Near I(nterstate)?\-\d+", "Near public transportation", "Office condos for sale", "On\-site ownership", "Polished granite", "Private restroom", "Prominent signage", "Reflective Glass", "Reflective windows", "Renovation in 20\d\d", "Security cameras", "Rust aggregate", "Smoked Glass", "Solar glass", "Terra cotta", "Texas limestone", "Tinted Glass", "Valet parking", "Vanilla box", "Convenient store", "Located in the heart of midtown", "Minutes to Sky Harbor", "Points at Waterview", "Wingren submarket", "Zoned F1");

sort($amenities_to_watch);

$objPHPExcel = PHPExcel_IOFactory::load("ChicagoMedical07252017.xls");

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$desc = array();

$num_of_inserted_listing = 0;

for ($i = 2; $i <= count($sheetData); $i++)
{
	$data = $sheetData[$i];
	if ($data['R'] == "Medical Office")
	{	
		$sql = "SELECT ListingID FROM medical_listing WHERE StreetAddress = '".mysqli_real_escape_string($link, trim($data['G']))."' AND CityName = '".mysqli_real_escape_string($link, trim($data['H']))."' AND SpaceNumber ".($data['S'] == "Space 1" ? "IS NULL" : "= '".mysqli_real_escape_string($link, trim($data['S']))."'");

		$result = $link->query($sql);
	
		$num_rows = $result->num_rows;
echo $sql."<br /><br />";	

	$desc[$i] = "This medical";
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " listing" : ($x == 2 ? " property" : " office"));
		
	$desc[$i] .= " for ".(rand(1, 2) == 1 ? "lease" : "rent")." in ".$data['H'];
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " offers" : ($x == 2 ? " provides" : " features")); 
		
	$desc[$i] .= " ".$data['O']." of available space in a "; 
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? "prime" : ($x == 2 ? "great" : "excellent"));
		
	$desc[$i] .= " location. ".(rand(1, 2) == 1 ? "Located" : "Situated")." at ".$data['G'].($data['B'] == $data['G'] ? "" : " in ".$data['B'])." this";
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " space" : ($x == 2 ? " property" : " listing"));
		
	$desc[$i] .= " is ".(rand(1, 2) == 1 ? "perfect" : "ideal")." for medical ".(rand(1, 2) == 1 ? "professionals" : "practitioners")." seeking ".(rand(1, 2) == 1 ? "a versatile" : "an appealing and functional")." space for their practice. In addition to a ".(rand(1, 2) == 1 ? "great" : "prime")." location, this medical office";
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " offers" : ($x == 2 ? " provides" : " features")); 
		
	$matches = array();

	foreach ($amenities_to_watch AS $value)
	{
		if (!preg_match("/".$value."/i", $data['M'], $match)) continue;
			
		if ($value == "Available immediately" || $value == "Immediate occupancy" || $value == "Immediate availability" || $value == "Ready for occupancy") $match[0] .= " (at time of publishing)";
		if ($value == "Breakroom") $match[0] .= "Break room";
		if ($value == "Rest rooms") $match[0] .= "Restrooms";
		if ($value == "Washroom") $match[0] .= "Wash room";

		$matches[] = trim($match[0]);
	}

	if ($matches) {
		$desc[$i] .= ":<br /><ul>";

		foreach ($matches AS $value) {
			if (strtolower($value == "full floors"))
			{
				if (!preg_match("/Full floors available/i", $data['M'])) $desc[$i] .= "<li>".ucwords(strtolower($value))."</li>";
			}
			else 
			{
			 	if (strtolower($value) == "convenient store") $desc[$i] .= "<li>Convenience Store</li>";
			 	elseif (strtolower($value) == "located in the heart of midtown") $desc[$i] .= "<li>Great Midtown Location</li>";
			 	elseif (strtolower($value) == "minutes to sky harbor") $desc[$i] .= "<li>Minutes to Sky Harbor Airport</li>";
			 	elseif (strtolower($value) == "points at waterview") $desc[$i] .= "<li>Located in Points at Waterview</li>";
			 	elseif (strtolower($value) == "wingren submarket") $desc[$i] .= "<li>Wingren Area</li>";
			 	elseif (strtolower($value) == "zoned f1") $desc[$i] .= "<li>Zoned: F1</li>";
			 	else $desc[$i] .= "<li>".ucwords(strtolower($value))."</li>";	
			}
		}
		
		$desc[$i] .= "</ul>";	
	}
	else $desc[$i] .= " a number of ".(rand(1, 2) == 1 ? "great" : "valuable")." amenities.<br /><br />";

	$desc[$i] .= "For more information about medical space for ".(rand(1, 2) == 1 ? "rent" : "lease")." at ".$data['G']." in ".$data['H'].", contact";
	
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " us" : ($x == 2 ? " our company" : " our commercial real estate specialists"));
	
	$desc[$i] .= " today ".(rand(1, 2) == 1 ? "and" : "to")." get a ".(rand(1, 2) == 1 ? "free" : "complimentary")." property report. Our ".$data['H'];
	
	$desc[$i] .= (rand(1, 2) == 1 ? " agents" : " tenant representatives")." will ".(rand(1, 2) == 1 ? "give" : "provide")." you with ".(rand(1, 2) == 1 ? "all" : "detailed")." information on medical space at ".$data['G']." as well as other";
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " opportunities" : ($x == 2 ? " listings" : " availabilities"));

	$desc[$i] .= " in <a href=\"https://medicalofficespace.us/medical-office-for-rent/United-States/".$data['I']."/".$data['H']."\">".$data['H']."</a>. Everyday new ".(rand(1, 2) == 1 ? "spaces" : "opportunities")." ".(rand(1, 2) == 1 ? "come on the market" : "become available")." in ".$data['H'];

	$desc[$i] .= " so when you contact ".(rand(1, 2) == 1 ? "us" : "our team")." we'll ".(rand(1, 2) == 1 ? "do" : "perform")." an expanded search to pull even more";
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " listings" : ($x == 2 ? " properties" : " sites"));
		
	$desc[$i] .= " and give you the most choices possible.<br /><br />";
		
	if ($data['AB']) $desc[$i] .= "<strong>Listing Credit</strong><br /><br /><em>Listing Provided by</em> ".$data['AB']." ".$data['AA'].($data['AD'] ? " and ".$data['AD']." ".$data['AC'] : "")."<br /><br />";
		
	$desc[$i] .= "Note: We do not directly represent or claim to represent any listing on the site<br /><br /><h4>";
	
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? "National" : ($x == 2 ? "US" : "Nationwide"));
	
	$desc[$i] .= " ".(rand(1, 2) == 1 ? "Medical" : "Commercial")." Real Estate ".(rand(1, 2) == 1 ? "Experts" : "Specialists")."</h4><br /><br />From";
		
	$desc[$i] .= " dentists and plastic surgeons to pediatricians and dermatologists, ".(rand(1, 2) == 1 ? "medical professionals" : "doctors")." trust our ".(rand(1, 2) == 1 ? "agents" : "commercial real estate agents")." ".(rand(1, 2) == 1 ? "to handle" : "with")." their ".(rand(1, 2) == 1 ? "medical" : "specialty medical")." leasing needs. Using our";
	
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " national" : ($x == 2 ? " nationwide" : " massive"));
	
	$desc[$i] .= " database of available";
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " properties" : ($x == 2 ? " commercial listings" : " offices"));
		
	$desc[$i] .= " and the experience of our ".(rand(1, 2) == 1 ? "in-market " : "local ");
		
	$x = rand(1, 4);
	switch ($x) {
		case 1:
   			$desc[$i] .= "commercial real estate agents";
			break;
		case 2:
			$desc[$i] .= "medical specialists";
			break;
		case 3:
			$desc[$i] .= "tenant reps";
			break;
		case 4:
			$desc[$i] .= "tenant representatives";
			break;
	}
		
	$desc[$i] .= " we can show you every listing ".(rand(1, 2) == 1 ? "suited" : "perfect")." for:<br /><br /><ul><li>Dental / Orthodontic practices</li><li>Family ".(rand(1, 2) == 1 ? "practice" : "medicine")."</li><li>In-hospital / ".(rand(1, 2) == 1 ? "connected" : "adjacent")." space</li><li>".(rand(1, 2) == 1 ? "Single" : "One")." physician / group practices</li><li>Physical ".(rand(1, 2) == 1 ? "therapy" : "therapy and rehabilitation")." centers</li><li>Psychotherapy</li><li>Radiology / MRI</li><li>".(rand(1, 2) == 1 ? "Outpatient Surgery Centers" : "Surgery centers")."</li><li>".(rand(1, 2) == 1 ? "Urgent care" : "Walk-in")." clinics</li></ul>".(rand(1, 2) == 1 ? "We've " : "Our agents have ").(rand(1, 2) == 1 ? "worked with" : "assisted")." ".(rand(1, 2) == 1 ? "tenants" : "clients")." ranging from";
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " orthodontists" : ($x == 2 ? " dentists" : " endocrinologists"));
		
	$desc[$i] .= " and";
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " orthopedic surgeons" : ($x == 2 ? " plastic surgeons" : " psychiatrists"));
		
	$desc[$i] .= " to ";
		
	$x = rand(1, 4);
	switch ($x) {
		case 1:
   			$desc[$i] .= "cardiologists";
			break;
		case 2:
			$desc[$i] .= "internists";
			break;
		case 3:
			$desc[$i] .= "dermatologists";
			break;
		case 4:
			$desc[$i] .= "endocrinologists";
			break;
	}
		
	$desc[$i] .= " and";
		
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " oncologists" : ($x == 2 ? " pain management specialists" : " thoracic surgeons"));
		
	$desc[$i] .= ". Our ".(rand(1, 2) == 1 ? "national" : "US")." ".(rand(1, 2) == 1 ? "tenant reps" : "commercial real estate agents")." will find the right space at the right price for your ".(rand(1, 2) == 1 ? "practice" : "operation")." and we look forward to adding you to our list of ".(rand(1, 2) == 1 ? "distinguished" : "satisfied")." clients.<br /><br />Contact ".(rand(1, 2) == 1 ? "us" : "our agents")." and ".(rand(1, 2) == 1 ? "we'll" : "our local agents will")." begin searching for your next ".(rand(1, 2) == 1 ? "space" : "location")." today!";

	if ($num_rows == 0)
	{
	 	$num_of_inserted_listing++;
	 	$rate = preg_replace("/[^a-zA-Z0-9\$\.\/\s]/", "", trim($data['C'].$data['D'].$data['E'].$data['F']));

		$sql = "INSERT INTO medical_listing (Title, SpaceNumber, SpaceAvailable, RentalRate, StreetAddress, CityName, StateProvCode, PostalCode, LotSize, LastUpdate, SpaceAvailableMax, SpaceAvailableMin, PropertySubType, PropertyType, RentalRateMin, SpaceAvailableTotal, Description, Latitude, Longitude, ListingIsActive) VALUES (";
	
		$sql .= "'".mysqli_real_escape_string($link, trim($data['B']))."', ";
		$sql .= ($data['S'] == "Space 1" ? "NULL" : "'".mysqli_real_escape_string($link, trim($data['S']))."'").", ";
		$sql .= "'".$data['O']."', ";
		$sql .= "'".($rate ? $rate : "Negotiable")."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['G']))."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['H']))."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['I']))."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['J']))."', ";
		$sql .= "NULL, ";
		$sql .= "'".time()."', ";
		$sql .= "'".$data['O']."', ";
		$sql .= "'".$data['O']."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['R']))."', ";
		$sql .= "'Office', ";
		$sql .= "'".($rate ? $rate : "Negotiable")."', ";
		$sql .= "'".$data['O']."', ";
		$sql .= "'".mysqli_real_escape_string($link, $desc[$i])."', ";
		$sql .= ($data['AK'] ? "'".$data['AK']."', " : "NULL, ");
		$sql .= ($data['AL'] ? "'".$data['AL']."', " : "NULL, ");
		$sql .= "'y')";

		$result = $link->query($sql);
echo $num_of_inserted_listing.". ".$sql."<br /><br />";		
		$last_insert_id = mysqli_insert_id($link);
		
		$listing_id = $last_insert_id."_".preg_replace("/[^a-zA-Z0-9]/", "_", $data['B']);

		if ($data['AK'] && $data['AL']) $geodata = array($data['AK'], $data['AL']);
		else $geodata = geocode($data['G'].", ".$data['H'].", ".$data['I']." ".$data['J']);

		if ($geodata)
		{
			$image = file_get_contents('https://maps.googleapis.com/maps/api/streetview?size=640x640&location='.$geodata[0].','.$geodata[1].'&key=AIzaSyDO2jhnIVC1uq_zYEodibtHbGiFi3lMG3Y');

			$fp  = fopen('../images/property_photos/'.$listing_id.".jpg", 'w+'); 

			fputs($fp, $image); 
			fclose($fp); 
			unset($image);
		}
		

		$sql = "UPDATE medical_listing SET ";

		if (file_exists('../images/property_photos/'.$listing_id.".jpg")) $sql .= "PhotoURL = 'https://medicalofficespace.us/images/property_photos/".$listing_id.".jpg', ";
		else $sql .= "PhotoURL = NULL, ";
		
		$sql = substr($sql, 0, -2)." WHERE ListingID = ".$last_insert_id;
		
		$result = $link->query($sql);
echo $sql."<br /><br />";
	}
	else
	{
	 	$row = mysqli_fetch_row($result);
	 	$rate = preg_replace("/[^a-zA-Z0-9\$\.\/\s]/", "", trim($data['C'].$data['D'].$data['E'].$data['F']));
	 	
	 	$listing_id = $row[0]."_".preg_replace("/[^a-zA-Z0-9]/", "_", $data['B']);
	
		$sql = "UPDATE medical_listing SET 
			Title = '".mysqli_real_escape_string($link, trim($data['B']))."', 
			SpaceAvailable = '".$data['O']."', 
			RentalRate = '".($rate ? $rate : "Negotiable")."',
			SpaceAvailableMax = '".$data['O']."', 
			SpaceAvailableMin = '".$data['O']."', 
			PropertySubType = '".mysqli_real_escape_string($link, trim($data['R']))."', 
			RentalRateMin = '".($rate ? $rate : "Negotiable")."',
			SpaceAvailableTotal = '".$data['O']."',
			Description = '".mysqli_real_escape_string($link, $desc[$i])."',
			LastUpdate = '".time()."',
			ListingIsActive = 'y'
			WHERE ListingID = ".$row[0];
		
		$result = $link->query($sql);
echo $sql."<br /><br />";	
	}
}
}

$link->close();
?>