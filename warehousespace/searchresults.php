<?php include 'header.php'; ?>

<link rel="stylesheet" href="https://warehousespaces.com/js/leaflet/leaflet.css" />

<!--[if lte IE 8]>

<link rel="stylesheet" href="https://warehousespaces.com/leaflet/leaflet.ie.css" />

<![endif]-->

<link rel="stylesheet" href="https://warehousespaces.com/js/leaflet/MarkerCluster.Default.css" />

<!--[if lte IE 8]>

<link rel="stylesheet" href="https://warehousespaces.com/leaflet/MarkerCluster.Default.ie.css" />

<![endif]-->

<style type="text/css" title="currentStyle">

    @import "https://warehousespaces.com/js/datatables/css/demo_table.css";



     /** FIX for Bootstrap and Google Maps Info window styes problem **/

     img[src*="gstatic.com/"], img[src*="googleapis.com/"] {

    max-width: none;

     }

</style>

<script src="https://maps.google.com/maps/api/js?v=3.3&amp;libraries=geometry&amp;sensor=false&amp;key=AIzaSyCNswMe5_HsaKaYjqmzPe-FP-19rBqafd4" type="text/javascript"></script>

<?php

$prop_type_array = array("office" => "office-space", "industrial" => "warehouse-space", "medical" => "medical-space", "retail" => "retail-space");

$markers_script = ""; //variable to store map markers

$popup_script = ""; //variable to store popup infowindows in map

$table_rows = ""; //variable to store result rows

$q = ''; //URL query string

$zip_where_clause = "";



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





if (sizeof($q) > 1 ) $search_sql_stmt = "select distinct *, 1 AS rank from warehouse_listing where ListingIsActive = 'y' AND CityName ='".trim($q[0])."' and PostalCode = '".trim($q[1])."'";

else {

	if ($q[0] == 'Arlington' || $q[0] == 'arlington') $search_sql_stmt = "select distinct *, 1 AS rank from warehouse_listing WHERE ListingIsActive = 'y' AND CityName = '".$q[0]."'";

	else 

		if (strlen($q[0]) == 2) 

		{

		 	$search_sql_stmt = "select distinct *, 1 AS rank from warehouse_listing WHERE ListingIsActive = 'y' AND StateProvCode like '%".$q[0]."%'";

		 	$statecode = $q[0];

		}

		else

		{ 

 				if(!empty($states[$q[0]]))

				{

					$search = $states[$q[0]];

			   $search_sql_stmt = "select distinct *, 1 AS rank from warehouse_listing WHERE ListingIsActive = 'y' AND  StateProvCode ='". $search."'";

				}

				else

				{

					$search = $q[0];

			  $search_sql_stmt = "select distinct *, 1 AS rank from warehouse_listing WHERE ListingIsActive = 'y' AND concat(CityName, StateProvCode, PostalCode) like '%".trim(str_replace("-", " ", $search))."%'";

				}

		}

	$zip_sql = "SELECT ZipCodes FROM city_descriptions WHERE CityName='".$q[0]."'";

	$zip_result = $mysqli->query($zip_sql);

	$zip_row = mysqli_fetch_array($zip_result);



	if ($zip_row['ZipCodes'])

	{

		$zip_codes = explode(",", $zip_row['ZipCodes']);

	

		if (count($zip_codes))

		{

			$zip_where_clause = " OR (ListingIsActive = 'y' AND (";

			foreach ($zip_codes AS $key => $value) $zip_where_clause .= "PostalCode = '".$value."' OR ";

			$zip_where_clause = substr($zip_where_clause, 0, -4)."))";

		}



		$cityname = $q[0];

	}

}



if ($_GET['state']) {

	$search_sql_stmt .= " and StateProvCode='".$_GET['state']."'";

	$statecode = $_GET['state']; 	

}



if ($_GET['space_type']) {

	$search_sql_stmt .= " and PropertySubType='".$_GET['space_type']."'";	

}



if ($_GET['price_range']) {

	list($min, $max) = explode("-", $_GET['price_range']);

	$search_sql_stmt .= " and MonthlyRate >= ".$min." and MonthlyRate <= ".$max;

}



if ($_GET['size_range']) {
   
	list($min, $max) = explode("-", $_GET['size_range']);

	$search_sql_stmt .= " and cast(replace(replace(SpaceAvailable, ' SF', ''), ',', '') AS UNSIGNED) >= ".$min.($max ? " and cast(replace(replace(SpaceAvailable, ' SF', ''), ',', '') AS UNSIGNED) <= ".$max : "");

}


$search_sql_stmt_sale = $search_sql_stmt;

$search_sql_stmt_sale .= ' and created_from = "json" ';

$search_sql_stmt_sale .= $zip_where_clause;



$search_sql_stmt .= ' and created_from != "json" ';

$search_sql_stmt .= $zip_where_clause;



$search_sql_stmt = $search_sql_stmt." AND RentalRate != 'Negotiable' UNION ".str_replace(', 1 AS rank ', ', 2 AS rank ', $search_sql_stmt)." AND RentalRate = 'Negotiable' ORDER BY rank, MonthlyRate ASC";



//$search_sql_stmt .= " ORDER BY ListingID ASC";

//echo $search_sql_stmt;

try {



	$result = $mysqli->query($search_sql_stmt);

	$result_sale = $mysqli->query($search_sql_stmt_sale);

	

	$rec_count = $result->num_rows;

	$rec_sale_count = $result_sale->num_rows;

	

	if ($rec_count > 0 || $rec_sale_count > 0)

	{

		$limit = "21";

	

		$num_of_pages = ceil($rec_count / $limit);



		if (!isset($_GET{'page'}) || empty($_GET{'page'})) $page = 1;

		else $page = $_GET{'page'};





		if (!isset($_GET{'page'}) || empty($_GET{'page'})) $sale_page = 1;

		else $sale_page = $_GET{'page'};



		$sql = $search_sql_stmt." LIMIT ".(($page - 1) * $limit).", $limit";

		$sql_sale = $search_sql_stmt_sale." LIMIT ".(($sale_page - 1) * $limit).", $limit";



		$result_page = $mysqli->query($sql);

		$result_page_sale = $mysqli->query($sql_sale);



	    /***********************************************************************/

    	/**  Loop through the resultset and generate the content              **/

    	/***********************************************************************/

    	if(!isset($_GET['sell_property'])) {

    	$sidebar_content .= '<div class="col-sm-12"><h2 style="background-color: #ffffff;">'.ucwords( $q[0].' '.$prop_type.' Warehouse Space for rent').'</h2></div>';

		while ($row = mysqli_fetch_array($result_page)) {

		 	if (empty($statecode)) $statecode = $row["StateProvCode"];

        	//////////////////////////////////////////////

        	// generate the results table row by row    //

        	//////////////////////////////////////////////

        	$mod_StreetAddress = substr($row['StreetAddress'].($row['SpaceNumber'] ? ', '.$row['SpaceNumber'] : ""), 0, 23);

	if (strpos($row['RentalRateMin'], 'Year') !== false)



	{



		if (strpos($row['RentalRateMin'], '/SF/Year')) $thisRateNew = number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable'])));



		else $thisRateNew = number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12));



	}



	elseif (strpos($row['RentalRateMin'], 'SF') !== false) 



		$thisRateNew = number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable'])));



	else



		 $thisRateNew = number_format(floatval(ereg_replace("[^-0-9\.]","",$row['RentalRateMin'])));



//echo "<br>" . $new_price_calc =  str_replace( ',', '', $thisRateNew);

$new_sqft =   str_replace( ',', '', substr($row['SpaceAvailable'],0, -3));
if($new_sqft >= 5000) {
        	if($row['created_from'] == 'json')

        	{

        	$sidebar_content .= '<div class="col-sm-4" style="height:500px;"><div class="shop-item"><div class="image">

								<a href="/warehouse-for-sale/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'"><img class="img-polaroid" id="img'.$row['ListingID'].'" height="150" alt="'.$row['StreetAddress'].'" src="';



        	}

        	else

        	{



        	$sidebar_content .= '<div class="col-sm-4" style="height:500px;"><div class="shop-item"><div class="image">

								<a href="/warehouse-for-rent/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'"><img class="img-polaroid" id="img'.$row['ListingID'].'" height="150" alt="'.$row['StreetAddress'].'" src="';
			}



			if ($row['PhotoURL']!= "") $sidebar_content .= $row['PhotoURL'] ;

			else $sidebar_content .= '/images/no-available.jpg';



        	if($row['created_from'] == 'json')

        	{



			$sidebar_content .='"></a>

							</div>

							<!-- Product Title -->

							<div class="title">

								<h3><a href="/warehouse-for-sale/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'">'.$mod_StreetAddress.'</a></h3><center><small>'.$row['CityName'].', '.$row["StateProvCode"].'</small></center>

							</div>

							<div class="price">';

			}

			else {

			$sidebar_content .='"></a>

							</div>

							<!-- Product Title -->

							<div class="title">

								<h3><a href="/warehouse-for-rent/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'">'.$mod_StreetAddress.'</a></h3><center><small>'.$row['CityName'].', '.$row["StateProvCode"].'</small></center>

							</div>

							<div class="price">';



			}



if ($row['RentalRateMin'] == "Negotiable") $sidebar_content .=  "Negotiable";

else

{

	if (strpos($row['RentalRateMin'], 'Year') !== false)

	{

		if (strpos($row['RentalRateMin'], '/SF/Year')) $thisRate = number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable'])));

		else $thisRate = number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12));

	}

	elseif (strpos($row['RentalRateMin'], 'SF') !== false) 

		$thisRate = number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable'])));

	else

		 $thisRate = number_format(floatval(ereg_replace("[^-0-9\.]","",$row['RentalRateMin'])));



	$sidebar_content .=  '$ '.$thisRate.'/mo';

}

						    $get_last_five = substr($row['RentalRateMin'],-5);
							if($get_last_five == '/Year')
							{
								$rental_rate_min = substr($row['RentalRateMin'], 0, strlen($row['RentalRateMin']) - 5).'/Month';
							}
							else
							{
								$rental_rate_min = $row['RentalRateMin'];
							}
						/*	echo "<br>" . $row['RentalRateMin'];
							echo "<br>" . $row['SpaceAvailable'];
							exit;*/
							
 $rent_amount = ereg_replace("[^-0-9\.]", "", $row['RentalRateMin']);
 $space_amount = preg_replace("/[^0-9]/", "", $row['SpaceAvailable']);
 $rent_final = $rent_amount / $space_amount;
       if (preg_match('/\Month\b/', $row['RentalRateMin'])) {
 $rental_rate_min = number_format($rent_final,2) . '/SF/Month'; 
 } else {
 $rental_rate_min = number_format(($rent_amount / 12),2) . '/SF/Month';        

   }
 if($row['created_from'] == 'json') {
$rental_rate_min = number_format($rent_amount / ereg_replace(",", "", $row['SpaceAvailable']),2) . '/SF/Month';
}

if($rental_rate_min == '0.00/SF/Month') {
    $rental_rate_min = substr($rent_amount,0,4) . '/SF/Month';
}
/*if ($row['RentalRateMin'] != "Negotiable") $sidebar_content .=  '<br /><span style="font-weight: normal; font-size: 14px;">($'.number_format(ereg_replace(",", "", $thisRate) / ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable']), 2).'/sf./mo)</span>';*/
if ($row['RentalRateMin'] != "Negotiable") $sidebar_content .=  '<br /><span style="font-weight: normal; font-size: 14px;">('.$rental_rate_min.')</span>';
else $sidebar_content .=  '<br /><span style="font-weight: normal; font-size: 14px;">&nbsp;</span>';



        	if($row['created_from'] == 'json')

        	{

			$sidebar_content .= '</div><div class="description">

								<p>'.$row['PropertyType'].'<br>'.$row['PropertySubType'].'<br />'.number_format (ereg_replace("[^-0-9\.]","",$row['SpaceAvailable'])).' square feet</p>

							</div>

							<div class="actions">

								<a href="/warehouse-for-sale/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'" class="btn">More Infoz</a>

							</div>

						</div></div>';

			}

			else

			{



			$sidebar_content .= '</div><div class="description">

								<p>'.$row['PropertyType'].'<br>'.$row['PropertySubType'].'<br />'.number_format (ereg_replace("[^-0-9\.]","",$row['SpaceAvailable'])).' square feet</p>

							</div>

							<div class="actions">

								<a href="/warehouse-for-rent/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'" class="btn">More Info</a>

							</div>

						</div></div>';



			}

		}
}
		if(!isset($_GET['sell_property']) && !isset($_GET['rent_property'])) {



	    	$sidebar_content .= "<div class=\"col-sm-12\" style=\"margin-bottom: 20px; float: right;\"><div class=\"actions\" style=\"float: right;\"><a href=\"/warehouse-for-rent/United-States/".$statecode.'/'.$q[0]."?rent_property=all\" class=\"btn\">View More ".ucwords($prop_type." Space for rent In ".$q[0]).' >>></a></div></div>';

	    }

	  

	}

	    if(!isset($_GET['rent_property'])) {

    	$sidebar_content .= '<div class="col-sm-12"><h2 style="background-color: #ffffff;">'.ucwords( $q[0].' '.$prop_type.' Warehouse Space for sale').'</h2></div>';

		while ($row = mysqli_fetch_array($result_page_sale)) {
$new_sqft =   str_replace( ',', '', substr($row['SpaceAvailable'],0, -3));
if($new_sqft >= 5000) {

		 if (empty($statecode)) $statecode = $row["StateProvCode"];

        	//////////////////////////////////////////////

        	// generate the results table row by row    //

        	//////////////////////////////////////////////

        	if($row['created_from'] == 'json') {

        	$mod_StreetAddress = substr($row['StreetAddress'].($row['SpaceNumber'] ? ', '.$row['SpaceNumber'] : ""), 0, 23);



        	if($row['created_from'] == 'json')

        	{

        	$sidebar_content .= '<div class="col-sm-4" style="height:500px;"><div class="shop-item"><div class="image">

								<a href="/warehouse-for-sale/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'"><img class="img-polaroid" id="img'.$row['ListingID'].'" height="150" alt="'.$row['StreetAddress'].'" src="';



        	}

        	else

        	{



        	$sidebar_content .= '<div class="col-sm-4"><div class="shop-item"><div class="image">

								<a href="/warehouse-for-rent/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'"><img class="img-polaroid" id="img'.$row['ListingID'].'" height="150" alt="'.$row['StreetAddress'].'" src="';

			}



			if ($row['PhotoURL']!= "") $sidebar_content .= $row['PhotoURL'] ;

			else $sidebar_content .= '/images/no-available.jpg';



        	if($row['created_from'] == 'json')

        	{

        		if($row['StateProvCode'] != 'CA') {

			$sidebar_content .='"></a>

							</div>

							<!-- Product Title -->

							<div class="title">

								<h3><a href="/warehouse-for-sale/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'">'.$mod_StreetAddress.'</a></h3><center><small>'.$row['CityName'].', '.$row["StateProvCode"].' - OFF MARKET</small></center>

							</div>

							<div class="price">';



        		} else {



			$sidebar_content .='"></a>

							</div>

							<!-- Product Title -->

							<div class="title">

								<h3><a href="/warehouse-for-rent/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'">'.$mod_StreetAddress.'</a></h3><center><small>'.$row['CityName'].', '.$row["StateProvCode"].'</small></center>

							</div>

							<div class="price">';

        		}

			}

			else {

			$sidebar_content .='"></a>

							</div>

							<!-- Product Title -->

							<div class="title">

								<h3><a href="/warehouse-for-rent/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'">'.$mod_StreetAddress.'</a></h3><center><small>'.$row['CityName'].', '.$row["StateProvCode"].'</small></center>

							</div>

							<div class="price">';



			}



if ($row['RentalRateMin'] == "Negotiable") $sidebar_content .=  "Negotiable";

else

{

	if (strpos($row['RentalRateMin'], 'Year') !== false)

	{

		if (strpos($row['RentalRateMin'], '/SF/Year')) $thisRate = number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable'])));

		else $thisRate = number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12));

	}

	elseif (strpos($row['RentalRateMin'], 'SF') !== false) 

		$thisRate = number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable'])));

	else

		 $thisRate = number_format(floatval(ereg_replace("[^-0-9\.]","",$row['RentalRateMin'])));



	$sidebar_content .=  '$ '.$thisRate.'/mo';

}



if ($row['RentalRateMin'] != "Negotiable") $sidebar_content .=  '<br /><span style="font-weight: normal; font-size: 14px;">('.number_format(ereg_replace(",", "", $thisRate) / ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable']), 2).' $/sf./year)</span>';

else $sidebar_content .=  '<br /><span style="font-weight: normal; font-size: 14px;">&nbsp;</span>';

        		$city_replace = " Los Angeles "."("."Torrance P.O";

        		if($row['CityName'] == $city_replace) {

        			$row['CityName'] = 'Torrance';

        		}

        		if($row['CityName'] == 'Palo Alto (E)') {

        			$row['CityName'] = 'Palo-Alto';

        		}

        		



        	if($row['created_from'] == 'json')

        	{

			$sidebar_content .= '</div><div class="description">

								<p>'.$row['PropertyType'].'<br>'.$row['PropertySubType'].'<br />'.number_format (ereg_replace("[^-0-9\.]","",$row['SpaceAvailable'])).' square feet</p>

							</div>

							<div class="actions">

								<a href="/warehouse-for-sale/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'" class="btn">More Info</a>

							</div>

						</div></div>';

			}

			else

			{



			$sidebar_content .= '</div><div class="description">

								<p>'.$row['PropertyType'].'<br>'.$row['PropertySubType'].'<br />'.number_format (ereg_replace("[^-0-9\.]","",$row['SpaceAvailable'])).' square feet</p>

							</div>

							<div class="actions">

								<a href="/warehouse-for-rent/United-States/'.$row["StateProvCode"].'/'.trim($row['CityName']).'/'.$row['ListingID'].'" class="btn">More Info</a>

							</div>

						</div></div>';



			}

		 }

}

		}

			if(!isset($_GET['sell_property']) && !isset($_GET['rent_property'])) {

	    	$sidebar_content .= "<div class=\"col-sm-12\" style=\"margin-bottom: 20px; float: right;\"><div class=\"actions\" style=\"float: right;\"><a href=\"/warehouse-for-sale/United-States/".$statecode.'/'.$q[0]."?sell_property=all\" class=\"btn\">View More ".ucwords($prop_type." Space for sale In ".$q[0]).' >>></a></div></div>';

	    }

		}

		//close the resultset

    	$result_page->close();

    	$result_page_sale->close();



		//////////////////////////////////////////////////////////

    	// generate the js code to create map markers and       //

    	// popup infowondows for each property in the resultset //    

    	//////////////////////////////////////////////////////////

		

		$patterns = array("/'/", "/\"/");



		while ($row = mysqli_fetch_array($result)) {        

    		if ($row["Latitude"] != "")	{



        	if($row['created_from'] == 'json')

        	{



				$markers_script .= 'var myLatlng = new google.maps.LatLng('.$row["Latitude"].', '.$row["Longitude"].'); var marker = new google.maps.Marker({position: myLatlng, title: "'.preg_replace($patterns, '', $row['StreetAddress'].' <Br> '.$row['CityName'].' <br> Propertytype: '.$row['PropertyType'].' | '.$row['PropertySubType']).'", map: map });'.

				" var contentTxt = '<div><div class=\"row-fluid\"><a href=\"/warehouse-for-sale/United-States/".$row['StateProvCode']."/".$row['CityName']."/".$row['ListingID']."\">".preg_replace($patterns, '', $row['StreetAddress'].", ".$row['CityName'])."</a><br />".$row['SpaceAvailableTotal']." sq. ft.</div></div>';".

				" marker.infoWindow = new google.maps.InfoWindow({

				  content: contentTxt

				});".

				" google.maps.event.addListener(marker,'click',function() {

				  infoClose();

				  infoList.push(this);

				  this.infoWindow.open(map,this);

				});";

			}

			else

			{

				$markers_script .= 'var myLatlng = new google.maps.LatLng('.$row["Latitude"].', '.$row["Longitude"].'); var marker = new google.maps.Marker({position: myLatlng, title: "'.preg_replace($patterns, '', $row['StreetAddress'].' <Br> '.$row['CityName'].' <br> Propertytype: '.$row['PropertyType'].' | '.$row['PropertySubType']).'", map: map });'.

				" var contentTxt = '<div><div class=\"row-fluid\"><a href=\"/warehouse-for-rent/United-States/".$row['StateProvCode']."/".$row['CityName']."/".$row['ListingID']."\">".preg_replace($patterns, '', $row['StreetAddress'].", ".$row['CityName'])."</a><br />".$row['SpaceAvailableTotal']." sq. ft.</div></div>';".

				" marker.infoWindow = new google.maps.InfoWindow({

				  content: contentTxt

				});".

				" google.maps.event.addListener(marker,'click',function() {

				  infoClose();

				  infoList.push(this);

				  this.infoWindow.open(map,this);

				});";



			}



    		}

		}



		//////////////////////////////////////////////////////

		// fetch a single row to set                        //

		// 1. Center Lat Lng for map                        //

		// 2. statecode variable to be used in page         //

		// 3. citycode var to be used in page later         //    

		//////////////////////////////////////////////////////



	    $result->data_seek(0);

	    $firstLatLng = "";



	    while (!$firstLatLng && $onerow = $result->fetch_assoc())

	    {

    		$statecode = $onerow['StateProvCode'];

    		if (!isset($cityname)) $cityname = $onerow['CityName'];

    

			if ($onerow["Latitude"] != "" && $onerow["Longitude"] != "") $firstLatLng = 'new google.maps.LatLng('.$onerow["Latitude"].','.$onerow["Longitude"].')';

		}



		if (!firstLatLng) $firstLatLng = 'new google.maps.LatLng(33.800903676512,-118.27401280403)';



		//close the resultset

    	$result->close();



	}

	else

	{

		$firstLatLng = 'new google.maps.LatLng(33.800903676512,-118.27401280403)';

		//$sidebar_content .= "<strong>Your search did not match any properties.</strong><br /><br /><a href=\"https://warehousespaces.com\">Start a New Search</a>"; 

		$sidebar_content .= "<strong>We are refreshing inventory in ".$_GET["q"].".</strong><br /><br /><a href=\"https://warehousespaces.com\">Start a New Search</a>";

		$cityname = $q[0];

	}

}



catch(exception $e) { var_dump($e);}



/***********************************************************************/

/**  php function to generate the dropdown of property types          **/

/** This is to be used in the filter form used on this page           **/

/***********************************************************************/



function list_prop_types(){

	$sql_list_prop_types = "select distinct PropertyType, PropertySubType from warehouse_listing WHERE ListingIsActive = 'y' order by PropertyType, PropertySubType;";



	try {

        $mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");

        $result = $mysqli->query($sql_list_prop_types);

        }

    catch(exception $e) { var_dump($e);}

    

    $p_type_dropdown ='<select name="prop-type" id="prop-type" class="form-control">';

    $p_type_dropdown .= '<option value="">Show All</option>';

    

	while ($row = mysqli_fetch_array($result)) $p_type_dropdown .= '<option value="'.$row['PropertySubType'].'"'.($_GET['prop-type'] == $row['PropertySubType'] ? ' selected' : '').'>'.$row['PropertyType'].'-'.$row['PropertySubType'].'</option>';

	

	$p_type_dropdown.= '</select>';

    print $p_type_dropdown;



}    

?>

<!--



//////////////////////////////////////////////////////////////////////////////////

//Create the navbar with links to the the state page and the city page and home //

//////////////////////////////////////////////////////////////////////////////////

-->

<?php //echo $q[0];

//echo "<br>" . $cityname; ?>

		<div class="section section-breadcrumbs">

			<div class="container">

				<div class="row">

					<div class="col-md-12">

						<?php if($_GET['rent_property'] == 'all') { ?>

						<h1><a href="/">Home</a> / <? if ($q[0] != trim($cityname)) echo $statecode; else echo "<a href=\"/warehouse-for-rent/United-States/".$statecode."\">".$statecode."</a> / ".$cityname; ?> Warehouses / Warehouse Space for Rent</h1>

						<?php } else if($_GET['sell_property'] == 'all') { ?>

						<h1><a href="/">Home</a> / <? if ($q[0] != trim($cityname)) echo $statecode; else echo "<a href=\"/warehouse-for-rent/United-States/".$statecode."\">".$statecode."</a> / ".$cityname; ?> Warehouses / Warehouse Space for Sale</h1>

						<?php } else  { ?>

						<h1><a href="/">Home</a> / <? if ($q[0] != trim($cityname)) echo $statecode; else echo "<a href=\"/warehouse-for-rent/United-States/".$statecode."\">".$statecode."</a> / ".$cityname; ?> Warehouses</h1>

						<?php } ?>



					</div>

				</div>

			</div>

		</div>

        <div class="section">

	    	<div class="container">

				<div class="row">

					<!-- Sidebar -->

				<div class="col-sm-12">

<?php

$city_sql = "select distinct cityname, StateProvCode from warehouse_listing where ListingIsActive = 'y' AND trim(cityname) <> '' and StateProvCode = '".trim($q[0])."' order by cityname";



$city_result = $mysqli->query($city_sql);

	

$city_count = $city_result->num_rows;

	

if ($city_count < 1)

{

?>				

						<div class="row-fluid" id="map-canvas"></div>

<?php } ?>

						<div class="clear"></div>

<!--



//////////////////////////////////////////////////////////////////////////////////

// Generate the filter form used to fliter the result set                       //

//////////////////////////////////////////////////////////////////////////////////

-->	

						<div class="row-fluid">

							<form class="form-horizontal" role="form" id="filter-form" method="get" action="<? echo preg_replace('/\/page\d*/', '', $_SERVER['REQUEST_URI']); ?>">

								<center><strong>Filter Results by: </strong></center><br />

								<div class="form-group col-xs-12 col-sm-4 col-lg-4 custom_width">

		        				 	<label for="sq_ft" class="col-xs-4 control-label" style="width:111px;"><b>Size Range:</b></label>

		        				 	<div class="col-xs-7" style="padding-left:0px;">

										<select name="size_range" id ="size_range" class="form-control">

										<option value="0"> Any </option>

										<option value="0-3000"<? if ($_GET['size_range'] == "0-3000") echo " selected"; ?>> Under 3,000 SF </option>

										<option value="3000-5000"<? if ($_GET['size_range'] == "3000-5000") echo " selected"; ?>> 3,000 - 5,000 SF </option>

										<option value="5000-10000"<? if ($_GET['size_range'] == "5000-10000") echo " selected"; ?>> 5,000 - 10,000 SF </option>

										<option value="10000-15000"<? if ($_GET['size_range'] == "10000-15000") echo " selected"; ?>> 10,000 - 15,000 SF </option>

										<option value="15000-25000"<? if ($_GET['size_range'] == "15000-25000") echo " selected"; ?>> 15,000 - 25,000 SF </option>

										<option value="25000-50000"<? if ($_GET['size_range'] == "25000-50000") echo " selected"; ?>> 25,000 - 50,000 SF </option>   

										<option value="50000-75000"<? if ($_GET['size_range'] == "50000-75000") echo " selected"; ?>> 50,000 - 75,000 SF </option>

										<option value="75000-"<? if ($_GET['size_range'] == "75000-") echo " selected"; ?>> 75,000 - 100,000+ SF</option> 

										</select>

									</div>

								</div>	

								<div class="form-group col-xs-12 col-sm-4 col-lg-4 custom_width">

		        				 	<label for="price_range" style="width:116px;" class="col-xs-4 control-label"><b>Price Range:</b></label>

		        				 	<div class="col-xs-7" style="padding-left:0px;">

										<select name="price_range" id ="price_range" class="form-control">

										<option value="0" > Any</option>

										<option value="0-1000"<? if ($_GET['price_range'] == "0-1000") echo " selected"; ?>> Under $1,000/ month</option>

										<option value="1000-2000"<? if ($_GET['price_range'] == "1000-2000") echo " selected"; ?>> $1,000 - $2,000/ month</option>

										<option value="2000-3000"<? if ($_GET['price_range'] == "2000-3000") echo " selected"; ?>> $2,000 - $3,000 / month</option>

										<option value="3000-4000"<? if ($_GET['price_range'] == "3000-4000") echo " selected"; ?>> $3,000 - $4,000/ month</option>

										<option value="4000-6000"<? if ($_GET['price_range'] == "4000-6000") echo " selected"; ?>> $4,000 - $6,000/ month</option>

										<option value="6000-8000"<? if ($_GET['price_range'] == "6000-8000") echo " selected"; ?>> $6,000 - $8,000/ month</option>

										<option value="8000-10000"<? if ($_GET['price_range'] == "8000-10000") echo " selected"; ?>> $8,000 - $10,000/ month</option>

										<option value="10000-20000"<? if ($_GET['price_range'] == "10000-20000") echo " selected"; ?>> $10,000 - $20,000/ month</option>

										<option value="20000-50000"<? if ($_GET['price_range'] == "20000-50000") echo " selected"; ?>> $20,000 - $50,000/ month</option>

										<option value="50000-100000"<? if ($_GET['price_range'] == "50000-100000") echo " selected"; ?>> $50,000 - $100,000/ month</option>

										<option value="100000-250000"<? if ($_GET['price_range'] == "100000-250000") echo " selected"; ?>> $100,000 - $250,000 / month</option>

										<option value="250000-500000"<? if ($_GET['price_range'] == "250000-500000") echo " selected"; ?>> $250,000 - $500,000 / month</option>

										</select>	

									</div>

								</div>	                        

								<div class="form-group col-xs-12 col-sm-4 col-lg-4 custom_width">

		        				 	<label for="space_type" style="width:116px;" class="col-xs-4 control-label"><b>Space Type:</b></label>

		        				 	<div class="col-xs-7" style="padding-left:0px;">

										<? list_prop_types(); ?>	

									</div>

								</div>

								<div class="form-group col-xs-12 col-sm-2 col-lg-2">

										<button class="btn pull-left" type="submit" id="submit-filter-form" name="submit_filter_form">Filter Results</button>

								</div>

							</form>

						</div>

					</div>

					<div class="col-sm-4 blog-sidebar">



						<div class="clear"></div>

						<p>&nbsp;</p>

<!-- Product Summary & Options -->

	    			<div style="background: none repeat scroll 0% 0% padding-box #FFF; box-shadow: 0px 1px #FFF inset, 0px 0px 8px #C8CFE6; padding-left: 15px; padding-right: 15px;">

	    				<h2 style="color: black;">Free Property Report</h2>

             <ul class="unstyled">

			   <li> <i class="icon-ok"></i> <b>See all lease/purchase options in 24 hours</b></li>

			   <li> <i class="icon-ok"></i> <b>Get 1-2+ months of free rent on most leases</b></li>

				<li> <i class="icon-ok"></i> <b>We can negotiate 15-20% off list price</b></li>

				<li> <i class="icon-ok"></i> <b>Our tenant rep service is 100% free to clients</b></li>

				</ul>

				<p><strong>We can answer questions, send you a short list of options and schedule tours.</strong></p>

				<form id="contact-form" method="post" action="/process_contact_form.php" >

				<input type="hidden" name="utm_source" value="<? if ($_SESSION["utm_source"]) echo $_SESSION["utm_source"]; ?>" />

				<input type="hidden" name="utm_campaign" value="<? if ($_SESSION["utm_campaign"]) echo $_SESSION["utm_campaign"]; ?>" />

                <input type="hidden" name="contact_location" value="<? if ($q[0] != $cityname) echo $statecode; else echo $cityname.', '.$statecode; ?>">

                <input type="hidden" name="property_type" value="w">

				<input type="hidden" name="contact_budget" value="">

				<!-- <label for="contact_firstname">First Name:</label> !-->

                    <input class="form-firstname" type="text" placeholder="First Name" name="contact_firstname" ><br />

				<!-- <label for="contact_name">Name:</label> !-->

                    <input class="form-control required" type="text" placeholder="Name" name="contact_name" ><br />

				<!-- <label for="contact_phone">Phone:</label> !-->	

                    <input class="form-control required" type="tel" placeholder="Phone" name="contact_phone"><br />

				<!-- <label for="contact_email">Email:</label> !-->	

                    <input class="form-control required email" type="email" placeholder="Email" name="contact_email" ><br />

                <!-- <label for="contact_company">Company:</label> !-->	

                    <input class="form-control required" type="text" placeholder="Company" name="contact_company" ><br /> 

                    <!-- <label for="contact_sq_ft">Square Feet?:</label>    !-->

					<input class="form-control required" type="text" placeholder="Square Feet?" name="contact_sq_ft"><br />

                    <!--<input class="input-xlarge" type="text" placeholder="Additional Requirements?" name="contact_additional_req">-->

				<!-- <label for="contact_message">Comments:</label>	 !-->

                    <textarea class="form-control required" rows="3" name="contact_message" placeholder="What can you tell us about what you are looking for?" ></textarea><br />

                    <button class="btn btn-block btn-danger" type="submit" id="submit-contact-form" name="submit_contact_form">Submit</button>

                    <input type="hidden" name="property_url" value ="https://<?=$curr_url?>">

		      </form>

         <h6><small> By clicking Submit, you agree to our terms of user and privacy policy. Your message will be sent to local broker and not made public.</small></h6>

<hr>

<h6>You will receive:</h6>

<ul class="unstyled">

<li> <i class="icon-ok"></i> <em> Free Availability Report based on your search criteria </em></li>

<li> <i class="icon-ok"></i> <em> Details on Landlord incentives and special offers </em></li>

<li> <i class="icon-ok"></i> <em> No obligation advice from local broker specialist </em></li>

</ul>		 

<p>&nbsp;</p>

	    			</div>

	    			<!-- End Product Summary & Options -->

						<div class="clear"></div>

						<p>&nbsp;</p>

						<div class="row-fluid">

<?

$city_sql = "select * from city_descriptions where CityName ='".trim($q[0])."' AND StateProvCode = '".$statecode."'";



$city_result = $mysqli->query($city_sql);

	

$city_count = $city_result->num_rows;

	

if ($city_count > 0)

{

 	$city_row = mysqli_fetch_array($city_result);

 	

// 	if ($city_row['CityDesc']) echo "<h3>".trim($q[0])." Warehouse Market</h3>".$city_row['CityDesc']."<br />"; 

 	$sql_prop_city_count_rent = "select SpaceAvailable, RentalRateMin, created_from from warehouse_listing where (CityName = '".trim(str_replace("-", " ", $search))."' OR StateProvCode = '".trim($search)."')".($q[1] ? "and (PostalCode = '".trim($q[1])."' OR StateProvCode = '".trim($q[1])."') " : "")."AND ListingIsActive = 'y' and created_from !='json'";



	$listing_result_rent = $mysqli->query($sql_prop_city_count_rent);

	$num_rows_rent = $listing_result_rent->num_rows;



 	$sql_prop_city_count_sale = "select SpaceAvailable, RentalRateMin, created_from from warehouse_listing where (CityName like '%".trim(str_replace("-", " ", $search))."' OR StateProvCode like '%".trim($search)."')".($q[1] ? "and (PostalCode = '".trim($q[1])."' OR StateProvCode = '".trim($q[1])."') " : "")."AND ListingIsActive = 'y' and created_from = 'json'";

	$listing_result_sale = $mysqli->query($sql_prop_city_count_sale);

	$num_rows_sale = $listing_result_sale->num_rows;



$desc = "

".trim($q[0])." currently features warehouses with (".$num_rows_rent.") for rent and (".$num_rows_sale.") for sale. 

Browse through all of the available listings for industrial space and our agents will be happy to send detailed 

property reports on any building you would like to review. Our ".trim($q[0])." commercial real estate 

office handles both lease and purchase transactions with complimentary advisory services to 

review agreements, leverage opportunities and schedule tours of available properties.";



// 	if ($city_row['CityDesc']) echo "<h3>".trim($q[0])." Warehouse Market</h3>".$city_row['CityDesc']."<br />"; 

 	if ($city_row['CityDesc']) echo "<h3>".trim($q[0])." Warehouse Market</h3>".$desc."<br />"; 

}	

	

$city_sql = "select distinct cityname, StateProvCode, created_from from warehouse_listing where ListingIsActive = 'y' AND trim(cityname) <> '' and StateProvCode = '".trim($q[0])."' order by cityname";



$city_result = $mysqli->query($city_sql);

	

$city_count = $city_result->num_rows;

$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri_segments = explode('/', $uri_path);



if ($city_count > 0)

{

 	echo "<h3>Cities Covered in ".strtoupper(trim($q[0]))."</h3>";

	 	

 	while ($city_row = mysqli_fetch_array($city_result))

		if($uri_segments[1] == 'warehouse-for-sale')

		{ 		

 		echo "<a href=\"/warehouse-for-sale/United-States/".$city_row['StateProvCode']."/".trim($city_row['cityname'])."\">".trim($city_row['cityname'])."</a><br />"; 

 		}

 		else

 		{

 		echo "<a href=\"/warehouse-for-rent/United-States/".$city_row['StateProvCode']."/".trim($city_row['cityname'])."\">".trim($city_row['cityname'])."</a><br />"; 



 		}

 	

 	echo "<br />";

} 



 	$min_rate = 0;

 	$max_rate = 0;

 	$min_sqft = 0;

 	$max_sqft = 0;

 	$total_sqft = 0;

 	$total_rate = 0;

 	$counter  = 0;

 	$counter_sale  = 0;

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



 				if(!empty($states[$q[0]]))

				{

					$search = $states[$q[0]];

				}

				else

				{

					$search = $q[0];

				}



//$sql_prop = "select SpaceAvailable, RentalRateMin from listings where (CityName = '".trim(str_replace("-", " ", $search)).

//	$sql_prop = "select SpaceAvailable, RentalRateMin from warehouse_listing where CityName = '".trim($q[0])."' AND ListingIsActive = 'y'";

$sql_prop = "select SpaceAvailable, RentalRateMin, created_from from warehouse_listing where (CityName = '".trim(str_replace("-", " ", $search))."' OR StateProvCode = '".trim($search)."')".($q[1] ? "and (PostalCode = '".trim($q[1])."' OR StateProvCode = '".trim($q[1])."') " : "")."AND ListingIsActive = 'y'";



	try{

		$listing_result = $mysqli->query($sql_prop);

	}

	catch(exception $e) { var_dump($e); }

		

	$num_rows = $listing_result->num_rows;



	while ($listing_row = mysqli_fetch_array($listing_result)) 

	{

		$int_sqft = intval(preg_replace("/[^0-9\.]/", "", $listing_row['SpaceAvailable']));

	 	

		if ($listing_row['RentalRateMin'] == "Negotiable") $int_rate = 0;

		else

		{

	 		if (strpos($listing_row['RentalRateMin'], 'Year') !== false)

			{

				if (strpos($listing_row['RentalRateMin'], '/SF/Year')) $int_rate = floatval(floatval(substr($listing_row['RentalRateMin'], 1)) / 12);

				else $int_rate = floatval(floatval(substr($listing_row['RentalRateMin'], 1)/12) / $int_sqft);

			}

			elseif (strpos($listing_row['RentalRateMin'], 'SF') !== false) 

				$int_rate = floatval(floatval(substr($listing_row['RentalRateMin'], 1)));

			else

				$int_rate = floatval(floatval(substr($listing_row['RentalRateMin'], 1)) / $int_sqft);

		}

		

		$total_sqft += $int_sqft; 

		$total_rate += $int_rate;



	 	if (!$min_sqft || $int_sqft < $min_sqft) $min_sqft = $int_sqft;

	 	if (!$max_sqft || $int_sqft > $max_sqft) $max_sqft = $int_sqft;

	 	

 		if ((!$min_rate || $int_rate < $min_rate) && $int_rate > 0) $min_rate = $int_rate;

 		if ((!$max_rate || $int_rate > $max_rate) && $int_rate > 0) $max_rate = $int_rate;

	 		

 		if ($int_rate) $counter++;

	}





	while ($listing_row_sale = mysqli_fetch_array($listing_result_sale)) 

	{

		$int_sqft_sale = intval(preg_replace("/[^0-9\.]/", "", $listing_row_sale['SpaceAvailable']));

	 	

		if ($listing_row_sale['RentalRateMin'] == "Negotiable") $int_rate_sale = 0;

		else

		{

	 		if (strpos($listing_row_sale['RentalRateMin'], 'Year') !== false)

			{

				if (strpos($listing_row_sale['RentalRateMin'], '/SF/Year')) $int_rate_sale = floatval(floatval(substr($listing_row_sale['RentalRateMin'], 1)) / 12);

				else $int_rate_sale = floatval(floatval(substr($listing_row_sale['RentalRateMin'], 1)/12) / $int_sqft_sale);

			}

			elseif (strpos($listing_row_sale['RentalRateMin'], 'SF') !== false) 

				$int_rate_sale = floatval(floatval(substr($listing_row_sale['RentalRateMin'], 1)));

			else

				$int_rate_sale = floatval(floatval(substr($listing_row_sale['RentalRateMin'], 1)) / $int_sqft_sale);

		}

		

		$total_sqft_sale += $int_sqft_sale; 

		$total_rate_sale += $int_rate_sale;



	 	if (!$min_sqft_sale || $int_sqft_sale < $min_sqft_sale) $min_sqft_sale = $int_sqft_sale;

	 	if (!$max_sqft_sale || $int_sqft_sale > $max_sqft_sale) $max_sqft_sale = $int_sqft_sale;

	 	

 		if ((!$min_rate_sale || $int_rate_sale < $min_rate_sale) && $int_rate_sale > 0) $min_rate_sale = $int_rate_sale;

 		if ((!$max_rate_sale || $int_rate_sale > $max_rate_sale) && $int_rate_sale > 0) $max_rate_sale = $int_rate_sale;

	 		

 		if ($int_rate_sale) $counter_sale++;

	}





	$total_sqft_aall  = ($total_sqft + $total_sqft_sale);

	$total_rate_all = ($total_rate + $total_rate_sale);

if ($total_sqft_aall > 0)

{

echo "<h3>".trim($q[0])." Warehouse Stats</h3>";

echo "Total available listings: ". ($num_rows + $num_rows_sale)."<br />Total square footage: ".number_format($total_sqft_aall)." SF";



echo "<br><br>" .  "Total for rent : " .$num_rows .  "<br />Average asking rate: \$".number_format($total_rate/$counter, 2)." sf/mo<br />Average size available: ".number_format($total_sqft / $num_rows)." SF<br />Price range: \$".number_format($min_rate, 2)." sf/mo to \$".number_format($max_rate, 2)." sf/mo<br /><br />";



echo   "Total for sale : " .$num_rows_sale .  "<br />Average asking rate: Negotiable<br />Average size available: ".number_format($total_sqft_sale / $num_rows_sale)." SF<br />Price range: Negotiable <br /><br />";





echo "<h3>Available Layouts</h3>";



	$sql_prop = "select distinct PropertySubType, count(*) AS num from warehouse_listing where CityName = '".trim($q[0])."' AND ListingIsActive = 'y' GROUP BY PropertySubType";



	try{

		$listing_result = $mysqli->query($sql_prop);

	}

	catch(exception $e) { var_dump($e); }



	while ($listing_row = mysqli_fetch_array($listing_result)) echo $listing_row['PropertySubType'].": ".$listing_row['num']." Listing".($listing_row['num'] > 1 ? "s" : "")."<br />";

}

?>

						</div>

					</div>

					<!-- End Sidebar -->

					<div class="col-sm-8">

<!--        

    //////////////////////////////////////////////////////////////////////////////////

    // Insert the generated results table in this div                               //

    //////////////////////////////////////////////////////////////////////////////////

 -->

                <? print $sidebar_content; ?>

					</div>

				</div>

				<div class="pagination-wrapper">

					<ul class="pagination pagination-lg">

<?php



if($_GET['rent_property'] == 'all') {

if ($rec_count > 0 && $num_of_pages > 1)

{

	$new_query_str = preg_replace('/&page=\d*/', '', $_SERVER['QUERY_STRING']);

	$new_query_str = '';

	

	$last = $page - 1;

	$next = $page + 1;



	echo ($page == 1 ? "" : "<li><a href=\"/warehouse-for-rent/United-States/$statecode/".trim($cityname).(($last != 0 && $last != 1) ? "/page$last?rent_property=all" : "").($new_query_str ? "/?".$new_query_str : "")."\">Previous</a></li>");



	for ($i = 1; $i <= $num_of_pages; $i++)

	{

		if ($page == $i) echo "<li class=\"active\"><span>".$i."</span></li>";

		else echo "<li><a href=\"/warehouse-for-rent/United-States/$statecode/".trim($cityname).($i != 1 ? "/page$i?rent_property=all" : "").($new_query_str ? "/?".$new_query_str : "")."\">$i</a></li>";

	}

	

	echo ($page == $num_of_pages ? "" : "<li><a href=\"/warehouse-for-rent/United-States/$statecode/".trim($cityname)."/page$next?rent_property=all".($new_query_str ? "/?".$new_query_str : "")."\">Next</a></li>");

}



} else if($_GET['sell_property'] == 'all'){

if ($rec_count > 0 && $num_of_pages > 1)

{

	$new_query_str = preg_replace('/&page=\d*/', '', $_SERVER['QUERY_STRING']);

	$new_query_str = '';

	

	$last = $page - 1;

	$next = $page + 1;



	echo ($page == 1 ? "" : "<li><a href=\"/warehouse-for-sale/United-States/$statecode/".trim($cityname).(($last != 0 && $last != 1) ? "/page$last?sell_property=all" : "").($new_query_str ? "/?".$new_query_str : "")."\">Previous</a></li>");



	for ($i = 1; $i <= $num_of_pages; $i++)

	{

		if ($page == $i) echo "<li class=\"active\"><span>".$i."</span></li>";

		else echo "<li><a href=\"/warehouse-for-sale/United-States/$statecode/".trim($cityname).($i != 1 ? "/page$i?sell_property=all" : "").($new_query_str ? "/?".$new_query_str : "")."\">$i</a></li>";

	}

	

	echo ($page == $num_of_pages ? "" : "<li><a href=\"/warehouse-for-sale/United-States/$statecode/".trim($cityname)."/page$next?sell_property=all".($new_query_str ? "/?".$new_query_str : "")."\">Next</a></li>");

}



}

else

{

if ($rec_count > 0 && $num_of_pages > 1)

{

//	$new_query_str = preg_replace('/&page=\d*/', '', $_SERVER['QUERY_STRING']);

/*	$new_query_str = '';

	

	$last = $page - 1;

	$next = $page + 1;



	echo ($page == 1 ? "" : "<li><a href=\"/warehouses/United-States/$statecode/".trim($cityname).(($last != 0 && $last != 1) ? "/page$last" : "").($new_query_str ? "/?".$new_query_str : "")."\">Previous</a></li>");



	for ($i = 1; $i <= $num_of_pages; $i++)

	{

		if ($page == $i) echo "<li class=\"active\"><span>".$i."</span></li>";

		else echo "<li><a href=\"/warehouses/United-States/$statecode/".trim($cityname).($i != 1 ? "/page$i" : "").($new_query_str ? "/?".$new_query_str : "")."\">$i</a></li>";

	}

	

	echo ($page == $num_of_pages ? "" : "<li><a href=\"/warehouses/United-States/$statecode/".trim($cityname)."/page$next".($new_query_str ? "/?".$new_query_str : "")."\">Next</a></li>");

*/

}

}

?>

					</ul>

				</div>

			</div>

	    </div>

<div class="row-fluid" >	

    <div class="span10 offset1" id="canvas2-body">

        <div class="row-fluid" id="canvas2"></div>	

    </div>

</div>



<script type="text/javascript">

$("#submit-contact-form").click(function () {

	if(!$("#contact-form").valid()) return false;

	else 

	$.ajax({

				type: 'POST',

				  data: $("#contact-form").serialize(),

				  success: function() { 

				   			window.location = "https://warehousespaces.com/thank-you.php";

				   			//alert("Thank you for submitting your information!");

							//$("#contact-form").reset();

							//resetForm('contact-form');

					},

				  error: function(){

					request.abort();

					alert("error");

					

				  },

				  url: '/process_contact_form.php',

				  cache:false

			});	

	});		

		function resetForm(id) {

			$('#'+id).each(function(){

					this.reset();

			});

		}

   

    var infoList = [];  // Array to store the popup infowindows content

	 

	 var map; //var used to store the map generated using google maps API

    

    

//////////////////////////////////////////////////////////////////////////////////

//  js function to initialise google map                                        //

//////////////////////////////////////////////////////////////////////////////////



      function initialize() {

        var mapOptions = {

          center: <?=$firstLatLng?>,

          zoom: 10,

          mapTypeId: google.maps.MapTypeId.ROADMAP,

		 

        };

        map = new google.maps.Map(document.getElementById("map-canvas"),  mapOptions);

		

          

/////////////////////////////////////////////////////////////////////////////////////

// Once the map is generated add the markers created in the php loop for resultset //

/////////////////////////////////////////////////////////////////////////////////////



		<? print $markers_script; ?> 

          



//////////////////////////////////////////////////////////////////////////////////

//Create the map control to expand and reset map size                           //

//////////////////////////////////////////////////////////////////////////////////

          

		var controlDiv = document.createElement('div');

		var myControl = new ResizeControl(controlDiv,map);

		controlDiv.index =0;

		controlDiv.id = "mapExpand";

		controlDiv.style.zIndex = 1;

		controlDiv.style.right = 0;

		map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(controlDiv);

		}

     // google.maps.event.addDomListener(window, 'load', initialize);

        

         $(".gmnoprint").hide();

					

	function ResizeControl(controlDiv, map) {

	  

    // Set CSS styles for the DIV containing the control

    // Set CSS for the control border

  var controlUI = document.createElement('div');  

  controlUI.style.width = '32px';

  controlUI.style.height = '32px';

  controlUI.style.cursor = 'pointer';

  controlUI.style.textAlign = 'center';

  controlUI.title = 'Click to Expand the map';

  controlDiv.appendChild(controlUI);



	  google.maps.event.addDomListener(controlUI, 'click', function() {

		if($("#small-map").hasClass("span4"))	

            {

                $("#small-map").removeClass("span4").addClass("span12");

                $("#map_canvas").addClass("span12");

                $("#filter-form").addClass("span4").appendTo("#canvas2");

                $("#results-div").appendTo("#canvas2");

                $("#mapExpand").css({backgroundPosition: '0px -76px'});

                

                google.maps.event.trigger(map, 'resize');

                map.fitBounds();

            }

		else {

                $("#small-map").removeClass("span12").addClass("span4");

                $("#results-div").removeClass("span4").appendTo("#canvas1");

                $("#filter-form").removeClass("span4").appendTo("#small-map");

                $("#mapExpand").css({backgroundPosition: '0px 0px'});

                google.maps.event.trigger(map, 'resize');

                map.fitBounds();

    		}

		

	  });



    //add google events and triggers to move the controlUI div to the right bottom corner

    google.maps.event.addListener(map, 'bounds_changed', function(){

        $("#mapExpand").css({right:0}).zIndex(1000002);

    });

    google.maps.event.addListener(map, 'idle', function(){

        $("#mapExpand").css({right:0}).zIndex(1000002);

    });

   

}

      

         

$(document).ready(function(){



    //initialize google map on document load;

    initialize();

    

    // bind the button click in filter form to  cal lthe filter_results function

    

    $('#filter-results').on('click', function (e) {

         filter_results();

    });



    $("#contact-form").validate({

			rules: {

                contact_name: {           //input name: fullName

                    required: true,   //required boolean: true/false

                    minlength: 5,       

                },

                contact_email: {              //input name: email

                    required: true,   //required boolean: true/false

                    email: true       //required boolean: true/false

                },

                contact_phone: {

                    required: true,   //required boolean: true/false

                  //   phoneUS: true

                },

                 contact_company: {

                    required: true,   //required boolean: true/false

                  },

                 contact_sq_ft: {

                    required: true,   //required boolean: true/false

                  },

                contact_message: {

                    required: true

                }

            },

            messages: {               //messages to appear on error

                contact_name: {

                      required:"Please put your full name.",

                      minlength:"Full name please."

                      },

                contact_email: "Enter a valid email.",

                contact_phone: {

                      required: "Enter a valid Phone Number",

					//  phoneUS: "Please ented a Valid Phone Number"

                      },

                 contact_company: {

                    required: "Enter your company name"

                 },

                 contact_sq_ft: {

                    required: "Please enter square footage"

                 },

                contact_message: {

                    required: "Please add your comments"

                }

            },

			showErrors: function(errorMap, errorList) {

				$.each(this.successList, function(index, value) {

				  return $(value).popover("hide");

				});

				return $.each(errorList, function(index, value) {

				  var _popover;

				  _popover = $(value.element).popover({

					trigger: "manual",

					placement: "right",

					content: value.message,

					template: "<div class=\"popover\"><div class=\"arrow\"></div><div class=\"popover-inner\"><div class=\"popover-content\"><p></p></div></div></div>"

				  });

				  _popover.data("popover").options.content = value.message;

				  return $(value.element).popover("show");

				});

			  },

			submitHandler: function(form) {                 

				$(form).ajaxSubmit();

            }



        });



});   





//////////////////////////////////////////////////////////////////////////////

// this routine will close an open infowindow when a new point is clicked   //

// maps V.3 does not automatically do this for you anymore so we do it here //

//////////////////////////////////////////////////////////////////////////////    

function infoClose() {

    if (! infoList.length) { return; }

    for (i in infoList) {

       infoList[i].infoWindow.close();

    }



infoList = [];



} // infoClose     

         

//////////////////////////////////////////////////////////////////////////////

// function to filter the results based on teh form criteria.               //

// this routine hides or shows the relevant rows                            //    

//////////////////////////////////////////////////////////////////////////////

    

function filter_results(){

    var price_arr = $("#price_range").val().split("-");

    var size_arr = $("#size_range").val().split("-");

    

    var min_rent =  parseInt(price_arr[0]);

    var max_rent = (price_arr[1])? price_arr[1] : ""; 

    var min_area = parseInt(size_arr[0]);

    var max_area = (size_arr[1])?size_arr[1]:"";

    var prop_type = $("#prop-type").val();

    var show_all_flag = 1;

    var filtered_list = new Array();



// filter out the records that have min_area within min_space and max_space

	if (max_area != "" || min_area>0) 

    { 

        // if this flag is set to 0 do not show all rows but only those filtered out

        show_all_flag = 0;

        $(".space-total").each(function(){

			if (parseInt($(this).html().replace(/[^\d.]/g, "")) > min_area && ((max_area == "") ? true : parseInt($(this).html().replace(/[^\d.]/g, "")) <= max_area))

				filtered_list.push($(this).closest("div"));

        });                    

 	}

                         

//filter out the records with rent lower than min rent or more than max_rents        



// if max_rent > 0 then set the flag to NOT show all records

	if (max_rent !="" || min_rent > 0)

	{ 

    	show_all_flag = 0;

    	if (filtered_list.length === 0)

        {

            $(".top_rent").each(function(){

                if (parseInt($(this).html().replace(/[^\d.]/g, "")) > min_rent && ((max_rent == "")?true:parseInt($(this).html().replace(/[^\d.]/g, "")) <= max_rent))

                	filtered_list.push($(this).closest("div"));

            });

        }

     

    // Now check the filtered list for min_rent condition

    

    for (var i=0; i<filtered_list.length; i++)

    {

		if (filtered_list[i].find(".top_rent").html().replace(/[^\d.]/g, "") <= min_rent || filtered_list[i].find(".top_rent").html().replace(/[^\d.]/g, "") >= max_rent)

			filtered_list.splice(i,1);

        }

	}

        

// filter by the property type selected

    if (prop_type !="") {

        show_all_flag =0;

//if filtered_list not created already, create one now

        

		if (filtered_list.length === 0)

		{

			$(".prop-type-td").each(function(){

       // if($(this).html().slice($(this).html().indexOf("<br>")+4,$(this).html().length) == prop_type) 

	    	if ($(this).html().split("<br>")[1] == prop_type) 

            	filtered_list.push($(this).closest("div"));

        	});

        }

        

 //  if filtered_list already created, loop through it to match property type

    else

   {     

     for (var i=0;i<filtered_list.length;i++)

    {

       // if(filtered_list[i].find(".prop-type-td").html().slice(filtered_list[i].find(".prop-type-td").html().indexOf("<br>")+4,$(this).html().length) != prop_type)

			if(filtered_list[i].find(".prop-type-td").html().split("<br>")[1] != prop_type)

            {

				filtered_list.splice(i,1);

				i--;

			}

    }    

    

   }

}

                       

//now show only the ones left in the list                       

  if(filtered_list.length > 0)

    {

        var filtered_id_list = new Array();

        for (var i=0;i<filtered_list.length;i++) {

            filtered_id_list.push(filtered_list[i].attr("id"));

        }

        

        $(".top_rent").each(function(){

           if(filtered_id_list.indexOf($(this).closest("div").attr("id")) >= 0)

                $(this).closest("div").show();

            else $(this).closest("div").hide();

        });

    }                    

    else {

        if(show_all_flag === 0)

         $(".top_rent").closest("div").hide();

        else 

        $(".top_rent").closest("div").show();

    }

      

}

</script>

<? 

$mysqli->close();

include 'footer.php'; 

?>