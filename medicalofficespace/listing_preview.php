<?php include 'header.php'; ?>
		<div class="section section-breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1><a href="/">Home</a> / <a href="/offices-for-rent/<?=$row['CityName']?>"><?=$row['CityName']?></a></h1>
					</div>
				</div>
			</div>
		</div>

        <div class="section">
	    	<div class="container">
	    		<div class="row">
	    			<div class="col-sm-12">
	    				<a href="<?=$_SERVER['HTTP_REFERER']?>"><span class="ui-icon ui-icon-triangle-1-w" style="display:inline-block; vertical-align: middle;" ></span>Back to Search Results</span></a>
	    			</div>
	    			<!-- Office Image -->
	    			<div class="col-sm-6">
	    				<h1><?=$row['StreetAddress']?></h1>
        <h1><small><?=$row['CityName']?>, <?=$row['StateProvCode']?></small></h1>
        			</div>
        			<div class="col-sm-6">
        				<h1 class="text-right"><?=number_format($row['SpaceAvailableTotal'] != "") ? number_format(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailableTotal'])) : number_format(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailableMin'])) ?> sq. ft.</h1>
	<h3 class="text-right">
<?
if ($row['RentalRateMin'] == "Negotiable") $thisRate = "Inquire about rate";
else
{
	if (strpos($row['RentalRateMin'], 'Year') !== false)
	{
		if (strpos($row['RentalRateMin'], '/SF/Year')) $thisRate = '$ '.number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable']))).'/mo';
		else $thisRate = '$ '.number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])/12)).'/mo';
	}
	elseif (strpos($row['RentalRateMin'], 'SF') !== false) 
		$thisRate = '$ '.number_format(floatval(ereg_replace("[^-0-9\.]", "", $row['RentalRateMin'])) * floatval(ereg_replace("[^-0-9\.]", "", $row['SpaceAvailable']))).'/mo';
	else
		 $thisRate = '$ '.number_format(floatval(ereg_replace("[^-0-9\.]","",$row['RentalRateMin']))).'/mo';
}

echo $thisRate;
?>
	</h3>
					</div>
				</div>	
	    		<div class="row">
	    			<!-- Office Image -->
	    			<div class="col-sm-8 adjust">
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
						<tr><td class="detail-key"> Space Available: </td><td class="detail-value">  <?=number_format ($row['SpaceAvailableTotal'] != "")?number_format (ereg_replace("[^-0-9\.]","",$row['SpaceAvailableTotal'])):number_format (ereg_replace("[^-0-9\.]","",$row['SpaceAvailableMin']))?> <small>sq. ft. </small></td></tr>
						<tr><td class="detail-key"> Type: </td><td class="detail-value"><?=$row['PropertyType']?> - <?=$row['PropertySubType']?> </td></tr>
						<tr><td class="detail-key"> Est. Rent: </td><td class="detail-value">-</td></tr>
					</table>
					<p>&nbsp;</p>
	    				<p><?=$row["Description"]?></p>
	    				<p>&nbsp;</p>
<?
if ($row['AgentName'])
{
?>
	    				<p>Listed By: <?=$row["AgentName"]?> <em><?=$row["AgentEmail"]?> <?=$row["AgentPhone"]?></em></p>
	    				<p>&nbsp;</p>
<?
}
?>
	    				<p><a href="approve_listing.php?ListingIsActive=y&ListingID=<? echo $row['ListingID']; ?>" class="btn btn-block btn-danger">Approve</a>&nbsp;&nbsp;&nbsp;<a href="approve_listing.php?ListingIsActive=n&ListingID=<? echo $row['ListingID']; ?>" class="btn btn-block">Delete</a></p>
	    				<p>&nbsp;</p>
	    			</div>
	    			<!-- End Office Image -->
	    			<!-- Product Summary & Options -->
	    			<div class="col-sm-4 product-details" style="background: none repeat scroll 0% 0% padding-box #FFF; box-shadow: 0px 1px #FFF inset, 0px 0px 8px #C8CFE6;">
	    				<h2 style="color: black;">Contact Local Broker</h2>
             <ul class="unstyled">
			   <li> <i class="icon-ok"></i> Need a brochure w/ floor plans? Want to schedule a tour?</li>
			   <li> <i class="icon-ok"></i> Complete the form below to contact a local broker</li>
				<li> <i class="icon-ok"></i> Click Submit to recieve more information</li>
				</ul>
<i><h5>It's fast, simple and Free!</h5></i>
				<form id="contact-form" method="post" action="/process_contact_form.php" >
				<input type="hidden" name="utm_source" value="<? if ($_SESSION["utm_source"]) echo $_SESSION["utm_source"]; ?>" />
				<input type="hidden" name="utm_campaign" value="<? if ($_SESSION["utm_campaign"]) echo $_SESSION["utm_campaign"]; ?>" />
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
                 <!-- <label for="contact_budget">Budget?:</label>    !-->
					<input class="form-control" type="text" placeholder="Budget?" name="contact_budget"><br />
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
<li> <i class="icon-ok"></i> <em> Free report on your selected office </em></li>
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
				   			window.location = "https://medicalofficespace.us/thank-you.php";
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
	<?php include 'footer.php'; ?>