<?php
$sql_list_cities = 'select CityName, StateProvCode from city_descriptions order by CityName;';

try{
	$mysqli = mysqli_connect("localhost", "leaseret_db", "dtWDmtmHZpUgrd4R", "leaseret_db");

	$result = $mysqli->query($sql_list_cities);
}
catch(exception $e) { var_dump($e);}

$tmpCount = 0;
$res_in_col = ceil(mysqli_num_rows($result) / 3);

print "<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";

while ($row = mysqli_fetch_array($result)) 
{	
	print "<li><a href=\"/retail-for-rent/United-States/".$row['StateProvCode']."/".$row['CityName']."\">".$row['CityName'].($row['CityName'] == "Newark" ? " ".$row['StateProvCode'] : "")." Retail Space</a></li>\n";
	
	$tmpCount++;

	if ($tmpCount / $res_in_col == 1 || $tmpCount / $res_in_col == 2 || $tmpCount / $res_in_col == 3 || $tmpCount / $res_in_col == 4 || $tmpCount / $res_in_col == 5) print "</ul></li></ul></div>\n<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";
}

print '</ul></li></ul></div>';
?>