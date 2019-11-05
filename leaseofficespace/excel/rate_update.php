<?
$mysqli = mysqli_connect("localhost", "leaseoff_db", "BTrsHGcg21vnwUpt", "leaseoff_db");

$sql = "select * from office_listing";

try {
	$result = $mysqli->query($sql);
	
	$rec_count = $result->num_rows;
	
	if ($rec_count > 0)
	{
		while ($row = mysqli_fetch_array($result)) {
			if ($row['RentalRateMin'] != "Negotiable") {
			 	$thisRate = "NULL";

			 	if (strpos($row['RentalRateMin'], 'Year') !== false)
				{
				 	if (strpos($row['RentalRateMin'], '/SF/Year')) $thisRate = floatval(preg_replace("/[^-0-9\.]/", "", $row['RentalRateMin'])/12) * floatval(preg_replace("/[^-0-9\.]/", "", $row['SpaceAvailable']));
				 	else $thisRate = floatval(preg_replace("/[^-0-9\.]/", "", $row['RentalRateMin'])/12);
				}
				elseif (strpos($row['RentalRateMin'], 'SF') !== false) 
					$thisRate = floatval(floatval(substr($row['RentalRateMin'], 1)) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable'])));
				else
		 			$thisRate = floatval(preg_replace("/[^-0-9\.]/","",$row['RentalRateMin']));
		 			
		 		$sql = "update office_listing set MonthlyRate = ".$thisRate." WHERE ListingID = ".$row['ListingID'];
		 		
		 		echo $sql."<br /><br />";
		 		$update_result = $mysqli->query($sql);
			}
		}
	}
}
catch(exception $e) {var_dump($e);}

$mysqli->close();
?>