<?php
///$sql_list_cities = 'select distinct cityname, StateProvCode from medical_listing where trim(cityname) <> "" AND  ListingIsActive = "y" order by cityname;';

$sql_list_cities = 'select distinct cityname, StateProvCode, count(*) AS num from medical_listing where trim(cityname) <> "" AND ListingIsActive = \'y\' group by cityname order by cityname;';


try{
	$mysqli = mysqli_connect("localhost", "medicalo_db", "7CuDaKPPeXgpKT46", "medicalo_db");

	$result = $mysqli->query($sql_list_cities);
}
catch(exception $e) { var_dump($e);}

$tmpCount = 0;
$res_in_col = ceil(mysqli_num_rows($result) / 3);

print "<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";

while ($row = mysqli_fetch_array($result)) 
{
    if ($row['num'] > 7) {
	print "<li><a href=\"/medical-space/United-States/".$row['StateProvCode']."/".ltrim($row['cityname'])."\">".$row['cityname'].($row['cityname'] == "Newark" ? " ".$row['StateProvCode'] : "")." Medical Office Space</a></li>\n";
    }

	$tmpCount++;

	if ($tmpCount / $res_in_col == 1 || $tmpCount / $res_in_col == 2 || $tmpCount / $res_in_col == 3 || $tmpCount / $res_in_col == 4 || $tmpCount / $res_in_col == 5) print "</ul></li></ul></div>\n<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";
}

print '</ul></li></ul></div>';
?>