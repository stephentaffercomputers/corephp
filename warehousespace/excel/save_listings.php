<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$link = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel_IOFactory */
require_once 'Classes/PHPExcel/IOFactory.php';

if (!file_exists("warehouses.xls")) {
	exit("Please copy listing_data_test.xls here first.\n");
}

$amenities_to_watch = array("\s?[\d']+\sclearance", "high clearance", "skylights", "yard", "secured yard", "fenced yard", "\s?\d*\soffices", "\s?\d*\sprivate offices", "free standing building", "freestanding building", "standalone building", "light industrial zoning", "freeway access", "highway access", "foil insulation", "automotive uses permitted", "\s\d*\s*dock high door", "\s\d*\s*ground level door", "480v", "3 phase power", "three phase power", "ample parking", "fire sprinklers", "ESFR sprinkler system", "central air", "air conditioned", "120v", "208v", "\s?[\d,]+\svolts?", "business park", "solar", "showroom", "truck parking", "freeway frontage", "ground level loading", "exterior platform", "\s?[\d,]+\samps?", "fully secured", "no CAM fees", "street view exposure", "railway access", "street frontage", "energy efficient lighting", "built in 2001", "built in 2002", "built in 2003", "built in 2004", "built in 2005", "built in 2006", "built in 2007", "built in 2008", "built in 2009", "built in 2010", "built in 2011", "built in 2012", "built in 2013", "built in 2014", "rail access", "heavy industrial zoning", "fully sprinklered", "flex space", "secure area", "clear span", "drive-in loading", "\s?[\d']+\sceilings?", "high ceilings?", "railroad access", "^\d\d+\sfoot clear height", "[^\d'][\d']+\sclear height", "grade level loading", "active rail", "air conditioned production area", "production area", "zoned MP", "zoned M-1", "concrete exterior walls", "professional landscaping", "attractive landspace", "truck staging area", "recently rehabbed", "recently renovated", "compressed air throughout", "suspended power outlets", "zoned C-2", "storefront", "concrete tilt up construction", "food grade", "zoned I-2", "zoned I-1", "temperature controlled warehouse", "humidity controlled warehouse", "temperature and humidity controlled warehouse");

$objPHPExcel = PHPExcel_IOFactory::load("warehouses.xls");

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$desc = array();

for ($i = 2; $i <= count($sheetData); $i++)
{
 	$counter = $i - 1;

	$data = $sheetData[$i];
	
	$sql = "INSERT INTO warehouse_listing_newest (Title, SpaceNumber, SpaceAvailable, RentalRate, StreetAddress, CityName, StateProvCode, PostalCode, LotSize, LastUpdate, SpaceAvailableMax, SpaceAvailableMin, PropertySubType, PropertyType, RentalRateMin, SpaceAvailableTotal, Description, Latitude, Longitude, PhotoURL, PhotoURL2, PhotoURL3, PhotoURL4, PhotoURL5) VALUES (";
	
	$sql .= "'".mysqli_real_escape_string($link, trim($data['B']))."', ";
	$sql .= ($data['S'] == "Space 1" ? "NULL" : "'".mysqli_real_escape_string($link, trim($data['S']))."'").", ";
	$sql .= "'".$data['O']."', ";
	$sql .= "'".preg_replace("/[^a-zA-Z0-9\$\.\/\s]/", "", trim($data['C'].$data['D'].$data['E'].$data['F']))."', ";
	$sql .= "'".mysqli_real_escape_string($link, trim($data['G']))."', ";
	$sql .= "'".mysqli_real_escape_string($link, trim($data['H']))."', ";
	$sql .= "'".mysqli_real_escape_string($link, trim($data['I']))."', ";
	$sql .= "'".mysqli_real_escape_string($link, trim($data['J']))."', ";
	$sql .= "NULL, ";
	$sql .= "NULL, ";
	$sql .= "'".$data['O']."', ";
	$sql .= "'".$data['O']."', ";
	$sql .= "'".mysqli_real_escape_string($link, trim($data['R']))."', ";
	$sql .= "'Industrial', ";
	$sql .= "'".preg_replace("/[^a-zA-Z0-9\$\.\/\s]/", "", trim($data['C'].$data['D'].$data['E'].$data['F']))."', ";
	$sql .= "'".$data['O']."', ";

	$desc[$i] = $data['B'].($data['B'] == $data['G'] ? "" : " located at ".$data['G']);
	
	$provides = rand(1, 3);
	$desc[$i] .= ($provides == 1 ? " offers" : ($provides == 2 ? " provides" : " gives"));
	
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " tenants" : ($x == 2 ? " businesses" : " companies"));

	$desc[$i] .= ($provides == 2 ? " width" : "")." up to ".$data['O']." for ".(rand(1, 2) == 1 ? "rent" : "lease")." in ".$data['H'].". This";

	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " listing" : ($x == 2 ? " available warehouse" : " property"))." is";
	
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? " perfect" : ($x == 2 ? " ideal" : " great"))." for";

	$size = intval(str_replace(",", "", $data['O']));

	$x = rand(1, 3);
	if ($size > 0 && $size < 10000) $desc[$i] .= (rand(1, 2) == 1 ? " light manufacturing or storage" : " light manufacturing");
	if ($size >= 10000 && $size < 50000) $desc[$i] .= (rand(1, 2) == 1 ? " growing companies" : " large to medium size businesses");
	if ($size >= 50000) $desc[$i] .= (rand(1, 2) == 1 ? " large distribution hubs and major manufacturing operations" : " regional/national distribution hubs and major manufacturing operations");
	
	$matches = array();
	
	foreach ($amenities_to_watch AS $value)
	{
		if (!preg_match("/".$value."/i", $data['M'], $match)) continue;
		
		if ($value == "recently rehabbed") $matches[] = "recently renovated";
		
		if ($value == "yard" && !preg_match("/secured yard/i", $data['M']) && !preg_match("/fenced yard/i", $data['M'])) $matches[] = "yard";
		elseif ($value != "yard") 
		{
			if ($value == "production area" && !preg_match("/air conditioned production area/i", $data['M'])) $matches[] = "production area";
			elseif ($value != "production area") $matches[] = trim($match[0]);
		}
	}
	
	if ($matches) {
		$desc[$i] .= " and ".(rand(1, 2) == 1 ? "includes" : "offers")." the following amenities:<br /><ul>";
		
		foreach ($matches AS $value) $desc[$i] .= "<li>".ucfirst($value)."</li>";
		
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
	
	$desc[$i] .= " in ".$data['H'].", ".(rand(1, 2) == 1 ? "request" : "contact")." ".(rand(1, 2) == 1 ? "a" : "your")." <strong>".(rand(1, 2) == 1 ? "free" : "complimentary")."</strong> property report today by ".(rand(1, 2) == 1 ? "filling out" : "completing")." our quick contact form. If you would like to ".(rand(1, 2) == 1 ? "view" : "browse")." additional ";
	
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? "listings" : ($x == 2 ? "spaces" : "warehouses"));
	
	$desc[$i] .= " visit our <a href=\"http://www.warehousespaces.com/warehouse-for-rent/United-States/".$data['I']."/".$data['H']."/\">".(rand(1, 2) == 1 ? "warehouse space in ".$data['H'] : $data['H']." warehouse space")."</a> page to see the ";
	
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? "full" : ($x == 2 ? "complete" : "current"))." market inventory.<br /><br />";
	
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
	
	$desc[$i] .= "<br /><br /><em>Listing Provided by</em> ".$data['AB']." ".$data['AA'].($data['AD'] ? " and ".$data['AD']." ".$data['AC'] : "")."<br />".$data['K'];

	//$desc[$i] .= "<br /><br /><b>Optional Title: </b>".($data['B'] == $data['G'] ? "" : $data['B'])."<br /><br /><b>Custom text instead of price: </b>".$data['C'].$data['D'].$data['E']."<br /><br /><b>Area: </b>".$size."<br /><br /><b>Latitude: </b>".$data['AK']."<br /><b>Longitude: </b>".$data['AL'];

	//$desc[$i] .= "<br /><hr><br />";

	$sql .= "'".mysqli_real_escape_string($link, $desc[$i])."', ";
	$sql .= ($data['AK'] ? "'".$data['AK']."', " : "NULL, ");
	$sql .= ($data['AL'] ? "'".$data['AL']."', " : "NULL, ");

	$listing_id = $counter."_".str_replace(" ", "_", $data['B']);
	$listing_id = str_replace("/", "_", $listing_id);

	if (file_exists('../images/property_photos/'.$listing_id.".jpg")) $sql .= "'http://www.warehousespaces.com/images/property_photos/".$listing_id.".jpg', ";
	else $sql .= "NULL, ";
	
	for ($j = 1; $j < 5; $j++)
	{
		if (file_exists('../images/property_photos/'.$listing_id."-".$j.".jpg")) $sql .= "'http://www.warehousespaces.com/images/property_photos/".$listing_id."-".$j.".jpg', ";
	else $sql .= "NULL, ";
	}

	$sql = substr($sql, 0, -2).")";
	
	$result = $link->query($sql);
}
?>