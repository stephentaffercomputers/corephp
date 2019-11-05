 <?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$link = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

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

if (!file_exists("WarehouseSpaces08092017.xls")) {
	exit("Please copy WarehouseSpaces08092017.xls here first.\n");
}

$amenities_to_watch = array("\d+% office", "\s?[\d']+\sclear(ance)?", "high clearance", "skylights?", "yard", "secured yard", "fenced yard", "\s?\d*\soffices", "\s?\d*\sprivate offices", "free[\-\s]standing building", "freestanding( building)?", "standalone building", "light industrial zoning", "freeway access", "highway access", "foil insulation", "automotive uses permitted", "\s\d*\s*dock high door", "\s\d*\s*ground level door", "480v", "3 phase(\spower)?", "three phase power", "3\-phase", "ample parking", "fire sprinklers?", "ESFR(\ssprinkler system)?", "central air", "air conditioned", "120v", "208v", "\s?[\d,]+\svolts?", "business park", "solar", "showroom", "truck parking", "freeway frontage", "ground level loading", "exterior platform", "\s?[\d,]+\samps?", "fully secured", "no CAM fees", "street view exposure", "railway access", "street frontage", "energy efficient lighting", "built in 2001", "built in 2002", "built in 2003", "built in 2004", "built in 2005", "built in 2006", "built in 2007", "built in 2008", "built in 2009", "built in 2010", "built in 2011", "built in 2012", "built in 2013", "built in 2014", "rail access", "heavy industrial zoning", "(fully )?sprinklered", "flex space", "secure area", "clear span", "drive\-in loading", "\s?[\d']+\sceilings?", "high ceilings?", "railroad access", "^\d\d+\sfoot clear height", "[^\d'][\d']+\sclear height", "grade level loading", "active rail", "air conditioned production area", "production area", "zoned MP", "zoned M\-1", "concrete exterior walls", "professional landscaping", "attractive landspace", "truck staging area", "recently rehabbed", "recently renovated", "compressed air throughout", "suspended power outlets", "zoned C\-2", "storefront", "concrete tilt up construction", "food grade", "zoned I\-\d", "temperature controlled warehouse", "humidity controlled warehouse", "temperature and humidity controlled warehouse", "airport industrial park", "bridge cranes". "can be combined", "concrete tilt\-wall", "crane\-ready", "cranes inside", "dock high building", "drive\-in door", "drop ceilings?", "dry sprinkler system", "end\-cap", "equipped with levelers", "fenced back lot", "fenced lot", "fenced property", "freight elevator", "gas heat", "gated yard", "hub one", "loft building", "metal siding", "natural light", "new roof", "newer construction", "newly constructed", "newly painted", "newly refurbished", "newly renovated", "no columns", "one\-story", "outside storage", "public utilities", "rail served", "rail spur", "sewer & water", "(separate\s)?roll\-up doors?", "tilt\s?wall building", "tiltwall construction", "white box condition", "will divide", "Zoned: IG", "End unit", "Trailer storage", "Fireproof", "Tax abatement", "Dock high loading", "Southeast corner", "Near DFW International Airport", "Heavy Power", "Concrete floor", "Mezzanine", "Rear loading", "Climate controlled", "Close proximity to I-635", "Freeway exposure", "Cross dock warehouse", "Collington Park", "Dock high ramp", "Kempwood Business Center", "Design District", "\d[\-\s]Story", "Basement", "Airpark Center", "Immediate occupancy", "HVAC", "Security System", "Corner location", "Norfolk Industrial Park", "near Downtown Houston", "Crane Ready", "Security cameras", "Can be divided", "Two-building property", "Storefront glass", "Near LBJ Freeway", "construction yard", "contractor yard", "Columbia Gateway", "Free standing", "Office\/warehouse building", "Near Highway 78", "Drive Through Access", "Montgomery Airpark Business Center", "class [abc]", "Wide column spacing", "Heavy floor loads", "Multiple loading docks", "On\-site management", "I\-20 frontage", "Close to I\-10", "Available immediately", "Ceiling height varies", "Foreign Trade Zone", "Paint booth", "Duplex building", "200a", "240v", "3ph", "constructed in \d\d\d\d", "(\d\s)?Dock Doors?", "NE Corner", "SW Corner", "BTS office", "Montgomery Airpark Bus Ctr", "Warehouse is heated", "Standalone bldg", "15 ft ceiling", "14 ft ceiling", "M\-1 Zoning", "M1\-1 zoned", "Zoning: M1\-1", "Zoning: M1\-1", "Zoning: ml", "I\-5 Zoning", "Zoned M1", "Zone I\-2", "Hardwood Floors", "Roll Down Gate");

sort($amenities_to_watch);

$objPHPExcel = PHPExcel_IOFactory::load("WarehouseSpaces08092017.xls");

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$desc = array();

$num_of_inserted_listing = 0;

for ($i = 2; $i <= count($sheetData); $i++)
{
	$data = $sheetData[$i];
	if ($data['A'] == "Industrial")
	{	
	$sql = "SELECT ListingID FROM warehouse_listing WHERE StreetAddress = '".mysqli_real_escape_string($link, trim($data['G']))."' AND CityName = '".mysqli_real_escape_string($link, trim($data['H']))."' AND SpaceNumber ".($data['S'] == "Space 1" ? "IS NULL" : "= '".mysqli_real_escape_string($link, trim($data['S']))."'");
	
	$result = $link->query($sql);
	
	$num_rows = $result->num_rows;
echo $sql."<br /><br />";	

	$desc[$i] = $data['B'].($data['B'] == $data['G'] ? "" : " located at ".$data['G']);

	$provides = rand(1, 3);
	$desc[$i] .= ($provides == 1 ? " offers" : ($provides == 2 ? " provides" : " gives"));

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " tenants" : ($x == 2 ? " businesses" : " companies"));

	$desc[$i] .= ($provides == 2 ? " with" : "")." up to ".$data['O']." for ".(rand(1, 2) == 1 ? "rent" : "lease")." in ".$data['H'].". This";

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " listing" : ($x == 2 ? " available warehouse" : " property"))." is";

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " perfect" : ($x == 2 ? " ideal" : " great"))." for";

	$size = intval(str_replace(",", "", $data['O']));

	$x = rand(1, 3);
	if ($size > 0 && $size < 10000) $desc[$i] .= (rand(1, 2) == 1 ? " light manufacturing, industrial or storage" : " light manufacturing");
	if ($size >= 10000 && $size < 50000) $desc[$i] .= (rand(1, 2) == 1 ? " growing companies" : " large to medium size businesses");
	if ($size >= 50000) $desc[$i] .= (rand(1, 2) == 1 ? " large distribution hubs and major manufacturing operations" : " regional/national distribution hubs and major manufacturing operations");

	$matches = array();

	foreach ($amenities_to_watch AS $value)
	{
		if (!preg_match("/".$value."/i", $data['M'], $match)) continue;
		
		if ($value == "yard" && !preg_match("/secured yard/i", $data['M']) && !preg_match("/fenced yard/i", $data['M']) && !preg_match("/construction yard/i", $data['M']) && !preg_match("/contractor yard/i", $data['M'])) $matches[] = "yard";	
		elseif ($value != "yard") 
		{
			if ($value == "production area" && !preg_match("/air conditioned production area/i", $data['M'])) $matches[] = "production area";
			elseif ($value != "production area") $matches[] = trim($match[0]);
		}
	}

	if ($matches) {
		$desc[$i] .= " and ".(rand(1, 2) == 1 ? "includes" : "offers")." the following amenities:<br /><ul>";

		foreach ($matches AS $value) {
			if ($value == "recently rehabbed") $desc[$i] .= "<li>Recently Renovated</li>";
			elseif ($value == "3 phase" || $value == "3 phase power" || $value == "three phase power" || $value == "3-phase" || $value == "3ph") $desc[$i] .= "<li>3 Phase Power</li>";
			elseif ($value == "200a") $desc[$i] .= "<li>200 Amps</li>";
			elseif ($value == "240v") $desc[$i] .= "<li>240 Volt</li>";
			elseif ($value == "NEC" || $value == "NE Corner") $desc[$i] .= "<li>Northeast Corner</li>";
			elseif ($value == "NWC") $desc[$i] .= "<li>Northwest Corner</li>";
			elseif ($value == "SEC") $desc[$i] .= "<li>Southeast Corner</li>";
			elseif ($value == "SWC" || $value == "SW Corner") $desc[$i] .= "<li>Southwest Corner</li>";
			elseif ($value == "BTS office") $desc[$i] .= "<li>Build to Suit Office</li>";
			elseif ($value == "Montgomery Airpark Bus Ctr") $desc[$i] .= "<li>Montgomery Airpark Business Center</li>";
			elseif ($value == "Warehouse is heated") $desc[$i] .= "<li>Heated Warehouse</li>";
			elseif ($value == "Standalone bldg") $desc[$i] .= "<li>Standalone Building</li>";
			elseif ($value == "15 ft ceiling") $desc[$i] .= "<li>15' Ceiling</li>";
			elseif ($value == "14 ft ceiling") $desc[$i] .= "<li>14' Ceiling</li>";
			elseif ($value == "M-1 Zoning") $desc[$i] .= "<li>Zoned: M1</li>";
			elseif ($value == "M1-1 zoned") $desc[$i] .= "<li>Zoned: M1-1</li>";
			elseif ($value == "Zoning : M1-1") $desc[$i] .= "<li>Zoned: M1-1</li>";
			elseif ($value == "Zoning: M1-1") $desc[$i] .= "<li>Zoned: M1-1</li>";
			elseif ($value == "Zoning: ml") $desc[$i] .= "<li>Zoned: Ml</li>";
			elseif ($value == "I-5 Zoning") $desc[$i] .= "<li>Zoned I-5</li>";
			elseif ($value == "Zoned M1") $desc[$i] .= "<li>Zoned M-1</li>";
			elseif ($value == "Zone I-2") $desc[$i] .= "<li>Zoned: I-2</li>";
			else $desc[$i] .= "<li>".ucfirst($value)."</li>";		
		}
		
		$desc[$i] .= "</ul>";	
	}
	else $desc[$i] .= ".<br /><br />";

	$desc[$i] .= "For ".(rand(1, 2) == 1 ? "more" : "additional")." information ".(rand(1, 2) == 1 ? "on" : "regarding")." ".$data['B'].(rand(1, 2) == 1 ? " or any other" : " and other")." ";
	
	$x = rand(1, 4);
	switch ($x) {
   		case 1:
    		$desc[$i] .= "listings";
   			break;
   		case 2:
			$desc[$i] .= "warehouse listings";
			break;
		case 3:
			$desc[$i] .= "properties";
			break;
		case 4:
			$desc[$i] .= "warehouses";
			break;
	}

	$desc[$i] .= " in ".$data['H'].", ".(rand(1, 2) == 1 ? "request" : "contact us for")." ".(rand(1, 2) == 1 ? "a" : "your")." <strong>".(rand(1, 2) == 1 ? "free" : "complimentary")."</strong> property report today by ".(rand(1, 2) == 1 ? "filling out" : "completing")." our quick contact form. If you would like to ".(rand(1, 2) == 1 ? "view" : "browse")." additional ";

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? "listings" : ($x == 2 ? "spaces" : "warehouses"));

	$desc[$i] .= " visit our <a href=\"https://www.warehousespaces.com/warehouse-for-rent/United-States/".$data['I']."/".$data['H']."/\">".(rand(1, 2) == 1 ? "warehouse space in ".$data['H'] : $data['H']." warehouse space")."</a> page to see the ";

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? "full" : ($x == 2 ? "complete" : "current"))." market inventory.<br /><br />";
	
	$desc[$i] .= "<em>Listing Provided by</em> ".$data['AB']." ".$data['AA'].($data['AD'] ? " and ".$data['AD']." ".$data['AC'] : "").($data['K'] ? "<br />".$data['K'] : "")."<br /><br />";

	$desc[$i] .= "<h3>".(rand(1, 2) == 1 ? "About Us" : "About WarehouseSpaces.com")."</h3>".(rand(1, 2) == 1 ? "WarehouseSpaces.com" : "Our site")." has thousands of ";

	$x = rand(1, 4);

	switch ($x) {
   		case 1:
   			$desc[$i] .= "listings";
   			break;
   		case 2:
			$desc[$i] .= "buildings";
			break;
		case 3:
			$desc[$i] .= "properties";
			break;
		case 4:
			$desc[$i] .= "warehouses";
			break;
	}

	$desc[$i] .= " both for ".(rand(1, 2) == 1 ? "lease" : "rent")." and for sale throughout the ".(rand(1, 2) == 1 ? "United States" : "US").". ".(rand(1, 2) == 1 ? "We" : "Our agents")." survey all of the ".(rand(1, 2) == 1 ? "top" : "major")." markets on a daily basis to ".(rand(1, 2) == 1 ? "give" : "provide")." ".(rand(1, 2) == 1 ? "you" : "potential tenants")." the most comprehensive ".(rand(1, 2) == 1 ? "set" : "collection")." of listings for ".(rand(1, 2) == 1 ? "warehouse and industrial" : "warehouse")." properties.";

	if ($num_rows == 0)
	{
	 	$num_of_inserted_listing++;
		$rate = preg_replace("/[^a-zA-Z0-9\$\.\/\s]/", "", trim($data['C'].$data['D'].$data['E'].$data['F']));

		$sql = "INSERT INTO warehouse_listing (Title, SpaceNumber, SpaceAvailable, RentalRate, StreetAddress, CityName, StateProvCode, PostalCode, LotSize, LastUpdate, SpaceAvailableMax, SpaceAvailableMin, PropertySubType, PropertyType, RentalRateMin, SpaceAvailableTotal, Description, Latitude, Longitude, ListingIsActive) VALUES (";
	
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
		$sql .= "'Industrial', ";
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
		

		$sql = "UPDATE warehouse_listing SET ";

		if (file_exists('../images/property_photos/'.$listing_id.".jpg")) $sql .= "PhotoURL = 'https://www.warehousespaces.com/images/property_photos/".$listing_id.".jpg', ";
		else $sql .= "PhotoURL = NULL, ";
		
		$sql = substr($sql, 0, -2)." WHERE ListingID = ".$last_insert_id;
		
		$result = $link->query($sql);
echo $sql."<br /><br />";
	}
	else
	{
	 	$row = mysqli_fetch_row($result);
	 	$rate = preg_replace("/[^a-zA-Z0-9\$\.\/\s]/", "", trim($data['C'].$data['D'].$data['E'].$data['F']));
	
		$sql = "UPDATE warehouse_listing SET 
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