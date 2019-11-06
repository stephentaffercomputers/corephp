<?php include 'header.php'; ?>
        <div class="section section-white">
	    	<div class="container" style="background: url('https://searchcommercialspace.com/images/scs_bg.jpg') no-repeat scroll center center transparent;">
				<div class="row-fluid" style="min-height: 400px;">
					<div class="col-md-8">
						<div id="heading" style="background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.65); color: #FFF; margin: 100px 0 50px 30px; padding: 20px; text-align: left;">
							<h1 style="color: inherit;">Search Commercial Real Estate</h1>
							<div id="search-form" style="margin: 50px 0 0 0; padding: 5px;">
								<form name="search-form" class="form-inline" action="" onSubmit="document.location.href = '/commercial-real-estate/United-States/' + this.q.value + '/?PropertyType=' + this.PropertyType.value; return false;">
							  	<input type="text" data-provide="typeahead" name="q" class="form-control" placeholder="Search by City, State or Zip Code" id="search-field" style="height: 50px; width: 250px; margin-bottom: 20px;"/>
							  	<select name="PropertyType" id="PropertyType" class="form-control" style="height: 50px; width: 250px; margin-bottom: 20px;">
							  	<option value="office">Office</option>
							  	<option value="industrial">Industrial</option>
							  	<option value="retail">Retail</option>
							  	<option value="medical">Medical</option>
							  	</select>
							 	<!-- <input type="text"  name="sqft" class="input" placeholder="Sq. Ft." id="search-field2"/>-->
							  	<button type="submit" class="btn btn-lg btn-red" id="search-button" style="float: right;">Search</button> 
								</form>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="section section-white">
	    	<div class="container">
	    		<h2>Search Commercial Real Estate Throughout the US</h2>
	    		<div class="row-fluid">
					<?php include 'city_list_index.php' ?>
				</div>
			</div>
		</div>
		
	<!-- new section -->	
		<div class="section section-white">
	        <div class="container">
	    		<h2>Contact A Local Commercial Real Estate Broker</h2>
				<form id="contact-form" method="post" action="/process_contact_form.php" >
				<input type="hidden" name="utm_source" value="<? if ($_SESSION["utm_source"]) echo $_SESSION["utm_source"]; ?>" />
				<input type="hidden" name="utm_campaign" value="<? if ($_SESSION["utm_campaign"]) echo $_SESSION["utm_campaign"]; ?>" />
				<input type="hidden" name="property_url" value ="https://<?=$curr_url?>">
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
                    	<input class="form-control required" type="text" placeholder="Name" name="contact_name"><br />
                    	<input class="form-control required" type="tel" placeholder="Phone" name="contact_phone"><br />
                    	<input class="form-control required email" type="email" placeholder="Email" name="contact_email"><br />
                    	<input class="form-control required" type="text" placeholder="Company" name="contact_company" >
					</div>
					<div class="col-md-4">
						<select name="property_type" id="property_type" class="form-control">
							  <option value="w">Industrial</option>
						</select><br />
						<input class="form-control required" type="text" placeholder="Location" name="contact_location"><br />
						<input class="form-control required" type="text" placeholder="Square Feet?" name="contact_sq_ft"><br />
						<textarea class="form-control required" rows="2" name="contact_message" placeholder="What can you tell us about what you are looking for?" ></textarea><br />
						<button class="btn btn-block btn-danger" type="submit" id="submit-contact-form" name="submit_contact_form">Submit</button>	
					</div>
				</div>
				</form>
			</div>
		</div>
		<!-- client section -->
		<div class="clientcontainer section section-white">
		<div class="ccontainer container">

		<h1>Featured Clients</h1>

		 <p><img src="img/cimg1.jpg" class="first"><img src="img/cimg2.jpg"><img src="img/cimg3.jpg"><img src="img/cimg4.jpg"><img src="img/cimg5.jpg"><img src="img/cimg6.jpg"><img src="img/cimg7.jpg"></p>
		</div>
		</div>
		<!-- end client section -->
		
	<!-- new section -->	
		<div class="section section-white">
	        <div class="container">
			
			<div class="one_half_first"><h3>1000s of Commercial Real Estate Listings</h3></div>
			
			<div class="one_half_last"><h3>Free Commercial Real Estate Services</h3></div>
			<div style="clear: both;"></div>
			<div class="header_row"> </div>
			<div class="one_half_first">
<p></p>
<p>Browse thousands of commercial spaces for rent throughout the United States and find the perfect office, warehouse, retail or medical space for your business. Our team of commercial real estate specialists can help you find the perfect space for:</p>

<p><ul><li>BioTech / Lab Space</li>
<li>Call Center</li>
<li>Charter / Private Schools</li>
<li>Creative Space</li>
<li>Data Center</li>
<li>Distribution Warehouse</li>
<li>Flex Office Warehouse</li>
<li>High Tech / Technology</li>
<li>Manufacturing</li>
<li>Medical Practice</li>
<li>Office</li>
<li>R&D (Research & Development)</li>
<li>Refrigerated/Cold Storage</li>
<li>Retail / Showroom</li>
<li>Studio / Entertainment</li>
<li>Truck Terminal/Hub/Transit</li></ul></p>

<p>Search for your city or click the Cities Covered link below and if you have any questions or would like to speak to an agent directly, fill out our <a href="https://searchcommercialspace.com/contact.php">contact</a> form or call (855) 989-5894.</p>

</div>
			
			<div class="one_half_last">
			<p></p>
<p>Our national network of commercial real estate agents will help you locate spaces, schedule tours, negotiate a competitive lease and work to get you the best possible deal all at no cost to you. In addition our agents can provide free space planning and lease renewal reviews to help you get the most out of your space and help you make sound business decisions when it comes to your commercial lease or purchase.</p>

<p>Each agent we partner with has years of experience in their respective markets and will be with you every step of the way from site selection to move-in.</p>

<p>Let us know what kind of space you need and your specific requirements and we'll scour the market to find the right space for your needs.</p>

</div>
			
			</div>
			</div> 
		
		
	<!-- / end new section -->	
	<script>
	$(document).ready(function() {
		$("#search-field").typeahead({
			minLength: 1,
			source: function(query, process) {
					return $.getJSON('./search_list.php',
						{ q: query },
						function (data) {
						return process(data);
					});
			
			},
			updater: function (item) {
			 	$("#search-field").value = item;
//				document.location = "/commercial-real-estate/United-States/" + item;
				return item;
			},
			sorter: function (items) {
				items.unshift(this.query);
				return items;
			}
		});
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
                 contact_location: {
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
                 contact_location: {
                    required: "Enter your preferred location"
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
	});

	$("#submit-contact-form").click(function () {
		if(!$("#contact-form").valid()) return false;
		else 
			$.ajax({
				type: 'POST',
				  data: $("#contact-form").serialize(),
				  success: function() { 
				   			window.location = "https://searchcommercialspace.com/thank-you.php";
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
