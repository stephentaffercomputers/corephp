<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

$file_path = dirname(__FILE__);

	$string = file_get_contents('/home/warehous/public_html/ws/san_diego_over_10k_053113.txt');
		$utf_String = utf8_encode($string);
		$property_arr = json_decode($utf_String, true);
		//var_dump($property_arr);
		//$sql_stmt = "insert into property_list()";
		$sql_stmt_values = "";
		
			
			
		foreach($property_arr as $item => $curr_arr) {
			//var_dump($curr_arr);
			$apn = "";
			$lotsize ="";
			$YearBuilt ="";
			$propertyType="";
			$PropertyDescription = "";
			$LocationDescription="";
			$zoning ="";
			$SpaceCount=1;
			$propertysubtype="";
			$photoid =""; 
			$photoext ="";
            $photourl ="";
			$SpaceAvailableMin = "";
			$SpaceAvailableMax = "";
			$RentalRateMin = "";
			$RentalRateMax ="";
            $suiteno ="";
            $availdate = "";
            $SpaceAvailableTotal ="";
			
			if (array_key_exists('Address', $curr_arr)) {
				$streetaddress = $curr_arr['Address']['StreetAddress'];
				$cityname = $curr_arr['Address']['CityName'];
				$stateprovcode = $curr_arr['Address']['StateProvCode'];
				$stateprovname = $curr_arr['Address']['StateProvName'];
				$countrycode = $curr_arr['Address']['CountryCode'];
				$postalcode = $curr_arr['Address']['PostalCode'];
			
				if (array_key_exists('Geopoint', $curr_arr['Address'])){
					$latitude = $curr_arr['Address']['Geopoint']['Latitude'];
					$longitude =$curr_arr['Address']['Geopoint']['Longitude'];
				}
				else {
					$latitude =""; 
					$longitude = "";
				}
			}
			if (array_key_exists('Photo', $curr_arr)) {
				$photoid = $curr_arr['Photo']['Id'];
				$photoext = $curr_arr['Photo']['Ext'];
                $photourl = "http://images.loopnet.com/xnet/mainsite/HttpHandlers/attachment/ServeAttachment.ashx?FileGuid=".$photoid.".".$photoext;
			}
			//else {$photoid =""; $photoext ="";}
// var_dump($photourl);
			if (array_key_exists("PropertyDescription", $curr_arr))
				{
					$PropertyDescription= $curr_arr['PropertyDescription'];
					$PropertyDescription = str_replace("'","\'", $PropertyDescription);
				}
			
			if (array_key_exists('LocationDescription',	$curr_arr)) {
					$LocationDescription = $curr_arr['LocationDescription'];
					$LocationDescription = str_replace("'","\'", $LocationDescription);					
			}
			
			
			if (array_key_exists('Broker', $curr_arr)) {
//var_dump($curr_arr['Broker']);
				if (array_key_exists('Name', $curr_arr['Broker'])) {$BrokerName = $curr_arr['Broker']['Name'];}
				else $BrokerName = "";
				if (array_key_exists('Email', $curr_arr['Broker'])) {$BrokerEmail = $curr_arr['Broker']['Email'];}
				else $BrokerEmail = "";
				if (array_key_exists('CompanyName', $curr_arr['Broker'])) {$BrokerCompany = $curr_arr['Broker']['CompanyName'];}
				else $BrokerCompany = "";
				if (array_key_exists('Phone', $curr_arr['Broker'])) {$BrokerPhone = $curr_arr['Broker']['Phone'];}
				else $BrokerPhone="";
				if (array_key_exists('LicenseNumber', $curr_arr['Broker'])) {$BrokerLicNumber = $curr_arr['Broker']['LicenseNumber'];}
				else $BrokerLicNumber="";
				if ( array_key_exists('Logo', $curr_arr['Broker']) && array_key_exists('Id', $curr_arr['Broker']['Logo'])) {$BrokerLogo = $curr_arr['Broker']['Logo']['Id'];}
				else $BrokerLogo = "";
				if (array_key_exists('Logo', $curr_arr['Broker']) && array_key_exists('Ext', $curr_arr['Broker']['Logo'])) {$BrokerLogoExt = $curr_arr['Broker']['Logo']['Ext'];}
				else $BrokerLogoExt = "";
				
			}
			
			if (array_key_exists('Details', $curr_arr)) {
				$Details = $curr_arr['Details'];
				foreach($Details as $item1 => $details_arr) {

				//echo ($details_arr['Name']);
				
				if ($details_arr['Name'] == "Space Available" )
						{	
							$SpaceAvailableRange = $details_arr['Value'][0];
								if(strpos("-", $SpaceAvailableRange,0) === false){
									$SpaceAvailableMin = substr($SpaceAvailableRange,1, strpos($SpaceAvailableRange, " "));
									$SpaceAvailableMax = $SpaceAvailableMin;
								}
								else {
									$SpaceAvailableMin = substr($SpaceAvailableRange,1, strpos($SpaceAvailableRange, " "));
									$SpaceAvailableMax = substr($SpaceAvailableRange,strpos($SpaceAvailableRange, "- ")+2, strpos($SpaceAvailableRange, " "));
									}
							
							$SpaceAvailableMin = preg_replace("/[^0-9]/","",$SpaceAvailableMin);	
							$SpaceAvailableMax = preg_replace("/[^0-9]/","",$SpaceAvailableMax);	
                            $paceAvailableTotal = $SpaceAvailableMax;
								
						}	
					else if ($details_arr['Name'] == "Rental Rate" )
						{ 
							//$RentalRateRange = $details_arr['Value'][0];
							$RentalRateMin = $details_arr['Value'][0];
							$RentalRateMax = $details_arr['Value'][0];
							
							
						}
					else if ($details_arr['Name'] == "Spaces" )
						{
							$SpaceCount = is_numeric($details_arr['Value'][0]) ? $details_arr['Value'][0] : 1;
						}
					else if ($details_arr['Name'] == "Building Size" )
						{
							$buildingsize = $details_arr['Value'][0];
						}
					else if ($details_arr['Name'] == "Property Sub-type" )
						{
							$propertytype= $details_arr['Value'][0];
						}
						
				}
			//	echo($RentalRateMax. "<br>");
			}
			else{ //echo("else part");
				if (array_key_exists('PropertyType', $curr_arr)) {
					$propertytype = $curr_arr['PropertyType'];
					$propertysubtype = $curr_arr['PropertySubtype'];
					}
				else {$propertytype = ""; $propertysubtype="";}	
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
					echo("rentalrange");
					$RentalRateRange = $curr_arr['RentalRateRange'];
					if(strpos("-", $RentalRateRange === false)){
						$RentalRateMin =  str_replace("$","",substr($RentalRateRange,1, strpos($RentalRateRange, " ")));
						$RentalRateMax = "";
					}
					else {
						$RentalRateMin =  str_replace("$","",substr($RentalRateRange,1, strpos($RentalRateRange, " ")));
						$RentalRateMax =  str_replace("$","",substr($RentalRateRange,strpos($RentalRateRange, "- "), strpos($RentalRateRange, " ")));
						}
						var_dump($RentalRateMin);
						var_dump($RentalRateMax);
					}
						
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
				/*else {
					$SpaceAvailableMin = "";
					$SpaceAvailableMax = "";
					}	
					*/
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
				
						
				if (array_key_exists('Zoning', $curr_arr)) {
						$zoning = $curr_arr['Zoning'];
					}
			}

			if (array_key_exists('Lease',	$curr_arr) ) {
				if(array_key_exists('Spaces',	$curr_arr['Lease'])){
                    
					$spaces_arr = $curr_arr['Lease']['Spaces'];
					foreach($spaces_arr as $item2 => $suites_arr) {
                    
                        if (array_key_exists('Number', $suites_arr)) {
                            $suiteno = $suites_arr['Number'];
                        }
                        
                        if (array_key_exists('SpaceAvailable', $suites_arr)) {
                            $SpaceAvailableTotal = $suites_arr['SpaceAvailable'];
                         }
                        
                        if (array_key_exists('MinDivisible', $suites_arr)) {
                            $SpaceAvailableMin = $suites_arr['MinDivisible'];
                         }
                        
                        if (array_key_exists('MaxContiguous', $suites_arr)) {
                            $SpaceAvailableMax = $suites_arr['MaxContiguous'];
                          }
                        
                        $SpaceAvailableMin = preg_replace("/[^0-9]/","",$SpaceAvailableMin);	
				        $SpaceAvailableMax = preg_replace("/[^0-9]/","",$SpaceAvailableMax);
                        $SpaceAvailableTotal = preg_replace("/[^0-9]/","",$SpaceAvailableTotal);
                        
                        if (array_key_exists('DateAvailable', $suites_arr)) {
                            $dateavail =  $suites_arr['DateAvailable'];
                          }
					
						$sql_stmt_values.= "('".$countrycode."','".$stateprovname."','".$stateprovcode."','".$cityname."','".$postalcode."','".$streetaddress."','".$latitude."','".$longitude."','".$photoid."','".$photoext."','".$propertytype."','".$propertysubtype."','".$zoning."','".$buildingsize."','".$lotsize."','".$YearBuilt."','".$PropertyDescription."','".$LocationDescription."','".$RentalRateMin."','".$RentalRateMax."','".$SpaceAvailableMin."','".$SpaceAvailableMax."','".$SpaceCount."','".$apn."','".$photourl."','".$BrokerName."','".$BrokerEmail."','".$BrokerCompany."','".$BrokerPhone."','".$BrokerLicNumber."','".$BrokerLogo."','".$BrokerLogoExt."','".$suiteno."','".$availdate."','".$SpaceAvailableTotal."'),";

//echo $sql_stmt_values;
                    }
					
				}	
			}
else{						
			//echo($RentalRateMax. "<br>");
		$sql_stmt_values.= "('".$countrycode."','".$stateprovname."','".$stateprovcode."','".$cityname."','".$postalcode."','".$streetaddress."','".$latitude."','".$longitude."','".$photoid."','".$photoext."','".$propertytype."','".$propertysubtype."','".$zoning."','".$buildingsize."','".$lotsize."','".$YearBuilt."','".$PropertyDescription."','".$LocationDescription."','".$RentalRateMin."','".$RentalRateMax."','".$SpaceAvailableMin."','".$SpaceAvailableMax."','".$SpaceCount."','".$apn."','".$photourl."','".$BrokerName."','".$BrokerEmail."','".$BrokerCompany."','".$BrokerPhone."','".$BrokerLicNumber."','".$BrokerLogo."','".$BrokerLogoExt."','".$suiteno."','".$availdate."','".$SpaceAvailableTotal."'),";
//		echo($sql_stmt_values."<br>");
	}
}
	//	 echo($sql_stmt_values."<br>");
	//echo substr($sql_stmt_values ,0,-1);

	switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
			$qry_stmt = "insert into warehouse_listing (CountryCode, StateProvName, StateProvCode, CityName, PostalCode, StreetAddress, Latitude, Longitude, PhotoID, PhotoExt, PropertyType, PropertySubtype, Zoning, BuildingSize, LotSize, YearBuilt, PropertyDescription, LocationDescription, RentalRateMin,RentalRateMax, SpaceAvailableMin, SpaceAvailableMax, SpaceCount, Apn, PhotoURL, BrokerName, BrokerEmail, BrokerCompany, BrokerPhone, BrokerLicNumber, BrokerLogo, BrokerLogoExt, suiteno, availdate, SpaceAvailableTotal) values".substr($sql_stmt_values, 0,-1);

//echo $qry_stmt;
			try{
//			$mysqli = mysqli_connect("localhost", "root", "amar", "largewarehouse_com");
			$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");
			$mysqli->query($qry_stmt);
			printf("Affected rows (INSERT): %d\n", $mysqli->affected_rows);
			}
			catch(exception $e) { var_dump($e);}
			$mysqli->close();
			//$row = $result->fetch_assoc();
			//echo htmlentities($row['_message']);
			// echo($qry_stmt);
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

