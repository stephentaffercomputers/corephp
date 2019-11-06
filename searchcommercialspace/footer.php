	    <!-- Footer -->
	    <div class="section">
	    	<div class="container">
	    		<!--<div class="one_half_first text-center"><a href="/all_cities.php">Cities Covered</a></div>
	    		<div class="one_half_last text-center"><a href="/all_states.php">States Covered</a></div>-->

				<div class="text-center"><a href="/all_cities.php">Cities Covered</a> &nbsp; &nbsp;
	    		<a href="/all_states.php">States Covered</a>&nbsp; &nbsp; <a href="/all_retail.php">Retail Covered</a> &nbsp; &nbsp;<a href="/all_office.php">Office Covered</a> &nbsp; &nbsp;<a href="/all_warehouse.php">Warehouse Covered</a> &nbsp; &nbsp;<a href="/all_medical.php">Medical Covered</a></div>	    		
	    		<div style="clear: both;"></div>
		    </div>
	    </div>
		<?php //if() 
		if(isset($_REQUEST['q'])) {
		?>
		<!-- client section -->
		<div class="clientcontainer section section-white">
		<div class="ccontainer container">

		<h1>Featured Clients</h1>

		 <p><img src="/img/cimg1.jpg" class="first"><img src="/img/cimg2.jpg"><img src="/img/cimg3.jpg"><img src="/img/cimg4.jpg"><img src="/img/cimg5.jpg"><img src="/img/cimg6.jpg"><img src="/img/cimg7.jpg"></p>
		</div>
		</div>
		<!-- end client section -->
		<?php } ?>
	    <div class="footer">
	    	<div class="container">
		    	<div class="row">
		    		<div class="col-md-12">
		    			<div class="footer-copyright"><a href="/terms.php">Terms & Conditions</a> | <a href="/privacy.php">Privacy Policy</a> | <a href="/disclaimer.php">Listings Disclaimer</a> | <a href="/add_listing.php">Submit Listing</a><br /><? echo ($ref_file == "index" ? "Photo courtesy of Christopher F<br />" : ""); ?>&copy; <? echo date("Y"); ?> Akimoto Ventures LLC - All rights reserved.</div>
		    		</div>
		    	</div>
		    </div>
	    </div>
    </body>
</html>	    		    