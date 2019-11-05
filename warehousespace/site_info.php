<?php include 'header.php'; ?>
<?php
$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");
$total_properties = "SELECT * FROM `warehouse_listing`";
$result = $mysqli->query($total_properties);

$total_properties = $result->num_rows;
mysqli_free_result($result);

$total_rent_properties = "SELECT count(*) as rent_properties FROM `warehouse_listing` WHERE `created_from` = '' ";
$rent_result = $mysqli->query($total_rent_properties);
$rent_prop = mysqli_fetch_array($rent_result);
$total_rent_properties = $rent_prop['rent_properties'];


$total_sale_properties = "SELECT count(*) as total_sale_properties FROM `warehouse_listing` WHERE `created_from` = 'json'";
$sale_result = $mysqli->query($total_sale_properties);
$sale_prop = mysqli_fetch_array($sale_result);
$total_sale_properties = $sale_prop['total_sale_properties'];

?>
<div class="col-sm-12"><h2 style="background-color: #ffffff;">Total Properties : <?php echo $total_properties; ?></h2></div>

<div class="col-sm-12"><h2 style="background-color: #ffffff;">Total Rent Properties : <?php echo $total_rent_properties; ?></h2></div>

<div class="col-sm-12"><h2 style="background-color: #ffffff;">Total Sale Properties : <?php echo $total_sale_properties; ?></h2></div>

<? 

$mysqli->close();

include 'footer.php'; 

?>