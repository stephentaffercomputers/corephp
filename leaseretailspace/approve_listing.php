<?php 
include 'header.php';

if ($_GET['ListingIsActive'] && $_GET['ListingID'])
{
	try {
        $mysqli = mysqli_connect("localhost", "leaseret_db", "dtWDmtmHZpUgrd4R", "leaseret_db");
	}
	catch(exception $e) { var_dump($e);}

	if ($_GET['ListingIsActive'] == 'y')
	{
		$sql = "UPDATE retail_listing SET ";
		$sql .= "ListingIsActive = 'y' ";
		$sql .= "WHERE ListingID = ".$_GET['ListingID'];

		$result = $mysqli->query($sql);
		
		$message = "The listing has been published on the site: <a href='property_details.php?id=".$_GET['ListingID']."'>View listing's page</a>";
	}
	else
	{
		if (file_exists("images/property_photos/".$_GET['ListingID']."_property_photo.jpg")) unlink("images/property_photos/".$_GET['ListingID']."_property_photo.jpg");
		
		$sql = "DELETE FROM retail_listing WHERE ListingID = ".$_GET['ListingID'];

		$result = $mysqli->query($sql);
		
		$message = "The listing has been deleted.";
	}

}
?>
		<div class="section section-breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1>Add Listing</h1>
					</div>
				</div>
			</div>
		</div>      
        <div class="section">
	        <div class="container">
	        	<? echo $message; ?>
	        </div>
	    </div>
<?php include 'footer.php'; ?>    
	        	