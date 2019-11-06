<?php include 'header.php'; ?>
        <div class="section section-white">
	    	<div class="container" style="background: url('http://warehousespaces.com/images/bg-image4.jpg') no-repeat scroll center center transparent;">
				<div class="row-fluid" style="min-height: 400px;">
					<div class="col-md-6">
					<div id="heading" style="background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.65); color: #FFF; margin: 100px 0 50px 30px; padding: 5px; text-align: center;"><h1 style="color: inherit;">Search Commercial Space</h1>					</div>
				  
					<div id="search-form" style="margin: 50px 0 50px 30px; padding: 5px;">
						<form name="search-form" class="form-inline" action="" onSubmit="document.location.href = '/warehouse-for-rent/United-States/' + this.q.value + '/?PropertyType=' + this.PropertyType.value; return false;">
							  <input type="text" data-provide="typeahead" name="q" class="form-control" placeholder="Search by City, State or Zip Code" id="search-field" style="height: 40px; width: 300px; margin-bottom: 20px;"/>
							  <select name="PropertyType" id="PropertyType" class="form-control" style="height: 40px; width: 300px; margin-bottom: 20px;">
							  <option value="Industrial">Industrial</option>
							  </select>
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
	    		<h2>Search Available Commercial Space Throughout the US</h2>
	    		<div class="row-fluid">
					<?php include 'city_list_index.php' ?>
				</div>
			</div>
		</div>
		
	<!-- new section -->	
		<div class="section section-white">
	        <div class="container">
			
			<div class="one_half_first"><h3>1000s of Warehouse Space Listings</h3></div>
			
			<div class="one_half_last"><h3>Free Commercial Real Estate Services</h3></div>
			<div style="clear: both;"></div>
			<div class="header_row"> </div>
			<div class="one_half_first">
<p></p>
<p>Browse thousands of warehouses for rent throughout the United States and find the perfect warehouse for your business. Our team of industrial real estate specialists can help you find the perfect space for:</p>

<p>Contractor Yards<br>
Distribution<br>
Flex Use<br>
Manufacturing<br>
Research and Development / Clean Room<br>
Refrigeration / Cold Storage / Food Grade<br>
Street Retail / Showroom<br>
Truck Hub</p>

<p>Search for your city or click the Cities Covered link below and if you have any questions or would like to speak to an agent directly, call us (888) 979-5899.</p>

</div>
			
			<div class="one_half_last">
			<p></p>
<p>Our agents will help you locate prospective spaces, schedule tours, negotiate a competitive lease and work to get you the best possible deal all at no cost to you. In addition we provide free space planning consulting and lease renewal reviews to help you get the most out of your space and help you make sound business decisions when it comes to your commercial lease or purchase.</p>

<p>Each agent has years of experience in their respective markets and will be with you every step of the way from site selection to move-in.</p>

<p>Let us know your specific requirements from power to number of dock doors and proximity to major roads / rail access and we'll scour the market to find the right space for your needs. </p>

</div>
			
			</div>
			</div> 
		
		
	<!-- / end new section -->	
		
		<div class="section section-white">
	        <div class="container">
	            <h2>Clients <a href="http://warehousespaces.com/clients.php" style="font-size: 14px; font-weight: normal;">more...</a></h2>
	        	<div class="row">
	        		<div class="span12 text-center">
					    <img src="http://warehousespaces.com/images/logos1.png"><img src="http://warehousespaces.com/images/logos2.png">
					</div>    
				</div>
			</div>
		</div>
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
//				document.location = "/warehouse-for-rent/United-States/" + item;
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