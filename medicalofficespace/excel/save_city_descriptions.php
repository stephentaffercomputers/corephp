<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$link = mysqli_connect("localhost", "medicalo_db", "7CuDaKPPeXgpKT46", "medicalo_db");

$sql = "UPDATE city_descriptions SET CityDesc = NULL";

try{
	$result = $link->query($sql);
}
catch(exception $e) { var_dump($e); }

$sql_list_cities = "select distinct CityName, StateProvCode, count(*) AS num, min(SpaceAvailable) AS minavail, max(SpaceAvailable) AS maxavail from medical_listing where trim(CityName) <> '' AND ListingIsActive = 'y' group by CityName, StateProvCode order by CityName";

try{
	$result = $link->query($sql_list_cities);
}
catch(exception $e) { var_dump($e); }

while ($row = mysqli_fetch_array($result)) 
{
 	$min_rate = 0;
 	$min_sqft = 0;
 	$max_sqft = 0;

	$sql = "select SpaceAvailable, RentalRateMin from medical_listing where CityName = '".$row['CityName']."' AND StateProvCode = '".$row['StateProvCode']."' AND ListingIsActive = 'y'";
	
	try{
		$listing_result = $link->query($sql);
	}
	catch(exception $e) { var_dump($e); }
	
	while ($listing_row = mysqli_fetch_array($listing_result)) 
	{
	 	$int_sqft = intval(preg_replace("/[^0-9\.]/", "", $listing_row['SpaceAvailable']));
	 	
		if ($listing_row['RentalRateMin'] == "Negotiable") $int_rate = 0;
		else
		{
		 	if (strpos($listing_row['RentalRateMin'], 'Year') !== false)
			{
				if (strpos($listing_row['RentalRateMin'], '/SF/Year')) $int_rate = floatval(preg_replace("/[^0-9\.]/", "", $listing_row['RentalRateMin']) / 12);
				else $int_rate = floatval(preg_replace("/[^0-9\.]/", "", $listing_row['RentalRateMin'])/12) / floatval(preg_replace("/[^-0-9\.]/", "", $listing_row['SpaceAvailable']));
			}
			elseif (strpos($listing_row['RentalRateMin'], 'SF') !== false) 
				$int_rate = floatval(preg_replace("/[^0-9\.]/", "", $listing_row['RentalRateMin']));
			else
				$int_rate = floatval(preg_replace("/[^0-9\.]/","",$listing_row['RentalRateMin'])) / floatval(preg_replace("/[^-0-9\.]/", "", $listing_row['SpaceAvailable']));
		} 

	 	if (!$min_sqft || $int_sqft < $min_sqft) $min_sqft = $int_sqft;
	 	if (!$max_sqft || $int_sqft > $max_sqft) $max_sqft = $int_sqft;
	 	
	 	if ((!$min_rate || $int_rate < $min_rate) && $int_rate > 0) $min_rate = $int_rate;
	}
	
	$sql = "SELECT * FROM city_descriptions WHERE CityName = '".$row['CityName']."' AND StateProvCode = '".$row['StateProvCode']."'";
	
	$result_city = $link->query($sql);
	
	$num_rows = $result_city->num_rows;

	if ($num_rows == 0)
	{
		$sql = "INSERT INTO city_descriptions (CityName, StateProvCode, CityDesc) VALUES (";
		$sql .= "'".$row['CityName']."', ";
		$sql .= "'".$row['StateProvCode']."', ";
	
		$sql .= "'<p>".$row['CityName']." currently features ".$row['num']." medical ".(rand(1, 2) == 1 ? "listings" : "offices")." for ".(rand(1, 2) == 1 ? "rent" : "lease")." with with ".($min_rate ? (rand(1, 2) == 1 ? "asking rates" : "asking rents")." from ".number_format($min_rate, 2)." per sq ft and " : "")."sizes ranging from ".number_format($min_sqft)." sqft to ".number_format($max_sqft)." sqft. ".(rand(1, 2) == 1 ? "Browse" : "View")." each ".(rand(1, 2) == 1 ? "listing" : "property")." in ".$row['CityName']." and let ";
	
		$x = rand(1, 3);
		$sql .= ($x == 1 ? "us" : ($x == 2 ? "our agents" : "our medical leasing specialists"));
	
		$sql .= " know which ";
	
		$x = rand(1, 3);
		$sql .= ($x == 1 ? "properties" : ($x == 2 ? "buildings" : "spaces"));
	
		$sql .= " you would like to review ".(rand(1, 2) == 1 ? "in more detail" : "in-depth").". Our ".(rand(1, 2) == 1 ? "local" : "in-market")." ";
	
		$x = rand(1, 3);
		$sql .= ($x == 1 ? "agents" : ($x == 2 ? "tenant representatives" : "commercial real estate specialists"));
	
		$sql .= " will prepare a detailed report on the property as well as other ".(rand(1, 2) == 1 ? "listings" : "available medical listings")." in the market that meet your ".(rand(1, 2) == 1 ? "practice" : "operational")." ".(rand(1, 2) == 1 ? "needs" : "requirements").". ".(rand(1, 2) == 1 ? "We" : "Our team")." can then ".(rand(1, 2) == 1 ? "schedule" : "arrange")." ".(rand(1, 2) == 1 ? "property" : "building")." tours and once a site is selected and negotiate a competitive ".(rand(1, 2) == 1 ? "commercial lease" : "lease")." agreement on your behalf.</p>".(rand(1, 2) == 1 ? "Find" : "Locate")." medical space ".(rand(1, 2) == 1 ? "built" : "tailored")." for:<br /><br /><ul><li>Dental / Orthodontic practices</li><li>Family ".(rand(1, 2) == 1 ? "practice" : "medicine")."</li><li>In-hospital / ".(rand(1, 2) == 1 ? "connected" : "adjacent")." space</li><li>".(rand(1, 2) == 1 ? "Single" : "One")." physician / group practices</li><li>Physical ".(rand(1, 2) == 1 ? "therapy" : "therapy and rehabilitation")." centers</li><li>Psychotherapy</li><li>Radiology / MRI</li><li>".(rand(1, 2) == 1 ? "Outpatient Surgery Centers" : "Surgery centers")."</li><li>".(rand(1, 2) == 1 ? "Urgent care" : "Walk-in")." clinics</li></ul><p>Our ";

		$x = rand(1, 3);
		$sql .= ($x == 1 ? "nationwide" : ($x == 2 ? "US" : "national"));
	
		$sql .= " ".(rand(1, 2) == 1 ? "commercial" : "commercial real estate")." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to ".(rand(1, 2) == 1 ? "you" : "clients").". Contact ".(rand(1, 2) == 1 ? "us" : "our team")." ".(rand(1, 2) == 1 ? "today" : "now")." and we look forward to finding ".(rand(1, 2) == 1 ? "the right space for your practice" : "your next space").".')";

		$result_insert = $link->query($sql);
		echo $sql."<br /><br />";		
	}
	else 
	{
		$sql = "UPDATE city_descriptions SET CityDesc = ";
		
		$sql .= "'<p>".$row['CityName']." currently features ".$row['num']." medical ".(rand(1, 2) == 1 ? "listings" : "offices")." for ".(rand(1, 2) == 1 ? "rent" : "lease")." with with ".($min_rate ? (rand(1, 2) == 1 ? "asking rates" : "asking rents")." from ".number_format($min_rate, 2)." per sq ft and " : "")."sizes ranging from ".number_format($min_sqft)." sqft to ".number_format($max_sqft)." sqft. ".(rand(1, 2) == 1 ? "Browse" : "View")." each ".(rand(1, 2) == 1 ? "listing" : "property")." in ".$row['CityName']." and let ";
	
		$x = rand(1, 3);
		$sql .= ($x == 1 ? "us" : ($x == 2 ? "our agents" : "our medical leasing specialists"));
	
		$sql .= " know which ";
	
		$x = rand(1, 3);
		$sql .= ($x == 1 ? "properties" : ($x == 2 ? "buildings" : "spaces"));
	
		$sql .= " you would like to review ".(rand(1, 2) == 1 ? "in more detail" : "in-depth").". Our ".(rand(1, 2) == 1 ? "local" : "in-market")." ";
	
		$x = rand(1, 3);
		$sql .= ($x == 1 ? "agents" : ($x == 2 ? "tenant representatives" : "commercial real estate specialists"));
	
		$sql .= " will prepare a detailed report on the property as well as other ".(rand(1, 2) == 1 ? "listings" : "available medical listings")." in the market that meet your ".(rand(1, 2) == 1 ? "practice" : "operational")." ".(rand(1, 2) == 1 ? "needs" : "requirements").". ".(rand(1, 2) == 1 ? "We" : "Our team")." can then ".(rand(1, 2) == 1 ? "schedule" : "arrange")." ".(rand(1, 2) == 1 ? "property" : "building")." tours and once a site is selected and negotiate a competitive ".(rand(1, 2) == 1 ? "commercial lease" : "lease")." agreement on your behalf.</p>".(rand(1, 2) == 1 ? "Find" : "Locate")." medical space ".(rand(1, 2) == 1 ? "built" : "tailored")." for:<br /><br /><ul><li>Dental / Orthodontic practices</li><li>Family ".(rand(1, 2) == 1 ? "practice" : "medicine")."</li><li>In-hospital / ".(rand(1, 2) == 1 ? "connected" : "adjacent")." space</li><li>".(rand(1, 2) == 1 ? "Single" : "One")." physician / group practices</li><li>Physical ".(rand(1, 2) == 1 ? "therapy" : "therapy and rehabilitation")." centers</li><li>Psychotherapy</li><li>Radiology / MRI</li><li>".(rand(1, 2) == 1 ? "Outpatient Surgery Centers" : "Surgery centers")."</li><li>".(rand(1, 2) == 1 ? "Urgent care" : "Walk-in")." clinics</li></ul><p>Our ";

		$x = rand(1, 3);
		$sql .= ($x == 1 ? "nationwide" : ($x == 2 ? "US" : "national"));
	
		$sql .= " ".(rand(1, 2) == 1 ? "commercial" : "commercial real estate")." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to ".(rand(1, 2) == 1 ? "you" : "clients").". Contact ".(rand(1, 2) == 1 ? "us" : "our team")." ".(rand(1, 2) == 1 ? "today" : "now")." and we look forward to finding ".(rand(1, 2) == 1 ? "the right space for your practice" : "your next space").".'";
		
		$sql .= " WHERE CityName = '".$row['CityName']."' AND StateProvCode = '".$row['StateProvCode']."'";

		$result_insert = $link->query($sql);
		echo $sql."<br /><br />";
	}
}

$link->close();
?>