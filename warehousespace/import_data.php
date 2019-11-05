<?php
$file_path = dirname(__FILE__);
// echo $file_path;

$string = file_get_contents('D:/ColdFusion10/cfusion/wwwroot/InsurancePlans/syn_report.html');
if(preg_match_all('/\[\{\"Address(.*?)\"\}\]/',$string,$match)) {  
	
		$json_arr = utf8_encode('[{"Address'.$match[1][0].'"}]');
		// echo json_encode($json_arr, JSON_PRETTY_PRINT);
		//echo json_encode($string, JSON_PRETTY_PRINT);
		// var_dump($json_arr);
		$property_arr = json_decode($json_arr, true);
		//var_dump($property_arr);
		//$sql_stmt = "insert into property_list()";
		$sql_stmt_values = "";
		
		foreach($property_arr as $item => $curr_arr) {
			var_dump($curr_arr);
			if (array_key_exists('Address', $curr_arr)) {
				$streetaddress = $curr_arr['Address']['StreetAddress'];
				$cityname = $curr_arr['Address']['CityName'];
				$stateprovcode = $curr_arr['Address']['StateProvCode'];
				$stateprovname = $curr_arr['Address']['StateProvName'];
				$countrycode = $curr_arr['Address']['CountryCode'];
				$postalcode = $curr_arr['Address']['PostalCode'];
				$latitude = $curr_arr['Address']['Geopoint']['Latitude'];
				$longitude =$curr_arr['Address']['Geopoint']['Longitude'];
			if (array_key_exists('Photo', $curr_arr)) {
				$photoid = $curr_arr['Photo']['Id'];
				$photoext = $curr_arr['Photo']['Ext'];
			}
			else {$photoid =""; $photoext ="";}
				$propertytype = $curr_arr['PropertyType'];
				$propertysubtype = $curr_arr['PropertySubtype'];
			if (array_key_exists('zoning',	$curr_arr)) {
				$zoning = $curr_arr['Zoning'];
				}
			else {$zoning = "";}	
			if (array_key_exists('BuildingSize',	$curr_arr)) {
				$buildingsize =  str_replace("SF","",str_replace(",","",substr($curr_arr['BuildingSize'],0, strrpos($curr_arr['BuildingSize'], " "))));
				//$curr_arr['BuildingSize'];
				}
			else{$buildingsize ="";}
			
			if (array_key_exists('LotSize',	$curr_arr)) {
				$lotsize =  str_replace(",","",substr($curr_arr['LotSize'],0, strrpos($curr_arr['LotSize'], " ")));
				}
			else {$lotsize = "";}
			
			if (array_key_exists('YearBuilt',	$curr_arr)) {
				$YearBuilt = $curr_arr['YearBuilt'];
				}
			else {$YearBuilt = "";}
			
			if (array_key_exists('PropertyDescription',	$curr_arr)) {
				$PropertyDescription = $curr_arr['PropertyDescription'];
				}
			else{$PropertyDescription = "";}
			
			$PropertyDescription = str_replace("'","\'", $PropertyDescription);
			
			if (array_key_exists('LocationDescription',	$curr_arr)) {
				$LocationDescription = $curr_arr['LocationDescription'];
				}
			else {$LocationDescription ="";}
			
			$LocationDescription = str_replace("'","\'", $LocationDescription);
				
			if (array_key_exists('RentalRateRange',	$curr_arr)) {
				$RentalRateRange = $curr_arr['RentalRateRange'];
				if(strpos("-", $RentalRateRange === false)){
					$RentalRateMin =  str_replace("$","",substr($RentalRateRange,1, strpos($RentalRateRange, " ")));
					$RentalRateMax = "";
				}
				else {
					$RentalRateMin =  str_replace("$","",substr($RentalRateRange,1, strpos($RentalRateRange, " ")));
					$RentalRateMax =  str_replace("$","",substr($RentalRateRange,strpos($RentalRateRange, "- "), strpos($RentalRateRange, " ")));
					}
				}
			else {$RentalMin = ""; $RentalMax = "";}
			if (array_key_exists('SpaceCount',	$curr_arr)) {
				$SpaceAvailableRange = $curr_arr['SpaceAvailableRange'];
				if(strpos("-", $SpaceAvailableRange === false)){
					$SpaceAvailableMin = substr($SpaceAvailableRange,1, strpos($SpaceAvailableRange, " "));
					$SpaceAvailableMax = $SpaceAvailableMin;
				}
				else {
					$SpaceAvailableMin = substr($SpaceAvailableRange,1, strpos($SpaceAvailableRange, " "));
					$SpaceAvailableMax = substr($SpaceAvailableRange,strpos($SpaceAvailableRange, "- ")+2, strpos($SpaceAvailableRange, " "));
					}
				}
			else {
				$SpaceAvailableMin = "";
				$SpaceAvailableMax = "";
				}	
			$SpaceAvailableMin = str_replace(",","",$SpaceAvailableMin);
			$SpaceAvailableMax = str_replace(",","",$SpaceAvailableMax);
				
			if (array_key_exists('SpaceCount',	$curr_arr)) {
			$SpaceCount =  str_replace("Space","",$curr_arr['SpaceCount']);
			}
			else $SpaceCount = "";	
				
			if (array_key_exists('apn',	$curr_arr)) {
				$apn = $curr_arr['Apn'];
				}
			else $apn = "";	
			}
					
			if (array_key_exists('Zoning', $curr_arr)) {
					$zoning = $curr_arr['Zoning'];
				}
							
		$sql_stmt_values.= "('".$countrycode."','".$stateprovname."','".$stateprovcode."','".$cityname."','".$postalcode."','".$streetaddress."','".$latitude."','".$longitude."','".$photoid."','".$photoext."','".$propertytype."','".$propertysubtype."','".$zoning."','".$buildingsize."','".$lotsize."','".$YearBuilt."','".$PropertyDescription."','".$LocationDescription."','".$RentalRateMin."','".$RentalRateMax."','".$SpaceAvailableMin."','".$SpaceAvailableMax."','".$SpaceCount."','".$apn."'),";
		
		}
		// echo($sql_stmt_values."<br>");
	}

	switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
			$qry_stmt = "insert into warehouse_listing (CountryCode, StateProvName, StateProvCode, CityName, PostalCode, StreetAddress, Latitude, Longitude, PhotoID, PhotoExt, PropertyType, PropertySubtype, Zoning, BuildingSize, LotSize, YearBuilt, PropertyDescription, LocationDescription, RentalRateMin,RentalRateMax, SpaceAvailableMin, SpaceAvailableMax, SpaceCount, Apn) values".substr_replace($sql_stmt_values ,"",-1);
			
			try{
			$mysqli = mysqli_connect("localhost", "root", "amar", "largewarehouse_com");
			$mysqli->query($qry_stmt);
			printf("Affected rows (INSERT): %d\n", $mysqli->affected_rows);
			}
			catch(exception $e) { var_dump($e);}
			$mysqli->close();
			//$row = $result->fetch_assoc();
			//echo htmlentities($row['_message']);
			 echo($qry_stmt);
			//"values=(".sql_stmt_values.")";
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }
?>

