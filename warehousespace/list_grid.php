<?
/*
if (!isset($result))
{
$search_sql_stmt = "SELECT *  FROM warehouse_listing WHERE ListingID >= (SELECT FLOOR( MAX(ListingID) * RAND()) FROM `warehouse_listing` ) ORDER BY ListingID LIMIT 4;";

	try{
		$mysqli = mysqli_connect("localhost", "root", "amar", "largewarehouse_com");
		$result = $mysqli->query($search_sql_stmt);
		
		}
	catch(exception $e) { var_dump($e);}
}
*/

 print '<table id="results" class="display">
		<thead>
        <tr>
			<th>Property Type</th>
			<th>SubType</th>
			<th>Zoning</th>
			<th>Bld. Size</th>
			<th>Lot Size</th>
			<th>Year Built</th>
			<th>Rent</th>
			<th>Space Avil.</th>
            <th>Address</th>
            <th>City</th>
        </tr>
    </thead><tbody>'.$table_rows.'</tbody></table>';
/*	
	while ($row = mysqli_fetch_array($result)) 
{ var_dump($row);
    print " ";
  
   print '<script>';
  print "var ".$row['ListingID']." = L.marker([".$row['Latitude'].", ".$row['Longitude']."]).bindPopup('".$row['StreetAddress']." <Br> ".$row['CityName']." <br> Propertytype: ".$row['PropertyType']." | ".$row['PropertySubType']."').addTo(map);  </script>";
  
}
*/

?>

<script>
$(document).ready( function () {
    var oTable = $('#results').dataTable({
	//"sDom": "<'row'<'span8'l><'span8'f>r>t<'row'<'span8'i><'span8'p>>"
	 "bJQueryUI": true
	//"sPaginationType": "bootstrap"
	});
} );
</script>