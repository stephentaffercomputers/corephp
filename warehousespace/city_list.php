<?
$multiple_state_cities = array("Aurora", "Fairfield", "Newark", "Union City", "Westminster", "Lancaster");

//$sql_list_cities = 'select distinct cityname, StateProvCode from warehouse_listing where ListingIsActive = \'y\' AND trim(cityname) <> "" order by cityname;';
$sql_list_cities = 'select CityName as cityname, StateProvCode from city_descriptions order by CityName, StateProvCode;';

try{
	$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

	$result = $mysqli->query($sql_list_cities);
}
catch(exception $e) { var_dump($e);}

$tmpCount = 0;
$res_in_col = ceil(mysqli_num_rows($result) / 3);

print "<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";

while ($row = mysqli_fetch_array($result)) 
{	
	print "<li><a href=\"/warehouses/United-States/".$row['StateProvCode']."/".$row['cityname']."\">".ucwords(strtolower($row['cityname'])).(in_array($row['cityname'], $multiple_state_cities) ? " ".$row['StateProvCode'] : "")." Warehouse and Industrial Space</a></li>\n";
	
	$tmpCount++;

	if ($tmpCount / $res_in_col == 1 || $tmpCount / $res_in_col == 2 || $tmpCount / $res_in_col == 3 || $tmpCount / $res_in_col == 4 || $tmpCount / $res_in_col == 5) print "</ul></li></ul></div>\n<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";
}

print '</ul></li></ul></div>';
?>