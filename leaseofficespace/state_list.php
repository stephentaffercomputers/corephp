<?php
$states = array(
    'AL'=>'Alabama',
    'AK'=>'Alaska',
    'AZ'=>'Arizona',
    'AR'=>'Arkansas',
    'CA'=>'California',
    'CO'=>'Colorado',
    'CT'=>'Connecticut',
    'DE'=>'Delaware',
    'DC'=>'District of Columbia',
    'FL'=>'Florida',
    'GA'=>'Georgia',
    'HI'=>'Hawaii',
    'ID'=>'Idaho',
    'IL'=>'Illinois',
    'IN'=>'Indiana',
    'IA'=>'Iowa',
    'KS'=>'Kansas',
    'KY'=>'Kentucky',
    'LA'=>'Louisiana',
    'ME'=>'Maine',
    'MD'=>'Maryland',
    'MA'=>'Massachusetts',
    'MI'=>'Michigan',
    'MN'=>'Minnesota',
    'MS'=>'Mississippi',
    'MO'=>'Missouri',
    'MT'=>'Montana',
    'NE'=>'Nebraska',
    'NV'=>'Nevada',
    'NH'=>'New Hampshire',
    'NJ'=>'New Jersey',
    'NM'=>'New Mexico',
    'NY'=>'New York',
    'NC'=>'North Carolina',
    'ND'=>'North Dakota',
    'OH'=>'Ohio',
    'OK'=>'Oklahoma',
    'OR'=>'Oregon',
    'PA'=>'Pennsylvania',
    'RI'=>'Rhode Island',
    'SC'=>'South Carolina',
    'SD'=>'South Dakota',
    'TN'=>'Tennessee',
    'TX'=>'Texas',
    'UT'=>'Utah',
    'VT'=>'Vermont',
    'VA'=>'Virginia',
    'WA'=>'Washington',
    'WV'=>'West Virginia',
    'WI'=>'Wisconsin',
    'WY'=>'Wyoming',
);

$sql_list_cities = 'select distinct StateProvCode from office_listing where ListingIsActive = \'y\' AND trim(StateProvCode) <> "" order by StateProvCode;';

try{
	$mysqli = mysqli_connect("localhost", "leaseoff_db", "BTrsHGcg21vnwUpt", "leaseoff_db");

	$result = $mysqli->query($sql_list_cities);
}
catch(exception $e) { var_dump($e);}

$tmpCount = 0;
$res_in_col = ceil(mysqli_num_rows($result) / 3);

print "<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";

while ($row = mysqli_fetch_array($result)) 
{
	$num = 0;

	print "<li><a href=\"/offices-for-rent/United-States/".$row['StateProvCode']."\">".$states[$row['StateProvCode']]."</a><br />Cities Covered: ";
	
	$sql = 'select distinct cityname from office_listing where ListingIsActive = \'y\' AND StateProvCode = "'.$row['StateProvCode'].'"';
	
	try {
		$city_result = $mysqli->query($sql);
	}
	catch(exception $e) { var_dump($e);}

	$num = mysqli_num_rows($city_result);
	
	print $num."</li>\n";
	
	$tmpCount++;

	if ($tmpCount / $res_in_col == 1 || $tmpCount / $res_in_col == 2 || $tmpCount / $res_in_col == 3 || $tmpCount / $res_in_col == 4 || $tmpCount / $res_in_col == 5) print "</ul></li></ul></div>\n<div class=\"col-md-4 col-sm-6\"><ul class=\"sitemap\"><li><ul>\n";
}

print '</ul></li></ul></div>';
?>