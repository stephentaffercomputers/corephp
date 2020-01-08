<?php
$sql_list_cities = 'select distinct cityname, StateProvCode from listings where ListingIsActive = \'y\' AND trim(cityname) <> "" order by cityname;';

try{
	$mysqli = mysqli_connect("localhost", "searchco_db", "7T2kHlRhuyLHFEKU", "searchco_db");

	$result = $mysqli->query($sql_list_cities);
}
catch(exception $e) { var_dump($e);}

$tmpCount = 0;
$res_in_col = ceil(mysqli_num_rows($result) / 3);

print "<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";

while ($row = mysqli_fetch_array($result)) 
{	
	print "<li><a href=\"/commercial-real-estate/United-States/".$row['StateProvCode']."/".$row['cityname']."\">".$row['cityname'].($row['cityname'] == "Newark" ? " ".$row['StateProvCode'] : "")." Commercial Real Estate</a></li>\n";
	
	$tmpCount++;

	if ($tmpCount / $res_in_col == 1 || $tmpCount / $res_in_col == 2 || $tmpCount / $res_in_col == 3 || $tmpCount / $res_in_col == 4 || $tmpCount / $res_in_col == 5) print "</ul></li></ul></div>\n<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";
}

print '</ul></li></ul></div>';
?>