<?php

$sql_list_cities = 'select distinct cityname, StateProvCode, count(*) AS num from medical_listing where trim(cityname) <> "" AND ListingIsActive = \'y\' group by cityname order by cityname;';



	try{
		$mysqli = mysqli_connect("localhost", "medicalo_db", "7CuDaKPPeXgpKT46", "medicalo_db");
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
	if ($row['num'] > 7 || $row['cityname'] == 'Long Beach')

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





$ciycount = "SELECT count(*) as total_listing FROM `medical_listing` WHERE `CityName` LIKE '%".$res['cityname']."%'";

$city_result_count = $mysqli->query($ciycount);
	$row = $city_result_count->fetch_row();
$city_count_final =  $row[0];
//echo $city_count_final = $city_result_count->num_rows;
	if($city_count_final >= 20) { 
  //print '<li style="padding:5px; border:0px !important;" class="list-group-item mob_city"><a href="/medical-office-for-rent/United-States/'.$res['StateProvCode']."/".$res['cityname'].'">'.$res['cityname'].'</a></li>';
  
    print '<li style="padding:5px; border:0px !important;" class="list-group-item mob_city"><a href="/medical-space/United-States/'.$res['StateProvCode']."/".$res['cityname'].'">'.$res['cityname'].'</a></li>';

  $tmpCount ++;
	}

  if(is_int($tmpCount/18))

  print '</ul><ul style="margin-bottom:0px; width:20%; float:left;" class="custom_list_group list-group">';

  //}

 }

 $name[] = trim($res['cityname']);

}

if(($tmpCount /6) != (int)($tmpCount / 6) )print '</div>';

?>