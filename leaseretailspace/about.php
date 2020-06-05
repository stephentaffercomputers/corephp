<?php include 'header.php'; ?>
		<div class="section section-breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1>About Us</h1>
					</div>
				</div>
			</div>
		</div>
        
        <div class="section">
	        <div class="container">
	    			<!-- Product Summary & Options -->
	    			<div class="col-sm-4 product-details" style="background: none repeat scroll 0% 0% padding-box #FFF; box-shadow: 0px 1px #FFF inset, 0px 0px 8px #C8CFE6; float: right;">
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
                    <input class="form-control required" type="text" placeholder="Ideal Location?" name="contact_location"><br />
				<!-- <label for="property_type">Type:</label> !-->	
                    <select class="form-control" name="property_type">
                       <!-- <option value="">Select a  property type</option>-->
                        <option value="o"> Office</option>
                        <option value="w"> Warehouse</option>
                        <option value="s"> Showroom</option>
                        <option value="r" selected> Retail</option>
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
                    <input type="hidden" name="property_url" value ="http://<?=$curr_url?>">
		      </form>
         <h6><small> By clicking Submit, you agree to our terms of user and privacy policy. Your message will be sent to local broker and not made public.</small></h6>
<hr>
<h6>You will receive:</h6>
<ul class="unstyled">
<li> <i class="icon-ok"></i> <em> Free report on your selected retail space </em></li>
<li> <i class="icon-ok"></i> <em> Details on Landlord incentives and special offers </em></li>
<li> <i class="icon-ok"></i> <em> No obligation advice from local broker specialist </em></li>
</ul>		 
<p>&nbsp;</p>
	    			</div>
	    			<!-- End Product Summary & Options -->
				<div class="col-sm-8 row service-wrapper-row">
        			<div class="col-sm-4">
        				<div class="service-image">
        			
        				</div>
        			</div>
        			<div class="col-sm-8">
    					<h3>We find you space and negotiate with Landlords</h3>
    					<p>We are a national firm that specializes in assisting Fortune 500 corporations, medium sized companies and small businesses identify space and negotiate lease transactions.</p>
    				</div>
				</div>
				<div class="col-sm-8 row service-wrapper-row">
        			<div class="col-sm-8">
    					<h3>We never represent Landlords, only Tenants.<br />100% Tenant Focused.</h3>
    					<p>We serve our clients best interests by removing the conflict of interest inherent when a listing broker tries to represent both the Tenant and Landlord.</p>
    				</div>
        			<div class="col-sm-4">
        				<div class="service-image">
        					
        				</div>
        			</div>
				</div>
				<div class="col-sm-8 row service-wrapper-row">
        			<div class="col-sm-4">
        				<div class="service-image">
        					
        				</div>
        			</div>
        			<div class="col-sm-8">
    					<h3>We have extensive market knowledge</h3>
    					<p>Tremendous leverage and clout with Landlords allows us to secure the best lease terms and price on space.</p>
    				</div>
				</div>
				<div class="col-sm-8 row service-wrapper-row">
        			<div class="col-sm-8">
    					<h3>We are committed to understanding the Market</h3>
    					<p>Lease Retail Space is the largest network of Tenant representatives in the nation. We are made up of principle -level professionals so all work is handled by top negotiaors in the industry.</p>
    				</div>
        			<div class="col-sm-4">
        				<div class="service-image">
        					
        				</div>
        			</div>
				</div>
				<div class="col-sm-8 row service-wrapper-row">
        			<div class="col-sm-4">
        				<div class="service-image">
        					
        				</div>
        			</div>
        			<div class="col-sm-8">
    					<h3>Our client focus puts you in the driver's seat</h3>
    					<p>Our team approach saves you time and money. We do the leg work for you in order to find the best space that fits your needs. This allows you to focus on what you do best.</p>
    				</div>
				</div>												
				<div class="col-sm-8 row service-wrapper-row">
        			<div class="col-sm-8">
    					<h3>Our service is free of charge and our work is Guaranteed</h3>
    					<p>Listing brokers represent the Landlord's interests and split the  commission with us to represent your interests in the transaction. If we don't meet your expectations we'll give you our fee.</p>
    				</div>
        			<div class="col-sm-4">
        				<div class="service-image">
        					
        				</div>
        			</div>
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
                contact_phone: {            //input name: subject
                    required: true,   //required boolean: true/false
                  //   phoneUS: true
                },
                 contact_company: {            //input name: subject
                    required: true,   //required boolean: true/false
                  },
                notes: {
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
                 contact_company: {            //input name: subject
                    required: "Enter your company name"
                 },
                notes:{
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
				   			window.location = "https://leaseretailspace.net/thank-you.php";
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