<?php
ini_set ('display_errors', 1);
header("Content-Type: text/plain");

// $servername = "localhost";
// $username = "root";
// $password = "";
// $database = "test";

// // Create connection
// $conn = new mysqli($servername, $username, $password,$database);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// } 


// $ch = curl_init();
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch,CURLOPT_URL,"https://www.loopnet.com/search/commercial-real-estate/ca/for-sale/?sk=b11a23aa790f561ba10ee956b24dcc73");
// curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
// curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
// $data = curl_exec($ch);
// curl_close($ch);
$data = file_get_contents("myText.txt");
$newdata = $data;

$dom = new DOMDocument('1.0');
@$dom->loadHTML($newdata);
$elm = @$dom->getElementById('placardSec');
$data1 = $dom->saveHTML($elm);
@$dom->loadHTML($data1);
$i = 0;

$tdvalue = array();
$tdsdata = array();
$allarr = array();
foreach($dom->getElementsByTagName('ul') as $node)
{
	//$node->parentNode->tagName; // parent node
	$array = $node->getElementsByTagName('li');
	foreach($array as $key => $val){
		/* Titel get START*/
			$nodevalue = $val->getElementsByTagName('h4')->item(0);
			$nodevalue3 = $val->getElementsByTagName('h6')->item(0);
			if(!empty($nodevalue)){
				$title1 = $nodevalue->nodeValue;
				$allarr[$i]['title'] = $title1;
			}
			if(!empty($nodevalue3)){
				$title2 = $nodevalue3->nodeValue;
				$allarr[$i]['title2'] = $title2;
			}
			
		/* Titel get END*/
		$nodevalue2 = $val->getElementsByTagName('a');
		
		if(!empty($nodevalue2)){
			$linksvalue1 = '';
			foreach($nodevalue2 as $key => $value){
				if(!empty($value)){
					$parentnode = $value->parentNode->tagName;
					if($parentnode == 'h4'){
						$linksvalue = $value->getAttribute('href');
						if($linksvalue1 != $linksvalue){
							$linksvalue1 = $linksvalue;
							$allarr[$i]['link'] = $linksvalue;
							$ch1 = curl_init();
							curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
							curl_setopt($ch1,CURLOPT_URL,$linksvalue);
							curl_setopt($ch1,CURLOPT_RETURNTRANSFER,1);
							curl_setopt($ch1, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
							$data2 = curl_exec($ch1);
							curl_close($ch1);
							/*$data2 = file_get_contents("newfile1.txt");
							$myfile = fopen("newfile1.txt", "w") or die("Unable to open file!");
							fwrite($myfile, $data2);
							fclose($myfile);*/
							$dom1 = new DOMDocument('1.0');
							@$dom1->loadHTML($data2);

							/* table data get START*/
								$xpath = new DomXPath($dom1);
								$nodeList = $xpath->query("//table[@class='property-data featured-grid']");
								$node = $nodeList->item(0);
								$data3 = @$dom1->saveHTML($node);
								@$dom1->loadHTML($data3);
								$nodevalue3 = $dom1->getElementsByTagName('table')->item(0);
								if(!empty($nodevalue3)){
									
									foreach($nodevalue3->getElementsByTagName('tr') as $key2 => $tr){
										$tds1 = $tr->getElementsByTagName('td')->item(0);
										$tds = $tr->getElementsByTagName('span')->item(0);
										if(!empty($tds1) && !empty($tds)){
											$tdvalue[trim($tds1->textContent)] = $tds->textContent;
										}
										$tds2 = $tr->getElementsByTagName('td')->item(2);
										$tds1 = $tr->getElementsByTagName('span')->item(1);
										if(!empty($tds2) && !empty($tds1)){
											$tdsdata[trim($tds2->textContent)] = $tds1->nodeValue;
										}
										$dom5 = new DOMDocument('1.0');
										@$dom5->loadHTML($data2);
										$xpath9 = new DomXPath($dom5);
										$nodeList9 = $xpath9->query("//div[@class='expandable-subtype-first']");
										$node9 = $nodeList9->item(0);
										$data9 = @$dom5->saveHTML($node9);
										@$dom5->loadHTML($data9);
										$contains = (bool) preg_match('/class="[^"]*\bexpandable-subtype-first\b[^"]*"/', $data9);
										if(!empty($contains) && $contains == '1'){
											$PropertyType = trim($node9->nodeValue);
										}
										
									}
								}
								$tabledata = array_merge($tdvalue,$tdsdata);
								$allarr[$i]['price'] = !empty($tabledata['Price']) ? $tabledata['Price'] : '';
								if(!empty($tabledata['Building Size'])){
									$BuildingSize = $tabledata['Building Size'];
									$sizedata = explode(' ', $BuildingSize);
									$allarr[$i]['BuildingSize'] = $sizedata[0];
									$allarr[$i]['squrefeet'] = $sizedata[1];
								}
								if(!empty($PropertyType)){
									$allarr[$i]['PropertyType'] = !empty($PropertyType) ? $PropertyType : '';
								}else{
									$allarr[$i]['PropertyType'] = !empty($tabledata['Property Type']) ? $tabledata['Property Type'] : '';
								}
								$allarr[$i]['PropertySubtype'] = !empty($tabledata['Property Sub-type']) ? $tabledata['Property Sub-type'] : '';
								if(!empty($tabledata['Rentable Building Area'])){
									$RentableBuildingArea = $tabledata['Rentable Building Area'];
									$sizedata1 = explode(' ', $RentableBuildingArea);
									$allarr[$i]['RentableBuildingArea'] = $sizedata1[0];
									$allarr[$i]['Rentablesqurefeet'] = $sizedata1[1];
								}
								$allarr[$i]['YearBuilt'] = !empty($tabledata['Year Built']) ? $tabledata['Year Built'] : '';
								if(!empty($tabledata['Rental Rate'])){
									$RentalRate = $tabledata['Rental Rate'];
									$sizedata2 = explode(' ', $RentalRate);
									$allarr[$i]['RentalRate'] = $sizedata2[0];
									$allarr[$i]['RentalRateyear'] = $sizedata2[1];
								}
								if(!empty($tabledata['Max. Contiguous'])){
									$MaxContiguous = $tabledata['Max. Contiguous'];
									$sizedata3 = explode(' ', $MaxContiguous);
									$allarr[$i]['MaxContiguous'] = $sizedata3[0];
									$allarr[$i]['MaxContiguousfeet'] = $sizedata3[1];
								}
							/* table data get END*/

							/* images get START*/
								$dom3 = new DOMDocument('1.0');
								@$dom3->loadHTML($data2);
								$xpath4 = new DomXPath($dom3);
								$nodeList4 = $xpath4->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'photo-maintain-ratio ')]");
								$node4 = $nodeList4->item(0);
								$data4 = @$dom3->saveHTML($node4);
								@$dom3->loadHTML($data4);
								$imagevalue = '';
								$nodevalue6 = $dom3->getElementsByTagName('img');
								
								foreach($nodevalue6 as $key3 =>$img){
									$imgvalue = $img->getAttribute('src');
									if($imagevalue != $imgvalue){
										$allarr[$i]['image'] = $imgvalue;
									}
								}
							/* images get END*/

							/*Description get START*/
								$dom4 = new DOMDocument('1.0');
								@$dom4->loadHTML($data2);
								$xpath5 = new DomXPath($dom4);
								$nodeList5 = $xpath5->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'description-text ')]");
								$node5 = $nodeList5->item(0);
								$data5 = @$dom4->saveHTML($node5);
								@$dom4->loadHTML($data5);
								if(!empty($node5)){
									$allarr[$i]['description'] = trim($node5->nodeValue);
								}
							/*Description get END*/

							/* Address get START */
							$dom2 = new DOMDocument('1.0');
							@$dom2->loadHTML($data2);
							$xpath7 = new DomXPath($dom2);
							$nodeList7 = $xpath7->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' profile-hero-info ')]");
							$node7 = $nodeList7->item(0);
							$data7 = @$dom2->saveHTML($node7);
							
							@$dom2->loadHTML($data7);
							$add1 = $dom2->getElementsByTagName('h1')->item(0);
							$add2 = $dom2->getElementsByTagName('h2')->item(0);
							if(!empty($add1) && !empty($add2)){
								$add1value = $add1->nodeValue;
								$add2value = $add2->nodeValue;
								$address1 = explode(' - ', $add1value);
								$addre1 = @$address1[0];

								$address2 = explode(' in ', $add2value);
								$addre2 = @$address2[1];
								$citydata = explode(',', $addre2);

								$allarr[$i]['address1'] = $addre1.' '. trim($citydata[0]).','.trim(@$citydata[1]);
								$allarr[$i]['city'] = trim($citydata[0]);
								$allarr[$i]['StateProvCode'] = trim(@$citydata[1]);

								$address = $allarr[$i]['address1']; // Address
								$apiKey = 'AIzaSyCNswMe5_HsaKaYjqmzPe-FP-19rBqafd4'; // Google maps now requires an API key.
								//Get JSON results from this request
								$geo = @file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key='.$apiKey);
								$geo = json_decode($geo, true); // Convert the JSON to an array

								if (isset($geo['status']) && ($geo['status'] == 'OK')) {
									$allarr[$i]['latitude'] = $geo['results'][0]['geometry']['location']['lat']; // Latitude
									$allarr[$i]['longitude'] = $geo['results'][0]['geometry']['location']['lng']; // Longitude
								}
							}
							/* Address get END */

							/* LIsting ID and last updated value START*/
							$dom4 = new DOMDocument('1.0');
							@$dom4->loadHTML($data2);
							$xpath5 = new DomXPath($dom4);
							$nodeList5 = $xpath5->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'property-timestamp ')]");
							$node5 = $nodeList5->item(0);
							$data5 = @$dom4->saveHTML($node5);
							@$dom4->loadHTML($data5);
							$ListingID = $dom4->getElementsByTagName('li')->item(0)->nodeValue;
							if(!empty($ListingID)){
								$ID = explode(":", $ListingID);
								$allarr[$i]['listingID'] = $ID[1];
							}
							$createddate = $dom4->getElementsByTagName('li')->item(1)->nodeValue;
							if(!empty($createddate)){
								$date = explode(":", $createddate);
								$allarr[$i]['createddate'] = $date[1];
							}
							/* LIsting ID and last updated value END*/
							/*Postal code START*/
							$dom5 = new DOMDocument('1.0');
							@$dom5->loadHTML($data2);
							$xpath6 = new DomXPath($dom5);
							$nodeList6 = $xpath6->query("//*[contains(concat(' ', normalize-space(@class), ' '), 'inline-block remove-comma-wrap')]");
							$node6 = $nodeList6->item(0);
							$data6 = @$dom5->saveHTML($node6);
							@$dom5->loadHTML($data6);
							$contains = (bool) preg_match('/class="[^"]*\binline-block remove-comma-wrap\b[^"]*"/', $data6);
							if($contains == '1'){
								$postalcode = $dom5->getElementsByTagName('span')->item(0)->nodeValue;
								if(!empty($postalcode)){
									$postal = explode(',', $postalcode);
									if(!empty($postal)){
										$postal1 = trim($postal[2]);
										$explodepostal = explode(' ', trim($postal[2]));
										$allarr[$i]['postalcode'] = $explodepostal[1];
									}
								}
							}
							/*Postal code END*/
						}
					}
				}
			}
		}
		$i++;
	}
}
/*echo "<pre>";
print_r($allarr);
echo "</pre>";
exit;*/

$units = array();
$i = 0;
foreach($allarr as $key4 => $alldata){
	$title2 = !empty($alldata['title2']) ? $alldata['title2'] : '';
	$title = !empty($alldata['title']) ? trim($alldata['title']).' '.trim($title2) : '';
	$listingID = !empty($alldata['listingID']) ? $alldata['listingID'] : '';
	$BuildingSize = !empty($alldata['BuildingSize']) ? $alldata['BuildingSize'] : '';
	$postalcode = !empty($alldata['postalcode']) ? $alldata['postalcode'] : '';
	$address1 = !empty($alldata['address1']) ? $alldata['address1'].' '.$postalcode : '';
	$city = !empty($alldata['city']) ? $alldata['city'] : '';
	$StateProvCode = !empty($alldata['StateProvCode']) ? $alldata['StateProvCode'] : '';
	$createddate = !empty($alldata['createddate']) ? $alldata['createddate'] : '';
	$PropertySubtype = !empty($alldata['PropertySubtype']) ? $alldata['PropertySubtype'] : '';
	$PropertyType = !empty($alldata['PropertyType']) ? $alldata['PropertyType'] : '';
	$description = !empty($alldata['description']) ? $alldata['description'] : '';
	$image = !empty($alldata['image']) ? $alldata['image'] : '';
	$squrefeet = !empty($alldata['squrefeet']) ? $alldata['squrefeet'] : '';
	$latitude = !empty($alldata['latitude']) ? $alldata['latitude'] : '';
	$longitude = !empty($alldata['longitude']) ? $alldata['longitude'] : '';
	$yearbulit = !empty($alldata['YearBuilt']) ? $alldata['YearBuilt'] : '';
	$totalrentablearea = !empty($alldata['RentableBuildingArea']) ? $alldata['RentableBuildingArea'] : '';
	$rentsqurefeet = !empty($alldata['Rentablesqurefeet']) ? $alldata['Rentablesqurefeet'] : '';
	$RentalRate = !empty($alldata['RentalRate']) ? $alldata['RentalRate'] : '';
	$RentalRateyear = !empty($alldata['RentalRateyear']) ? $alldata['RentalRateyear'] : '';
	$MaxContiguous = !empty($alldata['MaxContiguous']) ? $alldata['MaxContiguous'] : '';
	$MaxContiguousfeet = !empty($alldata['MaxContiguousfeet']) ? $alldata['MaxContiguousfeet'] : '';

	$imagetitle = preg_replace("/[^a-zA-Z0-9]/", "_", $alldata['title']);
	$image1 = file_get_contents($image);
	$fp  = fopen('images/json_lease_photos/'.$imagetitle.".jpg", 'w+'); 
	fputs($fp, $image1); 
	$photo_url = "/images/json_lease_photos/".$imagetitle.".jpg";



	$unitss = array('title'=>$title,'space_use'=>'Industrial','lease_term'=>'','lease_type'=>$PropertyType,'space_type'=>$PropertySubtype,'rental_rate'=>array($RentalRate,$RentalRateyear),'date_available'=>$createddate,'space_available'=> array($BuildingSize,$squrefeet));
	$location = array('lat'=>$latitude,'long'=>$longitude);

	$units[$i] = array('units'=> array($unitss),'address'=>$address1,'image'=>$photo_url,'for_sale'=>'false','location'=>$location,'for_lease'=>'true','on_market'=>'true','listing_id'=>$listingID,'year_built'=>$yearbulit,'rental_rate'=>'','sale_pending'=>'false','property_type'=>'Industrial','max_contiguous'=>array($MaxContiguous,$MaxContiguousfeet),'property_sub_type'=>$PropertySubtype,'listing_created_on'=>$createddate,'listing_updated_on'=>$createddate,'total_rentable_area'=>array($totalrentablearea,$rentsqurefeet),'total_space_available'=>array($BuildingSize,$squrefeet));
	$i++;
}
	$data = $units;
	echo $data = json_encode($data);

// foreach($allarr as $key4 => $alldata){
// 	$description = !empty($alldata['description']) ? $alldata['description'] : '';
// 	$insertquery = "INSERT INTO warehouse_listing1(ListingID,Title,SpaceAvailable,RentalRate,StreetAddress,CityName,StateProvCode,LastUpdate,SpaceAvailableMax,SpaceAvailableMin,PropertySubType,PropertyType,RentalRateMin,SpaceAvailableTotal,Description,PhotoURL)VALUES (
// 				'".mysqli_real_escape_string($conn,$alldata['listingID'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['title'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['BuildingSize'])."',
// 				'".mysqli_real_escape_string($conn,'Negotiable')."',
// 				'".mysqli_real_escape_string($conn,$alldata['address1'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['city'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['StateProvCode'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['createddate'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['BuildingSize'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['BuildingSize'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['PropertySubtype'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['PropertyType'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['BuildingSize'])."',
// 				'".mysqli_real_escape_string($conn,$alldata['BuildingSize'])."',
// 				'".mysqli_real_escape_string($conn,$description)."',
// 				'".mysqli_real_escape_string($conn,$alldata['image'])."'
// 				)";
// 				if (mysqli_query($conn, $insertquery)) {
					
// 					echo "Recored inserted.";
// 				} else {
// 					echo "Error: " . $insertquery . "" . mysqli_error($conn);
// 				}
	
// }
?>