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
	        		<div class="col-sm-8">
	        			<!-- Contact Form -->
	        			<h3>Contact Our Commercial Real Estate Specialists</h3>
	        			<div class="contact-form-wrapper">
		        			<form class="form-horizontal" id="contact-form" method="post" action="/process_contact_form.php" >
				<input type="hidden" name="utm_source" value="<? if ($_SESSION["utm_source"]) echo $_SESSION["utm_source"]; ?>" />
				<input type="hidden" name="utm_campaign" value="<? if ($_SESSION["utm_campaign"]) echo $_SESSION["utm_campaign"]; ?>" />
		        				 <div class="form-group">
		        				 	<label for="contact_name" class="col-sm-3 control-label"><b>Your name</b></label>
		        				 	<div class="col-sm-9">
										<input class="form-control required" type="text" placeholder="Your Name" name="contact_name" id="contact_name" />
									</div>
								</div>
								<div class="form-group">
									<label for="contact_email" class="col-sm-3 control-label"><b>Your Email</b></label>
									<div class="col-sm-9">
										<input class="input-xlarge" type="hidden" placeholder="Phone" name="contact_phone">
										<input class="form-control required email" type="email" placeholder="Your Email" name="contact_email" id="contact_email" />
									</div>
								</div>
								<div class="form-group">
									<label for="contact_location" class="col-sm-3 control-label"><b>Ideal Location?</b></label>
									<div class="col-sm-9">
									    <input class="input-xlarge " type="hidden" placeholder="Company" name="contact_company" >
									    <input class="form-control" type="text" placeholder="Ideal Location?" name="contact_location" id="contact_location" />
									</div>
								</div>
								<div class="form-group">
									<label for="contact_message" class="col-sm-3 control-label"><b>Message</b></label>
									<div class="col-sm-9">
										<textarea class="form-control required contact_message" rows="10" name="contact_message" placeholder="Your Message" id="contact_message"></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<button class="btn pull-right" type="submit" id="submit-contact-form" name="submit_contact_form">Submit</button>
										<input type="hidden" name="property_url" value ="http://<?=$curr_url?>">
									</div>
								</div>
		        			</form>
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