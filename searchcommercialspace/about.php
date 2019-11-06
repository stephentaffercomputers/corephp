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
<li> <i class="icon-ok"></i> <em> Free report on your selected commercial space </em></li>
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
    					<p>Search Comercial Space is the largest network of Tenant representatives in the nation. We are made up of principle -level professionals so all work is handled by top negotiaors in the industry.</p>
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
<?php include 'footer.php'; ?>