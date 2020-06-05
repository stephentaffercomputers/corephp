<?
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