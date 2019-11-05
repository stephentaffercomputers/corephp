<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$link = mysqli_connect("localhost", "leaseoff_db", "BTrsHGcg21vnwUpt", "leaseoff_db");

$sql = "UPDATE city_descriptions SET CityDesc = NULL";

try{
	$result = $link->query($sql);
}
catch(exception $e) { var_dump($e); }

$sql_list_cities = "select distinct CityName, StateProvCode, count(*) AS num, min(SpaceAvailable) AS minavail, max(SpaceAvailable) AS maxavail from office_listing where trim(CityName) <> '' AND ListingIsActive = 'y' group by CityName order by CityName;";

try{
	$result = $link->query($sql_list_cities);
}
catch(exception $e) { var_dump($e); }

while ($row = mysqli_fetch_array($result)) 
{
 	$min_rate = 0;
 	$min_sqft = 0;
 	$max_sqft = 0;

	$sql = "select SpaceAvailable, RentalRateMin from office_listing where CityName = '".$row['CityName']."' AND ListingIsActive = 'y'";
	
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
	
		if ($row['num'] > 1)
		{
	 		$x = rand(1, 3);

			$sql .= "'<p>".$row['CityName']." currently features ".$row['num']." office ".(rand(1, 2) == 1 ? "listings" : "spaces")." for rent with asking ".($min_rate ? (rand(1, 2) == 1 ? "rates " : "rents")." from ".number_format($min_rate, 2)." per sq ft and " : "")."sizes ranging from ".number_format($min_sqft)." sqft to ".number_format($max_sqft)." sqft. ".(rand(1, 2) == 1 ? "Browse" : "View")." each listing in ".$row['CityName']." and let ".(rand(1, 2) == 1 ? "us" : "our agents")." know which ".(rand(1, 2) == 1 ? "properties" : "buildings")." you would like to review ".(rand(1, 2) == 1 ? "in more detail" : "in-depth").".Our local ".(rand(1, 2) == 1 ? "agent" : "tenant representative")." will prepare a detailed report on the property as well as other ".(rand(1, 2) == 1 ? "available " : "")."offices in the market that meet your ".(rand(1, 2) == 1 ? "business" : "company").(rand(1, 2) == 1 ? " requirements" : " needs").". ".(rand(1, 2) == 1 ? "We " : "Our agent")." can then schedule ".(rand(1, 2) == 1 ? "property " : "building ")."tours and once a site is selected, negotiate a competitive lease or purchase agreement on your behalf.</p><p>Find office space that meets your needs:</p><ul><li>BioTech / ".(rand(1, 2) == 1 ? "Lab" : "Laboratory")." Space</li><li>Call Center</li><li>Creative Space</li><li>Data Center / High Tech</li><li>High Tech / ".(rand(1, 2) == 1 ? "R&D" : "Research and Development")."</li><li>".(rand(1, 2) == 1 ? "Law Firm" : "Legal Space")."</li><li>".(rand(1, 2) == 1 ? "Medical/Dental Space" : "Medical Space")."</li><li>".(rand(1, 2) == 1 ? "Media Production" : "Production / Studio")."</li></ul><p>Our".($x == 1 ? " office search" : ($x == 2 ? " office locating" : " commercial real estate"))." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to ".(rand(1, 2) == 1 ? "you" : "clients").". Contact our ".$row['CityName']." office space specialists today and we look forward to finding ".(rand(1, 2) == 1 ? "the right space for you" : "your next space").".</p>')";
		}
		else
		{
		 	$x = rand(1, 3);

			$sql .= "'<p>Current inventory for ".$row['CityName']." is one listing but given the nature of the commercial real estate market, more listings may be available or could come onto the market soon. Contact our office specialists for more information and we can look at opportunities outside of the market or alert you when new listings that meet your needs become available.</p><p>Find office  space that meets your needs:</p><ul><li>BioTech / ".(rand(1, 2) == 1 ? "Lab" : "Laboratory")." Space</li><li>Call Center</li><li>Creative Space</li><li>Data Center / High Tech</li><li>High Tech / ".(rand(1, 2) == 1 ? "R&D" : "Research and Development")."</li><li>".(rand(1, 2) == 1 ? "Law Firm" : "Legal Space")."</li><li>".(rand(1, 2) == 1 ? "Medical/Dental Space" : "Medical Space")."</li><li>".(rand(1, 2) == 1 ? "Media Production" : "Production / Studio")."</li></ul><p>Our".($x == 1 ? " office search" : ($x == 2 ? " office locating" : " commercial real estate"))." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to ".(rand(1, 2) == 1 ? "you" : "clients").". Contact our ".$row['CityName']." office space specialists today and we look forward to finding ".(rand(1, 2) == 1 ? "the right space for you" : "your next space").".</p>')";	
		}	

		$result_insert = $link->query($sql);
		echo $sql."<br /><br />";
	}
	else
	{
		$sql = "UPDATE city_descriptions SET CityDesc = ";

		if ($row['num'] > 1)
		{
	 		$x = rand(1, 3);

			$sql .= "'<p>".$row['CityName']." currently features ".$row['num']." office ".(rand(1, 2) == 1 ? "listings" : "spaces")." for rent with asking ".($min_rate ? (rand(1, 2) == 1 ? "rates " : "rents")." from ".number_format($min_rate, 2)." per sq ft and " : "")."sizes ranging from ".number_format($min_sqft)." sqft to ".number_format($max_sqft)." sqft. ".(rand(1, 2) == 1 ? "Browse" : "View")." each listing in ".$row['CityName']." and let ".(rand(1, 2) == 1 ? "us" : "our agents")." know which ".(rand(1, 2) == 1 ? "properties" : "buildings")." you would like to review ".(rand(1, 2) == 1 ? "in more detail" : "in-depth").".Our local ".(rand(1, 2) == 1 ? "agent" : "tenant representative")." will prepare a detailed report on the property as well as other ".(rand(1, 2) == 1 ? "available " : "")."offices in the market that meet your ".(rand(1, 2) == 1 ? "business" : "company").(rand(1, 2) == 1 ? " requirements" : " needs").". ".(rand(1, 2) == 1 ? "We " : "Our agent")." can then schedule ".(rand(1, 2) == 1 ? "property " : "building ")."tours and once a site is selected, negotiate a competitive lease or purchase agreement on your behalf.</p><p>Find office space that meets your needs:</p><ul><li>BioTech / ".(rand(1, 2) == 1 ? "Lab" : "Laboratory")." Space</li><li>Call Center</li><li>Creative Space</li><li>Data Center / High Tech</li><li>High Tech / ".(rand(1, 2) == 1 ? "R&D" : "Research and Development")."</li><li>".(rand(1, 2) == 1 ? "Law Firm" : "Legal Space")."</li><li>".(rand(1, 2) == 1 ? "Medical/Dental Space" : "Medical Space")."</li><li>".(rand(1, 2) == 1 ? "Media Production" : "Production / Studio")."</li></ul><p>Our".($x == 1 ? " office search" : ($x == 2 ? " office locating" : " commercial real estate"))." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to ".(rand(1, 2) == 1 ? "you" : "clients").". Contact our ".$row['CityName']." office space specialists today and we look forward to finding ".(rand(1, 2) == 1 ? "the right space for you" : "your next space").".</p>'";
		}
		else
		{
		 	$x = rand(1, 3);

			$sql .= "'<p>Current inventory for ".$row['CityName']." is one listing but given the nature of the commercial real estate market, more listings may be available or could come onto the market soon. Contact our office specialists for more information and we can look at opportunities outside of the market or alert you when new listings that meet your needs become available.</p><p>Find office  space that meets your needs:</p><ul><li>BioTech / ".(rand(1, 2) == 1 ? "Lab" : "Laboratory")." Space</li><li>Call Center</li><li>Creative Space</li><li>Data Center / High Tech</li><li>High Tech / ".(rand(1, 2) == 1 ? "R&D" : "Research and Development")."</li><li>".(rand(1, 2) == 1 ? "Law Firm" : "Legal Space")."</li><li>".(rand(1, 2) == 1 ? "Medical/Dental Space" : "Medical Space")."</li><li>".(rand(1, 2) == 1 ? "Media Production" : "Production / Studio")."</li></ul><p>Our".($x == 1 ? " office search" : ($x == 2 ? " office locating" : " commercial real estate"))." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to ".(rand(1, 2) == 1 ? "you" : "clients").". Contact our ".$row['CityName']." office space specialists today and we look forward to finding ".(rand(1, 2) == 1 ? "the right space for you" : "your next space").".</p>'";	
		}

		$sql .= " WHERE CityName = '".$row['CityName']."' AND StateProvCode = '".$row['StateProvCode']."'";

		$result_insert = $link->query($sql);
		echo $sql."<br /><br />";
	}
}

$link->close();
?>