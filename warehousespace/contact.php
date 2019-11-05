<?php 
include 'header.php'; 
$curr_url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
?>
		<div class="section section-breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h1>Contact Us</h1>
					</div>
				</div>
			</div>
		</div>			
        <div class="section">
	    	<div class="container">
	        	<div class="row">
	        		<div class="col-sm-offset-3 col-sm-6">
	        			<!-- Contact Form -->
	        			<h3>Contact Our Commercial Real Estate Specialists</h3>
	        			<div class="contact-form-wrapper">
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
                    <input class="form-control" type="text" placeholder="Company" name="contact_company" ><br />
                    <input class="form-control required" type="text" placeholder="Ideal Location?" name="contact_location"><br />
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
                    <input type="hidden" name="property_url" value ="http://<?=$curr_url?>">
		      </form>
         <h6><small> By clicking Submit, you agree to our terms of user and privacy policy. Your message will be sent to local broker and not made public.</small></h6>		 
<p>&nbsp;</p>
		        		</div>
		        		<!-- End Contact Info -->
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
                contact_location: {            //input name: subject
                    required: true,   //required boolean: true/false
                }
            },
            messages: {               //messages to appear on error
                contact_name: {
                      required:"Please put your full name.",
                      minlength:"Full name please."
                      },
                contact_email: "Enter a valid email.",
                contact_location: {
                      required: "Enter Ideal Location."
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
<?php include 'footer.php'; ?>