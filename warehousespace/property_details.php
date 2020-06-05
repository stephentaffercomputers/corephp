<?php include 'header.php'; ?>
<!--<script src="https://maps.google.com/maps/api/js?v=3.3&amp;libraries=geometry&amp;sensor=false&amp;key=AIzaSyDyfRy-J4yLknJAI3anM5w4OuVVt8NmQtU" type="text/javascript"></script>-->
<script type="text/javascript">
var map = null;
var geocoder = null;
var myPano;

function handleNoFlash(errorCode) {
  if (errorCode == FLASH_UNAVAILABLE) {
    alert("Error: Flash doesn't appear to be supported by your browser");
    return;
  }
}  

function initialize(address, Lat, Lng) {
 var myLatlng = new google.maps.LatLng(Lat, Lng);
  var geocoder = new google.maps.Geocoder(); 
  geocoder.geocode( { 'address': address }, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      var panoramaOptions = { pov: { heading: 270, pitch: 0 } };
      var myStreetView = new google.maps.StreetViewPanorama(document.getElementById("pano"), panoramaOptions);
      myStreetView.setPosition(myLatlng);
      //var marker = new google.maps.Marker({ position: results[0].geometry.location, map: myStreetView, title: address });
      
      var mapOptions = { zoom: 14, center: results[0].geometry.location, mapTypeId: google.maps.MapTypeId.ROADMAP };
      var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
      var marker = new google.maps.Marker({position: results[0].geometry.location, map: map});	
    }
  }); 
}

function showAddress(address) {
  if (geocoder) {
    geocoder.getLatLng(
      address,
      function(point) {
        if (!point) {
          alert(address + " not found");
        } else {
          map.setCenter(point, 13);
        }
      }
    );
  }
}
</script>
<?php
//echo $row['ListingIsActive'];
if($row['ListingIsActive'] == 'n') {
//echo "<br>" .     $redirect_url = '/warehouse/United-states/'.$row['CityName'];
header("HTTP/1.1 301 Moved Permanently");
header('Location:'. $redirect_url);
//break;
}
?>
		<div class="section section-breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12" style="padding: 0px;">
					<div class="col-md-9">
<?php if($row['created_from'] == 'json') { ?>
						<h1><a href="/">Home</a> / <a href="/warehouse-for-sale/United-States/<?=$row['StateProvCode']?>"><?=$row['StateProvCode']?></a> / <a href="/warehouses/United-States/<?=trim($row['CityName'])?>"><?=$row['CityName']?> Warehouses </a> / <a href="/warehouse-for-sale/United-States/<?=$row['StateProvCode']?>/<?=trim($row['CityName'])?>?sell_property=all">Warehouse Space for Sale</a> / <?=$row['StreetAddress']?> </h1>
<?php } else { ?>

						<h1><a href="/">Home</a> / <a href="/warehouse-for-rent/United-States/<?=$row['StateProvCode']?>"><?=$row['StateProvCode']?></a> / <a href="/warehouses/United-States/<?=trim($row['CityName'])?>"><?=$row['CityName']?> Warehouses</a> / <a href="/warehouse-for-rent/United-States/<?=$row['StateProvCode']?>/<?=trim($row['CityName'])?>?rent_property=all">Warehouse Space for Rent</a> / <?=$row['StreetAddress']?></h1>
<?php } ?>
					</div>
					<div class="col-md-3 text-right">
	    				<a href="<?=$_SERVER['HTTP_REFERER']?>"><span class="ui-icon ui-icon-triangle-1-w" style="display:inline-block; vertical-align: middle;" ></span>Back to Search Results</span></a>
					</div>

					</div>
				</div>
			</div>
		</div>

        <div class="section">
	    	<div class="container">
	    		<div class="row">
	    			<!-- Warehouse Image -->
	    			<div class="col-sm-8 adjust">
	    			<div>
<?php if($row['created_from'] == 'json' && $row['StateProvCode'] != 'CA') { ?>
	    				<h1 style="background: #fff; padding: 5px; color: #000;"><?=$row['StreetAddress']?> - OFF MARKET</h1>
<?php } else { ?>
	    				<h1 style="background: #fff; padding: 5px; color: #000;"><?=$row['StreetAddress']?></h1>
<?php } ?>
        <h1 style="background: #fff; padding: 5px;"><small style="color: #000;"><?=$row['CityName']?>, <?=$row['StateProvCode']?> | <?=number_format($row['SpaceAvailableTotal'] != "") ? number_format(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailableTotal']) + 5) : number_format(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailableMin'])+ 5 )  ?> sq. ft. | <?
if ($row['RentalRateMin'] == "Negotiable") $thisRate = "Inquire about rate";
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

echo '$'.$thisRate.'/mo';
/*	if (strpos($row['RentalRateMin'], 'Year') !== false)
	{
//	    echo floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable']));
		/*if (strpos($row['RentalRateMin'], '/SF/Year')) $thisRate = '$ '.number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable']))).'/mo';*/
/*		if (strpos($row['RentalRateMin'], '/SF/Year')) $thisRate = '$ '.sprintf("%.2f", floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable']))).'/mo';
		else $thisRate = '$ '.number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12)).'/mo';
	}
	elseif (strpos($row['RentalRateMin'], 'SF') !== false) 
		$thisRate = '$ '.number_format(floatval(floatval(substr($row['RentalRateMin'], 1)) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable'])))).'/mo';
	else
		 $thisRate = '$ '.number_format(floatval(ereg_replace("[^-0-9\.]","",$row['RentalRateMin']))).'/mo';

echo $thisRate;*/
					/*	    $get_last_five = substr($row['RentalRateMin'],-5);
							if($get_last_five == '/Year')
							{
								$rental_rate_min = substr($row['RentalRateMin'], 0, strlen($row['RentalRateMin']) - 5).'/Month';
							}
							else
							{
								$rental_rate_min = $row['RentalRateMin'];
							}
 if($row['created_from'] == 'json') {
$rental_rate_min = $row['RentalRateMin'];
}
echo $rental_rate_min;*/
}

?>
</small></h1> 

        			</div>
	    				<div class="flexslider">
	    					<ul class="slides">
	    						<li>
	    							<img alt="<?php echo $row['StreetAddress'] . ' ' . $row['CityName'] . ','. $row['StateProvCode'];?>" src="<? if ($row['PhotoURL'] != "") print $row['PhotoURL']; else print '/images/no-available.jpg';	?>" />
	    						</li>
	    						<? if ($row['PhotoURL2'] != "") print "<li><img alt=\"".$row['StreetAddress'] . ' ' . $row['CityName'] . ','. $row['StateProvCode']."\" src=\"".$row['PhotoURL2']."\" /></li>"; ?>
	    						<? if ($row['PhotoURL3'] != "") print "<li><img alt=\"".$row['StreetAddress'] . ' ' . $row['CityName'] . ','. $row['StateProvCode']."\" src=\"".$row['PhotoURL3']."\" /></li>"; ?>
	    						<? if ($row['PhotoURL4'] != "") print "<li><img alt=\"".$row['StreetAddress'] . ' ' . $row['CityName'] . ','. $row['StateProvCode']."\" src=\"".$row['PhotoURL4']."\" /></li>"; ?>
	    						<? if ($row['PhotoURL5'] != "") print "<li><img alt=\"".$row['StreetAddress'] . ' ' . $row['CityName'] . ','. $row['StateProvCode']."\" src=\"".$row['PhotoURL5']."\" /></li>"; ?>
	    					</ul>
	    				</div>
<?
if ($row['PhotoURL2'] != "" || $row['PhotoURL3'] != "" || $row['PhotoURL4'] != "" || $row['PhotoURL5'] != "")
{
?>
	    				<div class="custom-navigation" style="margin-bottom: 20px;">
	    					<a href="#" class="flex-prev">Prev</a>
	    					<div class="custom-controls-container"></div>
	    					<a href="#" class="flex-next">Next</a>
	    				</div>
<?
}
?>
  <script type="text/javascript">
    $(window).load(function(){
      $('.flexslider').flexslider({
        animation: "slide",
        controlsContainer: $(".custom-controls-container"),
        customDirectionNav: $(".custom-navigation a"),
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
  </script>
<table>
						<tr><td class="detail-key"> Space Available: </td><td class="detail-value">  <?=number_format ($row['SpaceAvailableTotal'] != "")?number_format (ereg_replace("[^-0-9\.]","",$row['SpaceAvailableTotal'] ) + 5 ) :number_format (ereg_replace("[^-0-9\.]","",$row['SpaceAvailableMin'] ) + 5 )  ?> <small>sq. ft. </small></td></tr>
						<tr><td class="detail-key"> Type: </td><td class="detail-value"><?=$row['PropertyType']?> - <?=$row['PropertySubType']?> </td></tr>

						<?php
						    $get_last_five = substr($row['RentalRateMin'],-5);
							if($get_last_five == '/Year')
							{
								$rental_rate_min = substr($row['RentalRateMin'], 0, strlen($row['RentalRateMin']) - 5).'/Month';
							}
							else
							{
								$rental_rate_min = $row['RentalRateMin'];
							}
						?>
<?php						
$rent_amount = ereg_replace("[^-0-9\.]", "", $row['RentalRateMin']);
 $space_amount = preg_replace("/[^0-9]/", "", $row['SpaceAvailable']);
 $rent_final = $rent_amount / $space_amount;
       if (preg_match('/\Month\b/', $row['RentalRateMin'])) {
 $rental_rate_min = number_format($rent_final,2) + .01 . '/SF/Month'; 
 } else {
 $rental_rate_min = number_format(($rent_amount / 12),2) + .01 . '/SF/Month';        

   }
 if($row['created_from'] == 'json') {
$rental_rate_min = number_format($rent_amount / ereg_replace(",", "", $row['SpaceAvailable']),2) + .01 . '/SF/Month';
}

if($rental_rate_min == '0.00/SF/Month') {
    $rental_rate_min = substr($rent_amount,0,4) + .01 . '/SF/Month';
}

?>
<?php if($row['created_from'] == 'json') {
//$rental_rate_min = $row['RentalRateMin'];
 ?>
						<tr><td class="detail-key"> Est. Price: </td><td class="detail-value"><? 
						if($row['RentalRateMin'] == 'Negotiable') {
                        echo    $rental_rate_min = '$1.00/SF/Month';
                        } else if($rental_rate_min == '$0.01/SF/Month') {
                        echo    $rental_rate_min = 'Negotiable';    
                        } else {
						    echo '$'. $rental_rate_min; 
						}
						?></td></tr>
<?php } else { ?>
						<tr><td class="detail-key"> Est. Rent: </td><td class="detail-value"><? 
						if($row['RentalRateMin'] == 'Negotiable') {
                        echo    $rental_rate_min = '$1.00/SF/Month';
                        } else if($rental_rate_min == '0.01/SF/Month') {
                        echo    $rental_rate_min = 'Negotiable';    
                        } else {
						    echo '$'. $rental_rate_min; 
						}
						?></td></tr>

<?php } ?>
					</table>
					<p>&nbsp;</p>
<div class="singleWarehouseInfo">
                                    <a href="#inquireModal" class="" data-toggle="modal">Inquire about listing</a>
                                    <?php if(!empty($row['floor_plan'])) { ?>
                                    <a download href="<?php echo $row['floor_plan'];?>" class="" data-toggle="modal">Download Floorplan</a>
                                    <?php } ?>
                                    <!--<a href="#tourListingModal" class="" data-toggle="modal">Tour listing</a>-->
</div>
						<p>&nbsp;</p>

<?
if (preg_match("/^(.*)(\<em\>Listing Provided by\<\/em\>.*\<br\s\/\>.*)$/i", $row["Description"], $matches))
{
	$row["Description"] = $matches[1]."</p>";
	$agents = "<p>".$matches[2];
}
?>
	    				<p><?php echo str_replace("https://www.","https://",$row["Description"]);
 //echo $row["Description"]; 
 ?></p>
	    				<p>&nbsp;</p>
	    				<!--<div id="map_canvas" style="float: left; height: 400px; width: 50%; margin-bottom: 30px;"></div>
	    				<div id="pano" style="float: left; height: 400px; width: 50%; margin-bottom: 30px;"></div>-->
	    				<p><?=$agents?></p>
	    			</div>
	    				
	    			<!-- End Warehouse Image -->
	    			<!-- Product Summary & Options -->
	    			<div class="col-sm-4 product-details" style="background: none repeat scroll 0% 0% padding-box #FFF; box-shadow: 0px 1px #FFF inset, 0px 0px 8px #C8CFE6;">
	    				<h2 style="color: black;">Free Property Report</h2>
             <ul class="unstyled">
			   <li> <i class="icon-ok"></i> <b>See all lease/purchase options in 24 hours</b></li>
			   <li> <i class="icon-ok"></i> <b>Get 1-2+ months of free rent on most leases</b></li>
				<li> <i class="icon-ok"></i> <b>We can negotiate 15-20% off list price</b></li>
				<li> <i class="icon-ok"></i> <b>Our tenant broker service is 100% free to clients</b></li>
				</ul>
				<form id="contact-form" method="post" action="/process_contact_form.php" >
				<input type="hidden" name="utm_source" value="<? if ($_SESSION["utm_source"]) echo $_SESSION["utm_source"]; ?>" />
				<input type="hidden" name="utm_campaign" value="<? if ($_SESSION["utm_campaign"]) echo $_SESSION["utm_campaign"]; ?>" />
				<input type="hidden" name="contact_budget" value="" />
				<!-- <label for="contact_firstname">First Name:</label> !-->
                    <input class="form-firstname" type="text" placeholder="First Name" name="contact_firstname" ><br />
				<!-- <label for="contact_name">Name:</label> !-->
                    <input class="form-control required" type="text" placeholder="Name" name="contact_name" ><br />
				<!-- <label for="contact_phone">Phone:</label> !-->	
                    <input class="form-control required" type="tel" placeholder="Phone" name="contact_phone"><br />
				<!-- <label for="contact_email">Email:</label> !-->	
                    <input class="form-control required email" type="email" placeholder="Email" name="contact_email" ><br />
				<!-- <label for="contact_company">Company:</label>	 !-->
                    <input class="form-control required" type="text" placeholder="Company" name="contact_company" ><br />
                    <input class="form-control" type="hidden" placeholder="Ideal Location?" name="contact_location" value="<?=$row['StreetAddress']?>, <?=$row['CityName']?>, <?=$row['StateProvCode']?>">
				<!-- <label for="property_type">Type:</label> !-->	
                    <select class="form-control" name="property_type">
                       <!-- <option value="">Select a  property type</option>-->
                        <option value="o"> Office</option>
                        <option value="w" selected> Warehouse</option>
                        <option value="s"> Showroom</option>
                        <option value="r"> Retail</option>
                        <option value="m"> Manufacturing</option>
                        <option value="d"> Distribution</option>
                        <option value="i"> Industrial</option>
                        <option value="c"> Call Center</option>
                        <option value="l"> Land/Yard</option>
                        <option value="v"> Live/Loft</option>
                        <option value="a"> Creative Space</option>
                        <option value="e"> Executive Suite</option>
                        <option value="f"> R&amp;D – Flex Space</option>
                        <option value="p"> Medical Space</option>
                        <option value="t"> Data Center</option>
                    </select><br />
                    <!-- <label for="contact_sq_ft">Square Feet?:</label>    !-->
					<input class="form-control required" type="text" placeholder="Square Feet?" name="contact_sq_ft"><br />
                    <!--<input class="input-xlarge" type="text" placeholder="Additional Requirements?" name="contact_additional_req">-->
				<!-- <label for="contact_message">Comments:</label>	 !-->
                    <textarea class="form-control required" rows="3" name="contact_message" placeholder="Comments" ></textarea><br />
                    <button class="btn btn-block btn-danger" type="submit" id="submit-contact-form" name="submit_contact_form">Submit</button>
                    <input type="hidden" name="property_url" value ="https://<?=$curr_url?>">
		      </form>
         <h6><small> By clicking Submit, you agree to our terms of user and privacy policy. Your message will be sent to local broker and not made public.</small></h6>
<hr>
<h6>You will receive:</h6>
<ul class="unstyled">
<li> <i class="icon-ok"></i> <em> Free report on your selected warehouse </em></li>
<li> <i class="icon-ok"></i> <em> Details on Landlord incentives and special offers </em></li>
<li> <i class="icon-ok"></i> <em> No obligation advice from local broker specialist </em></li>
</ul>		 
<p>&nbsp;</p>
	    			</div>
	    			<!-- End Product Summary & Options -->
	    			
	    			<!-- Full Description & Specification -->
	    			<div class="col-sm-12">
	    				<div class="tabbable">
	    					<!-- Tabs -->
							<ul class="nav nav-tabs product-details-nav">
								<li class="active"><a href="#tab1" data-toggle="tab">Listings within Vicinity</a></li>
							</ul>
							<!-- Tab Content) -->
							<div class="tab-content product-detail-info" style="float: left; overflow: hidden;">
								<div class="tab-pane active" id="tab1" style="float: left; overflow: hidden;">
	    				<?php include 'listings_vicinity.php' ?>
	    						</div>
	    					</div>
						</div>		
	    			</div>
	    			<!-- End Full Description & Specification -->

	    		</div>
			</div>
		</div>
		<script>
	initialize("<? echo $row['StreetAddress'].' '.$row['CityName'].', '.$row['StateProvCode']; ?>", "<? echo $row["Latitude"]; ?>", "<? echo $row["Longitude"]; ?>"); 
	showAddress("<? echo $row['StreetAddress'].' '.$row['CityName'].', '.$row['StateProvCode']; ?>");
	
$(document).ready(function(){

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
})

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

	</script>
	
<div id="inquireModal" class="modal fade marketReportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-content-area bullet-text">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	        			    <h3><?php echo $row['StreetAddress'] . ',' . $row['CityName'] . ',' . $row['StateProvCode']; ?></h3>
	        			    <p>Let us know how to contact you and we'll reach out with more details on this space and a complete property report on other similar options in the market.</p>                                
                                <!--<h3 id="myModalLabel">
                                    Free Property Report
                                </h3>-->
                            </div>
                            <div class="modal-content">
	    			<div class="col-sm-12 product-details" style="background: none repeat scroll 0% 0% padding-box #FFF;">
	    				<!--<h2 style="color: black;">Free Property Report</h2>-->
	    				<!--
<form id="contact-form" method="post" action="/process_contact_form.php" novalidate="novalidate">
				<input type="hidden" name="utm_source" value="">
				<input type="hidden" name="utm_campaign" value="">
				<input type="hidden" name="property_url" value="https://warehousespaces.com/">
				<input type="hidden" name="contact_company" value="">
                <input type="hidden" name="property_type" value="w">
				<input type="hidden" name="contact_budget" value="">
	    		<div class="row-fluid">
					<div class="col-md-4">
						<ul class="list-unstyled">
							<li> <i class="icon-ok"></i> <b>See all lease/purchase options in 24 hours</b></li>
							<li> <i class="icon-ok"></i> <b>Get 1-2+ months of free rent on most leases</b></li>
							<li> <i class="icon-ok"></i> <b>We can negotiate 15-20% off list price</b></li>
							<li> <i class="icon-ok"></i> <b>Our tenant broker service is 100% free to clients</b></li>
						</ul>
						<p><strong>We can answer questions, send you a short list of options and schedule tours.</strong></p>
						<p>&nbsp;</p>
						<h6><small> By clicking Submit, you agree to our terms of user and privacy policy. Your message will be sent to local broker and not made public.</small></h6>
					</div>
					<div class="col-md-4">
                    	<input class="form-firstname" type="text" placeholder="First Name" name="contact_firstname">
                    	<input class="form-control required" type="text" placeholder="Name" name="contact_name"><br>
                    	<input class="form-control required" type="tel" placeholder="Phone" name="contact_phone"><br>
                    	<input class="form-control required email" type="email" placeholder="Email" name="contact_email"><br>
                    	<input class="form-control required" type="text" placeholder="Company" name="contact_company">
					</div>
					<div class="col-md-4">
						<input class="form-control required" type="text" placeholder="Location" name="contact_location"><br>
						<input class="form-control required" type="text" placeholder="Square Feet?" name="contact_sq_ft"><br>
						<textarea class="form-control required" rows="2" name="contact_message" placeholder="What can you tell us about what you are looking for?"></textarea><br>
						<button class="btn btn-block btn-danger" type="submit" id="submit-contact-form" name="submit_contact_form">Submit</button>	
</div>
</div>
</form>  -->

		<form id="contact-form" method="post" action="/process_contact_form.php" >
				<input type="hidden" name="utm_source" value="<? if ($_SESSION["utm_source"]) echo $_SESSION["utm_source"]; ?>" />
				<input type="hidden" name="utm_campaign" value="<? if ($_SESSION["utm_campaign"]) echo $_SESSION["utm_campaign"]; ?>" />
				 <label for="contact_first_name">Name:</label> 
                    <input class="form-control required" type="text" placeholder="First Name" name="contact_first_name" ><br />
				 <label for="contact_email">Email Address</label> 	
                    <input class="form-control required email" type="email" placeholder="Email" name="contact_email" ><br />

				 <label for="contact_phone">Phone</label> 
                    <input class="form-control required" type="tel" placeholder="Phone Number" name="contact_phone"><br />
                <label for="contact_location">Location</label> 
				<input class="form-control required" type="text" placeholder="Location" name="contact_location"><br />

                <label for="contact_sq_ft">Square Feet</label> 
				<input class="form-control required" type="text" placeholder="Square Feet?" name="contact_sq_ft"><br />                    
                <label for="contact_message">Additional Requirements:</label>	 
                    <textarea class="form-control required" rows="3" name="contact_message" placeholder="Additional Requirements" ><?php echo $message_content; ?></textarea>
				<br />
                    <button class="btn btn-block btn-danger" type="submit" id="submit-contact-form" name="submit_contact_form">SEND INQUIRY</button>
                    <input type="hidden" name="property_url" value ="http://<?=$curr_url?>">
		      </form>
         <h6><small> By clicking Submit, you agree to our terms of user and privacy policy. Your message will be sent to local broker and not made public.</small></h6>
			</div>

                            </div>
                        </div>
                    </div>





	
	<?php include 'footer.php'; ?>