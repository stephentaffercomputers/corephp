<?php 
include 'header.php'; 

?>
		<div class="section section-breadcrumbs">
			<div class="container" >
				<div class="row">
					<div class="col-md-6">
						<h1>Coronavirus</h1>
					</div>
				</div>
			</div>
		</div>			
        <div class="section AboutPageContainer">
	    	<div class="container" style="color:#26619c;">
	        	<div class="row">
	        		<div class="col-md-12 col-sm-12">
	        		    <h1 style="text-align:left;">In uncertain times, we can help!</h1>
	        		    <h2 style="text-align:left; padding:0;">Right-size your space to control your real estate spend.</h2>
	        		    <p>As buildings have temporarily closed, we are offering free commercial real estate consultation solutions to cut your real estate spend.</p>
	        		    <p></p>
	        		    <p><h3 style="color:#26619c;">We can help: </h3></p>
	        		    <ul>
	        		        <li>Review and summarize your current lease.</li>
	        		        <li>Evaluate whether you <b>qualify for rent relief</b> based on your lease.</li>
	        		        <li>Advise on whether you can <b>sublease</b>, both from a market viability and economic perspective. </li>
	        		        <li>Give guidance on opportunities to <b>renegotiate and reduce the cost of your current lease</b> immediately, whether or not your space is up for renewal.</li>
	        		        <li>Help you implement your contingency plan, may it be to help you get out of your existing lease and downsize into more affordable space</li>
	        		        
	        		    </ul>
	        		    <p><h3 style="color:#26619c;">How can we help? </h3></p>
	        		    <p>Get in touch with any questions you may have answered by a local expert in your area.</p>
<div class="col-md-4">
	        			<div class="contact-form-wrapper">
		        			<form id="contact-form" method="post" action="/process_contact_form.php" >
				<input type="hidden" name="utm_source" value="<? if ($_SESSION["utm_source"]) echo $_SESSION["utm_source"]; ?>" />
				<input type="hidden" name="utm_campaign" value="<? if ($_SESSION["utm_campaign"]) echo $_SESSION["utm_campaign"]; ?>" />
								 <label for="contact_first_name">Name:</label> 
                    <input class="form-control required" type="text" placeholder="First Name" name="contact_first_name" ><br />
				 <label for="contact_email">Email Address</label> 	
                    <input class="form-control required email" type="email" placeholder="Email" name="contact_email" ><br />

				 <label for="contact_phone">Phone</label> 
                    <input class="form-control required" type="tel" placeholder="Phone Number" name="contact_phone"><br />
<label for="contact_company">Company:</label>	 
                    <input class="form-control" type="text" placeholder="Company" name="contact_company" ><br />
                <label for="contact_sq_ft">Square Feet</label> 
				<input class="form-control required" type="text" placeholder="Square Feet?" name="contact_sq_ft"><br />
                <label for="contact_message">Additional Requirements:</label>	 
                    <textarea class="form-control required" rows="3" name="contact_message" placeholder="We can answer any questions you may have. How can we help you?" ><?php echo $message_content; ?></textarea>             
				 <button style="background:red; border:1px solid red; margin-top:10px;" class="btn btn-block btn-danger" type="submit" id="submit-contact-form" name="submit_contact_form">Get in Touch</button>
				</form>
				</div>
				</div>
	        		    <!--<p style="text-align:center;"><a style="background:red; border:1px solid red;" class="btn btn-primary" href="contact.php">Get in touch</a></p>-->
	        		    <!--<p><a href="contact.php">Goes to contact page</a></p>-->
	        			<!-- Contact Form 
	        			<h1>We'll help you find the perfect space.</h1>
	        			<div class="col-md-6 col-sm-6 contactleft">
	        			<h2 style="color: #004F75;">Need Help Finding Space?</h2>
                        <p>We'll put you in touch with a local expert to help you with your space search.</p>
	        			                 <ul class="unstyled">

			   <li> <i class="icon-ok"></i> <b>See all lease/purchase options in 24 hours</b></li>

			   <li> <i class="icon-ok"></i> <b>Get 1-2+ months of free rent on most leases</b></li>

				<li> <i class="icon-ok"></i> <b>We can negotiate 15-20% off list price</b></li>

				<li> <i class="icon-ok"></i> <b>Our service is complementary - 100% free</b></li>

				</ul>

				<p><strong>We can answer questions, send you a short list of options and schedule tours.</strong></p>
				
         <h6><small> By clicking Submit, you agree to our terms of user and privacy policy. Your message will be sent to local broker and not made public.</small></h6>
	        			</div>-->
	        			<div class="col-md-12 col-sm-12">
	        			<!--    <a href="<?php echo $back_link; ?>">Back to Listings</a> <br /> <br />-->

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