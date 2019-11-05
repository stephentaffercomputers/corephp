<?php include 'header.php'; ?>
        <div class="section section-white">
	    	<div class="container" style="background: url('https://leaseofficespace.net/images/bg-image4.jpg') no-repeat scroll center center transparent;">
				<div class="row-fluid" style="min-height: 400px;">
					<div class="col-md-6">
					<div id="heading" style="background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.65); color: #FFF; margin: 100px 0 50px 30px; padding: 5px; text-align: center;"><h1 style="color: inherit;">Find Office Space for Rent</h1>					</div>
				  
					<div id="search-form" style="margin: 50px 0 50px 30px; padding: 5px;">
						<form class="form-inline">
							  <input type="text" data-provide="typeahead" name="q" class="form-control" placeholder="Search by City, State or Zip Code" id="search-field" style="height: 40px; width: 300px; margin-bottom: 20px;"/>
							 <!-- <input type="text"  name="sqft" class="input" placeholder="Sq. Ft." id="search-field2"/>-->
							  <button type="submit" class="btn btn-red" id="search-button" style="height: 40px; margin-bottom: 20px;">Search</button> 
							
						</form>	
					</div>
					</div>
				</div>
			</div>
		</div>
        <div class="section section-white">
	    	<div class="container">
	    		<h2>Browse 1000s of Office Space Listings Throughout the US</h2>
	    		<div class="row-fluid">
					<?php include 'city_list_index.php' ?>
				</div>
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
			
				<div class="one_half_first"><h2 class="notmobile">1000s of Office Space Listings
</h2></div>
			
			<div class="one_half_last"><h2 class="notmobile">Free Commercial Real Estate Services</h2></div>
			<div style="clear: both;"></div>
			<div class="header_row"> </div>
			<div class="one_half_first">
			<h2 class="mobile">1000s of Office Space Listings
</h2
<p>Browse thousands of offices for rent throughout the United States and find the perfect office for your business. Our team of 100+ national commercial real estate specialists can help you find the perfect space for:
</p>
<p>- Accounting Firms<br>
- Banking / Finance <br>
- BioTech / Lab<br>
- Call centers<br>
- Creative agencies / Design / Architecture firms<br>
- High tech / R&D<br>
- Law firms<br>
- Media production / editing<br>
- Medical and dental practices</p>

<p>Search for your city or click the Cities Covered link below to browse every city where we have listings. If you would like to speak to an agent directly, fill out our <a href="https://leaseofficespace.net/contact.php">contact form</a>.</p>

</div>
			
			<div class="one_half_last">
			<h2 class="mobile">Free Commercial Real Estate Services</h2>
			
<p>Our national office space specialists will help you short list office spaces, schedule tours, negotiate a competitive lease and work to get you the best possible deal all at no cost to you. Additionally our agents provide free space planning consulting and lease renewal reviews to help you get the most out of your space and make sound business decisions when it comes to your commercial lease or purchase.</p>

<p>Our national tenant rep specialists have years of experience in their markets and will assist you at every step of the process from site selection to move-in. Let us know your specific requirements from location to number of offices and size required and we'll scour the market to find the right space for your needs. We've helped thousands of businesses just like yours find the right office and we'd love to add you to our list of satisfied clients.</p>

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
				document.location = "/offices-for-rent/" + item;
				return item;
			},
			sorter: function (items) {
				items.unshift(this.query);
				return items;
			}
		});
	});
	</script>
<?php include 'footer.php'; ?>