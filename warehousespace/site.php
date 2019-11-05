<?php
        include 'SitemapGenerator.php';

        // create object
        $sitemap = new SitemapGenerator("https://warehousespaces.com/");

$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

if (isset($_GET["q"])) $q = explode(",", $_GET["q"]);
$states = array(
    'Alabama'=>'AL',
    'Alaska'=>'AK',
    'Arizona'=>'AZ',
    'Arkansas'=>'AR',
    'California'=>'CA',
    'Colorado'=>'CO',
    'Connecticut'=>'CT',
    'Delaware'=>'DE',
    'District of Columbia'=>'DC',
    'Florida'=>'FL',
    'Georgia'=>'GA',
    'Hawaii'=>'HI',
    'Idaho'=>'ID',
    'Illinois'=>'IL',
    'Indiana'=>'IN',
    'Iowa'=>'IA',
    'Kansas'=>'KS',
    'Kentucky'=>'KY',
    'Louisiana'=>'LA',
    'Maine'=>'ME',
    'Maryland'=>'MD',
    'Massachusetts'=>'MA',
    'Michigan'=>'MI',
    'Minnesota'=>'MN',
    'Mississippi'=>'MS',
    'Missouri'=>'MO',
    'Montana'=>'MT',
    'Nebraska'=>'NE',
    'Nevada'=>'NV',
    'New Hampshire'=>'NH',
    'New Jersey'=>'NJ',
    'New Mexico'=>'NM',
    'New York'=>'NY',
    'North Carolina'=>'NC',
    'North Dakota'=>'ND',
    'Ohio'=>'OH',
    'Oklahoma'=>'OK',
    'Oregon'=>'OR',
    'Pennsylvania'=>'PA',
    'Rhode Island'=>'RI',
    'South Carolina'=>'SC',
    'South Dakota'=>'SD',
    'Tennessee'=>'TN',
    'Texas'=>'TX',
    'Utah'=>'UT',
    'Vermont'=>'VT',
    'Virginia'=>'VA',
    'Washington'=>'WA',
    'West Virginia'=>'WV',
    'Wisconsin'=>'WI',
    'Wyoming'=>'WY',
);

$sql_list_cities = 'select distinct cityname, StateProvCode, count(*) AS num from warehouse_listing where ListingIsActive = \'y\' AND trim(cityname) <> "" group by cityname order by cityname;';

	try{
		$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");
		//$mysqli = mysqli_connect("localhost", "root", "amar", "largewarehouse_com");
		$result = $mysqli->query($sql_list_cities);
		/* printf("Affected rows (INSERT): %d\n", $mysqli->affected_rows); */
		}
	catch(exception $e) { var_dump($e);}
	$tmpCount =0;
	print'<div class="row">';
	$name= array();

	while ($row = mysqli_fetch_array($result)) 
{
	if ($row['num'] > 45 || $row['cityname'] == 'Long Beach')
	{
$state_name = trim($row['StateProvCode']);
$city_name = trim($row['cityname']);
if (!in_array($row['cityname'], $name)) {


  echo "<br>" . $state_url = 'https://warehousespaces.com/warehouses/United-States/'.$state_name."/".$city_name;

  echo "<br>" . $state_rent_url = 'https://warehousespaces.com/warehouse-for-rent/United-States/'.$state_name."/".$city_name.'?rent_property=all';

  echo "<br>" . $state_sale_url = 'https://warehousespaces.com/warehouse-for-sale/United-States/'.$state_name."/".$city_name.'?sell_property=all';

  $tmpCount ++;
         $sitemap->addUrl($state_url, date('c'), 'daily', '1');
         $sitemap->addUrl($state_rent_url, date('c'), 'daily', '1');
         $sitemap->addUrl($state_sale_url, date('c'), 'daily', '1');

  }
 }
 $name[] = trim($row['cityname']);
}

$search_sql_stmt = "select distinct * from warehouse_listing WHERE ListingIsActive = 'y'";
	$result = $mysqli->query($search_sql_stmt);
		while ($row = mysqli_fetch_array($result)) {

if($row['created_from'] == 'json')
{
    $property_url = 'https://warehousespaces.com/warehouse-for-sale/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'];
         $sitemap->addUrl($property_url, date('c'), 'daily', '1');
} else {
 $property_url = 'https://warehousespaces.com/warehouse-for-rent/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'];
         $sitemap->addUrl($property_url, date('c'), 'daily', '1');
}
}
        $sitemap->createSitemap();

        // write sitemap as file
        $sitemap->writeSitemap();

        // update robots.txt file
        $sitemap->updateRobots();

        // submit sitemaps to search engines
        $sitemap->submitSitemap();

?>