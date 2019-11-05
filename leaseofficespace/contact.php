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
		        			<form class="form-horizontal" role="form" id="contact-form" method="post" action="process_contact_form.php" >
		        				 <div class="form-group">
		        				 	<div class="col-sm-12">
										<input class="form-control required" type="text" placeholder="Your Name" name="contact_name" >
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<input class="input-xlarge" type="hidden" placeholder="Phone" name="contact_phone">
										<input class="form-control required email" type="email" placeholder="Your Email" name="contact_email" >
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
									    <input class="input-xlarge " type="hidden" placeholder="Company" name="contact_company" >
									    <input class="form-control" type="text" placeholder="Ideal Location?" name="contact_location">
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<textarea class="form-control required" rows="10" name="contact_message" placeholder="Your Message" ></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<button class="btn btn-block btn-danger" type="submit" id="submit-contact-form" name="submit_contact_form">Submit</button>
										<h6><small> By clicking Submit, you agree to our terms of user and privacy policy. Your message will be sent to local broker and not made public.</small></h6>
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
		/*		
                contact_phone: {            //input name: subject
                    required: true,   //required boolean: true/false
                  //   phoneUS: true
                },
                 contact_company: {            //input name: subject
                    required:true
                 },
         */
				notes:{
                    required: true
                }
            },
            messages: {               //messages to appear on error
                contact_name: {
                      required:"Please put your full name.",
                      minlength:"Full name please."
                      },
                contact_email: "Enter a valid email.",
		/*		
                contact_phone: {
                      required: "Enter a valid Phone Number",
					//  phoneUS: "Please ented a Valid Phone Number"
                      },
                 contact_company: {            //input name: subject
                    required: "Enter your company name"
                 },
          */
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
				  success: function() { alert("Thank you for submitting your information!");
							//$("#contact-form").reset();
							resetForm('contact-form');
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