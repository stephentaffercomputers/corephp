<?php

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


$sql_list_cities = 'select distinct cityname, StateProvCode, count(*) AS num from listings where ListingIsActive = \'y\' AND trim(cityname) <> "" group by cityname order by cityname;';



	try{
		$mysqli = mysqli_connect("localhost", "searchco_db", "7T2kHlRhuyLHFEKU", "searchco_db");
		//$mysqli = mysqli_connect("localhost", "root", "amar", "largewarehouse_com");
		$result = $mysqli->query($sql_list_cities);

//		$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

		//$mysqli = mysqli_connect("localhost", "root", "amar", "largewarehouse_com");

//		$result = $mysqli->query($sql_list_cities);

		/* printf("Affected rows (INSERT): %d\n", $mysqli->affected_rows); */

		}

	catch(exception $e) { var_dump($e);}

	$tmpCount =0;

	print'<ul style=" margin-bottom:0px;  width:20%; float:left;" class="custom_list_group list-group">';

	$name= array();

$final_result = array();

$i = 0;

	while ($row = mysqli_fetch_array($result)) 

{
	if ($row['num'] > 50 || $row['cityname'] == 'Long Beach')

	{
$final_result[$i]['cityname'] = trim($row['cityname']);
$final_result[$i]['StateProvCode'] = $row['StateProvCode'];

$final_result[$i]['num'] = $row['num'];

$i++;

}

}


function msort($array, $key, $sort_flags = SORT_REGULAR) {

    if (is_array($array) && count($array) > 0) {

        if (!empty($key)) {

            $mapping = array();

            foreach ($array as $k => $v) {

                $sort_key = '';

                if (!is_array($key)) {

                    $sort_key = $v[$key];

                } else {

                    // @TODO This should be fixed, now it will be sorted as string

                    foreach ($key as $key_key) {

                        $sort_key .= $v[$key_key];

                    }

                    $sort_flags = SORT_STRING;

                }

                $mapping[$k] = $sort_key;

            }

            asort($mapping, $sort_flags);

            $sorted = array();

            foreach ($mapping as $k => $v) {

                $sorted[] = $array[$k];

            }

            return $sorted;

        }

    }

    return $array;

}





$final_result = msort($final_result, array('cityname'));



//array_multisort (array_column($final_result, 'cityname'), SORT_ASC, $final_result);

//echo "<pre>";

//print_r($final_result);
//exit;


//	while ($row = mysqli_fetch_array($result)) 

foreach($final_result as $res)

{

//	if ($row['num'] > 45 || $row['cityname'] == 'Long Beach')

//	{

$state_name = trim($res['StateProvCode']);

$city_name = trim($res['cityname']);

if (!in_array($res['cityname'], $name)) {





  print '<li style="padding:5px; border:0px !important;" class="list-group-item mob_city"><a href="/commercial-real-estate/United-States/'.$res['StateProvCode']."/".$res['cityname'].'">'.$res['cityname'].'</a></li>';

  $tmpCount ++;

  if(is_int($tmpCount/18))

  print '</ul><ul style="margin-bottom:0px; width:20%; float:left;" class="custom_list_group list-group">';

  //}

 }

 $name[] = trim($res['cityname']);

}

if(($tmpCount /6) != (int)($tmpCount / 6) )print '</div>';

?>