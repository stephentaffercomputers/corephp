<?php
//error_reporting(0);
$q =" ";
$rows = array();
if(isset($_GET["q"])) $q = $_GET["q"];
$limit = 8;
if(isset($_GET["limit"]) && is_int($_GET["limit"])) $limit = $_GET["limit"];


$sql_list_cities = "SELECT distinct concat(CityName, ', ',trim(PostalCode)) as search_result  FROM medical_listing WHERE concat( CityName,StateProvCode, PostalCode) like '".$q."%' ORDER BY CityName LIMIT ".$limit.";"; //StateProvName
//print	 $sql_list_cities;

	try{
		$mysqli = mysqli_connect("localhost", "medicalo_db", "7CuDaKPPeXgpKT46", "medicalo_db");
		$result = $mysqli->query($sql_list_cities);	
while ($r = mysqli_fetch_array($result)) {
		$rows[] = $r['search_result'];
	}
}
	catch(exception $e) { var_dump($e);}
    header('Content-Type: application/json');
	echo json_encode(array_values($rows));
?>