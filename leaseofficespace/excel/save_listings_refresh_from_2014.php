 <?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$link = mysqli_connect("localhost", "leaseoff_db", "BTrsHGcg21vnwUpt", "leaseoff_db");

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel_IOFactory */
require_once 'Classes/PHPExcel/IOFactory.php';

if (!file_exists("NewOfficeList_2014.xls")) {
	exit("Please copy NewOfficeList_2014.xls here first.\n");
}

$amenities_to_watch = array("24\/7 Access", "24\/7 Building Access", "24\/7 Security", "24\-Hour Security", "24 Hour Access", "Abundant Parking", "Adjacent Convention Center", "Adjacent Hotel", "Adjacent to Caltrain", "Administration Area", "Amazing Views", "Ample Parking", "Atrium with Fountain", "Auditorium", "Backup Generator", "Back\-up Generator", "Balcony", "Bay Views", "Bicycle Storage", "Bike Storage", "Blocks from the Highway", "Blocks from the Interstate", "Break Area", "Break Room", "Breakroom", "Brick Exterior", "Building Signage", "Building Top Signage", "Built in 20\d\d", "Bullpen", "Bullpen Area", "Business Park", "Cafeteria", "Call Center", "Campus", "Campus Style", "Cardscan Security Access", "Central Business District", "City Views", "Close proximity to hospitals", "Close to Airport", "Close to City Hall", "Close to Downtown", "Close to Downtown Dallas", "Close to Public Transportation", "Column-Free Floor Plates", "Completed in 20\d\d", "Concierge", "Conference Center", "\s\d*\s*Conference Rooms?", "Consultation Rooms", "Copy Room", "Corner Offices", "Covered Parking", "Covered Secure Parking", "Creative Space", "\s\d*\s*Cubicles", "Data Center", "Datacenter", "Direct Access to the MBTA", "Divisible", "Doctor's Office", "Doctor's Offices", "Document Shredding", "Document Storage", "Easy Access to Public Transportation", "Easy Access to Subway", "Easy Access to DART", "Easy Access to MBTA", "Easy Freeway Access", "Easy Highway Access", "Eighteenth Floor", "Eighth Floor", "Eleventh Floor", "Energy Star Certified", "Entire Building Available", "Entire Floor", "\s\d*\s*Exam Rooms?", "(Exercise )?Gym", "Exposed Brick", "Exposed Ceilings", "Fiber Capability", "Fiber Optic Internet", "Fiber Optic Wiring", "Fifteenth Floor", "Fifth Floor", "File Room", "Fitness Center", "Five Minutes to Airport", "Flexible Floor Plan", "Floor to Ceiling Windows", "Floor\-to\-Ceiling Windows", "Fourteenth Floor", "Fourth Floor", "Free Overnight Parking", "Free Parking", "Free Surface Parking", "Freestanding Building", "Freeway Signage", "Freeway Visibility", "Full Floor", "Fully Built Out", "(Fully )?Furnished", "Furniture Included", "Generator Backup", "GMP Space", "Granite Bathrooms", "Handicap Accessible", "Hardwood Floors", "Health Club", "High Ceilings", "Highway Visibility", "Historic Building", "Historic Landmark", "HVAC Included", "Immediate Freeway Access", "Individual HVAC", "Interior Courtyard", "IT Room", "Italian Marble Atrium", "Keyless Access", "Kitchen", "Kitchen with Seating Area", "Kitchenette", "Lab(oratory)?", "Landmark Building", "Large Open Area", "LEED Certified( Gold)?( Silver)?", "LEED Gold Certified", "LEED Silver Certified", "LEED Certification at the Gold Level", "LEED Certification at the Silver Level", "Lush Landscaping", "Marina Views", "MARTA Access", "MARTA Station Nearby", "Medical Office", "Medical Records Storage", "Minutes to Airport", "Minutes to Downtown", "Missile Impact Resistant Glass", "Monument Signage", "Move in ready", "Move\-in ready", "Multiple Private Offices", "Nature Walk", "New Construction", "New HVAC Systems", "Newly Renovated", "Nineteenth Floor", "Ninth Floor", "Office Campus", "\s\d+\s*Offices", "On\-Site Cafe", "On\-Site Dining", "On\-Site Management", "On\-Site Pharmacy", "On\-Site Property Management", "On-Site Property Manager", "On-Site Retail", "On-Site Security", "Open Area", "Open Floor Plan", "Open Office Environment", "Open Parking", "Operable Windows", "Original Brick", "Panoramic Views", "Parking Garage", "Pharmacy Rooms?", "Plug & Play", "Plug N Play", "Plug and Play", "Private Bathroom", "Private Entrance", "\s\d*\s*Private Offices?", "Production Room", "Purification Room", "R&D Lab", "Reception( Area)?", "Recently Renovated", "Restrooms", "Rooftop Deck", "Second Floor", "Secure Parking", "Secure Patio", "Separate Restroom", "Server Room", "Seventeenth Floor", "Seventh Floor", "Signage Available", "Signage Possibilities", "Signage Potential", "Single Tenant", "Sixteenth Floor", "Sixth Floor", "Storage", "Sundry Shop", "Sundry Store", "Surgical Suite", "T1", "Tenth Floor", "Third Floor", "Training Room", "Twelfth Floor", "Twentieth Floor", "Underground Parking", "Views of the Mountains", "Walking Distance to the Capitol", "Walking Distance to Shops and Restaurants", "Waiting Rooms?", "Walking Distance to Restaurants", "Waterfront Views", "Wired", "\s\d*\s*Work Stations", "\s\d*\s*Workstations", "Zoned BP", "Zoned C1", "Zoned C-1", "Zoned C2", "Zoned C-2", "Zoned CO", "Zoning C-O");

$objPHPExcel = PHPExcel_IOFactory::load("NewOfficeList_2014.xls");

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$desc = array();

$num_of_inserted_listing = 0;

for ($i = 2; $i <= count($sheetData); $i++)
{
	$data = $sheetData[$i];
	
	$sql = "SELECT ListingID FROM office_listing WHERE StreetAddress = '".mysqli_real_escape_string($link, trim($data['G']))."' AND CityName = '".mysqli_real_escape_string($link, trim($data['H']))."' AND SpaceNumber ".($data['R'] == "Space 1" ? "IS NULL" : "= '".mysqli_real_escape_string($link, trim($data['R']))."'");
	
	$result = $link->query($sql);
	
	$num_rows = $result->num_rows;
echo $sql."<br /><br />";	

	$desc[$i] = $data['B'].($data['B'] == $data['G'] ? "" : " located at ".$data['G']);

	$provides = rand(1, 3);
	$desc[$i] .= ($provides == 1 ? " offers" : ($provides == 2 ? " provides" : " gives"));

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " tenants" : ($x == 2 ? " businesses" : " companies"));

	$desc[$i] .= ($provides == 2 ? " with" : "")." up to ".$data['N']." for ".(rand(1, 2) == 1 ? "rent" : "lease")." in ".$data['H'].". This".($data['M'] ? " Class ".$data['M'] : "");

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " listing" : ($x == 2 ? " available office listing" : " property"))." is";

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " perfect" : ($x == 2 ? " ideal" : " great"))." for";

	$size = intval(str_replace(",", "", $data['N']));

	$x = rand(1, 3);
	if ($size > 0 && $size < 2500) $desc[$i] .= ($x == 1 ? " small businesses" : ($x == 2 ? " growing companies and startups" : " small firms"));
	if ($size >= 2501 && $size < 5000) $desc[$i] .= (rand(1, 2) == 1 ? " small to medium sized businesses" : " growing firms");
	if ($size >= 5001 && $size < 12000) $desc[$i] .= ($x == 1 ? " large firms" : ($x == 2 ? " large companies" : " regional offices"));
	if ($size >= 12001 && $size < 30000) $desc[$i] .= (rand(1, 2) == 1 ? " large operations or corporate hubs" : " large firms or entire corporate divisions");
	if ($size >= 30001) $desc[$i] .= (rand(1, 2) == 1 ? " major regional offices or corporate headquarters" : " major operations centers or corporate HQ");
	
	$desc[$i] .= (rand(1, 2) == 1 ? " seeking" : " searching for");
	
	if ($data['M'])
	{
		if ($data['M'] == 'A') 
		{
			$x = rand(1, 5);
			if ($x == 1) $desc[$i] .= " premium";
			if ($x == 2) $desc[$i] .= " luxurious";
			if ($x == 3) $desc[$i] .= " well-appointed";
			if ($x == 4) $desc[$i] .= " high-end";
			if ($x == 5) $desc[$i] .= " upscale";	
		}
		if ($data['M'] == 'B') 
		 	$desc[$i] .= (rand(1, 2) == 1 ? " high quality" : " great");
		if ($data['M'] == 'C') 
		 	$desc[$i] .= (rand(1, 2) == 1 ? " value based" : " practical");
	}
	
	$desc[$i] .= " space";

	$matches = array();

	foreach ($amenities_to_watch AS $value)
	{
		if (!preg_match("/".$value."/i", $data['L'], $match)) continue;

		$matches[] = trim($match[0]);
	}

	if ($matches) {
		$desc[$i] .= " and ".(rand(1, 2) == 1 ? "includes" : "offers")." the following amenities:<br /><ul>";

		foreach ($matches AS $value) $desc[$i] .= "<li>".ucwords(strtolower($value))."</li>";
		
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
			$desc[$i] .= "office listings";
			break;
		case 3:
			$desc[$i] .= "properties";
			break;
		case 4:
			$desc[$i] .= "offices";
			break;
	}

	$desc[$i] .= " in ".$data['H'].", ".(rand(1, 2) == 1 ? "request" : "contact")." ".(rand(1, 2) == 1 ? "a" : "your")." <strong>".(rand(1, 2) == 1 ? "free" : "complimentary")."</strong> property report today by ".(rand(1, 2) == 1 ? "filling out" : "completing")." our quick contact form. If you would like to ".(rand(1, 2) == 1 ? "view" : "browse")." additional ";

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? "listings" : ($x == 2 ? "spaces" : "offices"));

	$desc[$i] .= " visit our <a href=\"http://leaseofficespace.net/offices-for-rent/".$data['I']."/".$data['H']."/\">".(rand(1, 2) == 1 ? "office space in ".$data['H'] : $data['H']." office space")."</a> page to see the ";

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? "full" : ($x == 2 ? "complete" : "current"))." market inventory.<br /><br />";

	$desc[$i] .= "<h3>".(rand(1, 2) == 1 ? "About Us" : "About LeaseOfficeSpace.net")."</h3>".(rand(1, 2) == 1 ? "LeaseOfficeSpace.net" : "Our site")." has thousands of ";

	$x = rand(1, 3);

	switch ($x) {
   		case 1:
   			$desc[$i] .= "listings";
   			break;
   		case 2:
			$desc[$i] .= "offices";
			break;
		case 3:
			$desc[$i] .= "properties";
			break;
	}

	$desc[$i] .= " both for ".(rand(1, 2) == 1 ? "lease" : "rent")." and for sale throughout the ".(rand(1, 2) == 1 ? "United States" : "US").". ".(rand(1, 2) == 1 ? "We" : "Our agents")." survey all of the ".(rand(1, 2) == 1 ? "top" : "major")." markets on a daily basis to ".(rand(1, 2) == 1 ? "give" : "provide")." ".(rand(1, 2) == 1 ? "you" : "potential tenants")." the most comprehensive ".(rand(1, 2) == 1 ? "set" : "collection")." of listings for ".(rand(1, 2) == 1 ? "available office" : "office")." properties.";
	
	$desc[$i] .= "<br /><br /><em>Listing Provided by</em> ".$data['X']." ".$data['W'].($data['Z'] ? " and ".$data['Z']." ".$data['Y'] : "")."<br />".$data['K'];

	if ($num_rows == 0)
	{
	 	$num_of_inserted_listing++;
	 	$rate = preg_replace("/[^a-zA-Z0-9\$\.\/\s]/", "", trim($data['C'].$data['D'].$data['E'].$data['F']));

	 	if (preg_match('/^\$([0-9\.]*)([^\$]*)/', $rate, $match))
	 	{
			$match[1] = number_format(floatval($match[1]) * 1.1, 2);
			
			$rate = "$".$match[1]." ".$match[2];
		}

		$sql = "INSERT INTO office_listing (Title, SpaceNumber, SpaceAvailable, RentalRate, StreetAddress, CityName, StateProvCode, PostalCode, LotSize, LastUpdate, SpaceAvailableMax, SpaceAvailableMin, PropertySubType, PropertyType, RentalRateMin, SpaceAvailableTotal, Description, Latitude, Longitude) VALUES (";
	
		$sql .= "'".mysqli_real_escape_string($link, trim($data['B']))."', ";
		$sql .= ($data['R'] == "Space 1" ? "NULL" : "'".mysqli_real_escape_string($link, trim($data['R']))."'").", ";
		$sql .= "'".$data['N']."', ";
		$sql .= "'".($rate ? $rate : "Negotiable")."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['G']))."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['H']))."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['I']))."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['J']))."', ";
		$sql .= "NULL, ";
		$sql .= "NULL, ";
		$sql .= "'".$data['N']."', ";
		$sql .= "'".$data['N']."', ";
		$sql .= "'".mysqli_real_escape_string($link, trim($data['Q']))."', ";
		$sql .= "'Office', ";
		$sql .= "'".($rate ? $rate : "Negotiable")."', ";
		$sql .= "'".$data['N']."', ";
		$sql .= "'".mysqli_real_escape_string($link, $desc[$i])."', ";
		$sql .= ($data['AB'] ? "'".$data['AB']."', " : "NULL, ");
		$sql .= ($data['AC'] ? "'".$data['AC']."', " : "NULL, ");
		
		$sql = substr($sql, 0, -2).")";

		$result = $link->query($sql);
echo $num_of_inserted_listing.". ".$sql."<br /><br />";		
		$last_insert_id = mysqli_insert_id($link);
		
		$listing_id = $last_insert_id."_".str_replace(" ", "_", $data['B']);
		$listing_id = str_replace("/", "_", $listing_id);

		if ($data['S'])
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $data['S']);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

			// Getting binary data
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

			$image = curl_exec($ch);

			if ($image)
			{
				if (preg_match("/HTTP\/1\.1 200 OK/", $image))
				{
					curl_setopt($ch, CURLOPT_URL, $data['S']);
				}
				else
				{
					$file_array = explode("\n\r", $image, 2);
			 		$header_array = explode("\n", $file_array[0]);

				 	foreach ($header_array as $header_value) 
					{
						$header_pieces = explode(':', $header_value, 2);
						$headers[$header_pieces[0]] = trim($header_pieces[1]);
					}
				
					curl_setopt($ch, CURLOPT_URL, $headers['Location']);
				}

				curl_setopt($ch, CURLOPT_HEADER, 0);
				
				$image = curl_exec($ch);
				
				if ($image)
				{
					$src = '../images/property_photos/'.$listing_id.".jpg";

					$f = fopen($src, 'w');
					fwrite($f, $image);
					fclose($f);
				}
			}

			curl_close($ch);
/*
			$j = 1;
		
			if ($data['T'])
			{
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $data['T']);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

				// Getting binary data
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		
				$image = curl_exec($ch);

				if ($image)
				{
			 		if (preg_match("/HTTP\/1\.1 200 OK/", $image))
					{
						curl_setopt($ch, CURLOPT_URL, $data['T']);
					}
					else
					{
						$file_array = explode("\n\r", $image, 2);
			 			$header_array = explode("\n", $file_array[0]);

					 	foreach ($header_array as $header_value) 
						{
							$header_pieces = explode(':', $header_value, 2);
							$headers[$header_pieces[0]] = trim($header_pieces[1]);
						}
				
						curl_setopt($ch, CURLOPT_URL, $headers['Location']);
					}
				
					curl_setopt($ch, CURLOPT_HEADER, 0);
				
					$image = curl_exec($ch);
				
					if ($image)
					{
						$src = '../images/property_photos/'.$listing_id."-".$j.".jpg";

						$f = fopen($src, 'w');
						fwrite($f, $image);
						fclose($f);

						$j++;
					}
				}
			
				curl_close($ch);
			}

			if ($data['U'])
			{
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $data['U']);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

				// Getting binary data
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		
				$image = curl_exec($ch);

				if ($image)
				{
			 		if (preg_match("/HTTP\/1\.1 200 OK/", $image))
					{
						curl_setopt($ch, CURLOPT_URL, $data['U']);
					}
					else
					{
						$file_array = explode("\n\r", $image, 2);
			 			$header_array = explode("\n", $file_array[0]);

					 	foreach ($header_array as $header_value) 
						{
							$header_pieces = explode(':', $header_value, 2);
							$headers[$header_pieces[0]] = trim($header_pieces[1]);
						}
				
						curl_setopt($ch, CURLOPT_URL, $headers['Location']);
					}

					curl_setopt($ch, CURLOPT_HEADER, 0);
				
					$image = curl_exec($ch);
				
					if ($image)
					{
						$src = '../images/property_photos/'.$listing_id."-".$j.".jpg";

						$f = fopen($src, 'w');
						fwrite($f, $image);
						fclose($f);

						$j++;
					}
				}
			
				curl_close($ch);
			}

			if ($data['V'])
			{
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $data['V']);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

				// Getting binary data
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		
				$image = curl_exec($ch);

				if ($image)
				{
			 		if (preg_match("/HTTP\/1\.1 200 OK/", $image))
					{
						curl_setopt($ch, CURLOPT_URL, $data['V']);
					}
					else
					{
						$file_array = explode("\n\r", $image, 2);
			 			$header_array = explode("\n", $file_array[0]);

					 	foreach ($header_array as $header_value) 
						{
							$header_pieces = explode(':', $header_value, 2);
							$headers[$header_pieces[0]] = trim($header_pieces[1]);
						}
				
						curl_setopt($ch, CURLOPT_URL, $headers['Location']);
					}

					curl_setopt($ch, CURLOPT_HEADER, 0);
				
					$image = curl_exec($ch);
				
					if ($image)
					{
						$src = '../images/property_photos/'.$listing_id."-".$j.".jpg";

						$f = fopen($src, 'w');
						fwrite($f, $image);
						fclose($f);

						$j++;
					}
				}
			
				curl_close($ch);
			}
*/
		}

		$sql = "UPDATE office_listing SET ";

		if (file_exists('../images/property_photos/'.$listing_id.".jpg")) $sql .= "PhotoURL = 'http://leaseofficespace.net/images/property_photos/".$listing_id.".jpg', ";
		else $sql .= "PhotoURL = NULL, ";
/*
		for ($j = 1; $j < 5; $j++)
		{
			if (file_exists('../images/property_photos/'.$listing_id."-".$j.".jpg")) $sql .= "PhotoURL".($j + 1)." = 'http://leaseofficespace.net/images/property_photos/".$listing_id."-".$j.".jpg', ";
		}
*/		
		$sql = substr($sql, 0, -2)." WHERE ListingID = ".$last_insert_id;
		
		$result = $link->query($sql);
echo $sql."<br /><br />";
	}
}

$link->close();
?>