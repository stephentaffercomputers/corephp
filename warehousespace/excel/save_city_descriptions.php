<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$link = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

$sql = "UPDATE city_descriptions SET CityDesc = NULL";

try{
	$result = $link->query($sql);
}
catch(exception $e) { var_dump($e); }

$sql_list_cities = "select distinct CityName, StateProvCode, count(*) AS num, min(SpaceAvailable) AS minavail, max(SpaceAvailable) AS maxavail from warehouse_listing where trim(CityName) <> '' AND ListingIsActive = 'y' group by CityName, StateProvCode order by CityName";

try{
	$result = $link->query($sql_list_cities);
}
catch(exception $e) { var_dump($e); }

while ($row = mysqli_fetch_array($result)) 
{
 	$min_rate = 0;
 	$min_sqft = 0;
 	$max_sqft = 0;

	$sql = "select SpaceAvailable, RentalRateMin from warehouse_listing where CityName = '".$row['CityName']."' AND StateProvCode = '".$row['StateProvCode']."' AND ListingIsActive = 'y'";
	
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
			$sql .= "'<p>".$row['CityName']." currently features ".$row['num']." warehouses for ".(rand(1, 2) == 1 ? "rent" : "lease")." with ".($min_rate ? (rand(1, 2) == 1 ? "asking " : "")."rates from ".number_format($min_rate, 2)." per sq ft and " : "")."sizes ranging from ".number_format($min_sqft)." sqft to ".number_format($max_sqft)." sqft. Browse each ".(rand(1, 2) == 1 ? "available" : "")." listing in ".$row['CityName']." and let ".(rand(1, 2) == 1 ? "us" : "our agents")." know which ".(rand(1, 2) == 1 ? "properties" : "buildings")." you would like to ".(rand(1, 2) == 1 ? "learn more about" : "review in more detail").". ".(rand(1, 2) == 1 ? "We\'ll" : "Our local agent will")." ";
		
			$x = rand(1, 3);
			$sql .= ($x == 1 ? "prepare" : ($x == 2 ? "create" : "send you"));
		
			$sql .= " a detailed report on the ".(rand(1, 2) == 1 ? "property" : "building")." ".(rand(1, 2) == 1 ? "as well as" : "and include")." other warehouses in the market that meet your ".(rand(1, 2) == 1 ? "business" : "")." needs. ".(rand(1, 2) == 1 ? "We can" : "We\'ll")." then schedule ".(rand(1, 2) == 1 ? "property tours" : "tours of each property")." and once ".(rand(1, 2) == 1 ? "a site is selected" : "you select a site").", negotiate a competitive ".(rand(1, 2) == 1 ? "commercial" : "")." lease or sale agreement on your behalf.</p><p>Find industrial space that meets your needs:</p><ul><li>Distribution</li><li>Flex".(rand(1, 2) == 1 ? " space" : "")."</li><li>Manufacturing</li><li>";
		
			$x = rand(1, 3);
			$sql .= ($x == 1 ? "Office / R&D" : ($x == 2 ? "R&D" : "Research and Development"));
		
			$sql .= "</li><li>Refrigerated".(rand(1, 2) == 1 ? " / Cold Storage" : "")."</li><li>".(rand(1, 2) == 1 ? "Showroom" : "Street Retail")."</li><li>Storage</li><li>".(rand(1, 2) == 1 ? "Truck Terminal" : "Truck Hub")."</li><li>Warehouse</li><li>Yard</li></ul><p>Our warehouse ".(rand(1, 2) == 1 ? "locating" : "search")." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to you. Contact us today and we look forward to ".(rand(1, 2) == 1 ? "working with you" : "finding the right space for you").".</p>')";
		}
		else
		{
			$sql .= "'<p>Current inventory for ".$row['CityName']." is one listing but given the nature of the commercial real estate market, more listings may be available or could come onto the market soon. Contact our warehouse specialists for more information and we can look at opportunities outside of the market or alert you when new listings that meet your needs become available.</p><p>Find industrial space that meets your needs:</p><ul><li>Distribution</li><li>Flex".(rand(1, 2) == 1 ? " space" : "")."</li><li>Manufacturing</li><li>";
		
			$x = rand(1, 3);
			$sql .= ($x == 1 ? "Office / R&D" : ($x == 2 ? "R&D" : "Research and Development"));
		
			$sql .= "</li><li>Refrigerated".(rand(1, 2) == 1 ? " / Cold Storage" : "")."</li><li>".(rand(1, 2) == 1 ? "Showroom" : "Street Retail")."</li><li>Storage</li><li>".(rand(1, 2) == 1 ? "Truck Terminal" : "Truck Hub")."</li><li>Warehouse</li><li>Yard</li></ul><p>Our warehouse ".(rand(1, 2) == 1 ? "locating" : "search")." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to you. Contact us today and we look forward to ".(rand(1, 2) == 1 ? "working with you" : "finding the right space for you").".</p>')";
		
		}	

		$result_insert = $link->query($sql);
		echo $sql."<br /><br />";
	}
	else
	{
		$sql = "UPDATE city_descriptions SET CityDesc = ";
	
		if ($row['num'] > 1)
		{
			$sql .= "'<p>".$row['CityName']." currently features ".$row['num']." warehouses for ".(rand(1, 2) == 1 ? "rent" : "lease")." with ".($min_rate ? (rand(1, 2) == 1 ? "asking " : "")."rates from ".number_format($min_rate, 2)." per sq ft and " : "")."sizes ranging from ".number_format($min_sqft)." sqft to ".number_format($max_sqft)." sqft. Browse each ".(rand(1, 2) == 1 ? "available" : "")." listing in ".$row['CityName']." and let ".(rand(1, 2) == 1 ? "us" : "our agents")." know which ".(rand(1, 2) == 1 ? "properties" : "buildings")." you would like to ".(rand(1, 2) == 1 ? "learn more about" : "review in more detail").". ".(rand(1, 2) == 1 ? "We\'ll" : "Our local agent will")." ";
		
			$x = rand(1, 3);
			$sql .= ($x == 1 ? "prepare" : ($x == 2 ? "create" : "send you"));
		
			$sql .= " a detailed report on the ".(rand(1, 2) == 1 ? "property" : "building")." ".(rand(1, 2) == 1 ? "as well as" : "and include")." other warehouses in the market that meet your ".(rand(1, 2) == 1 ? "business" : "")." needs. ".(rand(1, 2) == 1 ? "We can" : "We\'ll")." then schedule ".(rand(1, 2) == 1 ? "property tours" : "tours of each property")." and once ".(rand(1, 2) == 1 ? "a site is selected" : "you select a site").", negotiate a competitive ".(rand(1, 2) == 1 ? "commercial" : "")." lease or sale agreement on your behalf.</p><p>Find industrial space that meets your needs:</p><ul><li>Distribution</li><li>Flex".(rand(1, 2) == 1 ? " space" : "")."</li><li>Manufacturing</li><li>";
		
			$x = rand(1, 3);
			$sql .= ($x == 1 ? "Office / R&D" : ($x == 2 ? "R&D" : "Research and Development"));
		
			$sql .= "</li><li>Refrigerated".(rand(1, 2) == 1 ? " / Cold Storage" : "")."</li><li>".(rand(1, 2) == 1 ? "Showroom" : "Street Retail")."</li><li>Storage</li><li>".(rand(1, 2) == 1 ? "Truck Terminal" : "Truck Hub")."</li><li>Warehouse</li><li>Yard</li></ul><p>Our warehouse ".(rand(1, 2) == 1 ? "locating" : "search")." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to you. Contact us today and we look forward to ".(rand(1, 2) == 1 ? "working with you" : "finding the right space for you").".</p>'";
		}
		else
		{
			$sql .= "'<p>Current inventory for ".$row['CityName']." is one listing but given the nature of the commercial real estate market, more listings may be available or could come onto the market soon. Contact our warehouse specialists for more information and we can look at opportunities outside of the market or alert you when new listings that meet your needs become available.</p><p>Find industrial space that meets your needs:</p><ul><li>Distribution</li><li>Flex".(rand(1, 2) == 1 ? " space" : "")."</li><li>Manufacturing</li><li>";
		
			$x = rand(1, 3);
			$sql .= ($x == 1 ? "Office / R&D" : ($x == 2 ? "R&D" : "Research and Development"));
		
			$sql .= "</li><li>Refrigerated".(rand(1, 2) == 1 ? " / Cold Storage" : "")."</li><li>".(rand(1, 2) == 1 ? "Showroom" : "Street Retail")."</li><li>Storage</li><li>".(rand(1, 2) == 1 ? "Truck Terminal" : "Truck Hub")."</li><li>Warehouse</li><li>Yard</li></ul><p>Our warehouse ".(rand(1, 2) == 1 ? "locating" : "search")." services are provided at no ".(rand(1, 2) == 1 ? "charge" : "cost")." to you. Contact us today and we look forward to ".(rand(1, 2) == 1 ? "working with you" : "finding the right space for you").".</p>'";
		
		}	
		
		$sql .= " WHERE CityName = '".$row['CityName']."' AND StateProvCode = '".$row['StateProvCode']."'";

		$result_insert = $link->query($sql);
		echo $sql."<br /><br />";
	}		
}

$link->close();
?>