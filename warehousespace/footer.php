	    <!-- Footer -->
	    <div class="section">
	    	<div class="container">
	    		<div class="one_half_first text-center"><a href="/all_cities.php">Cities Covered</a></div>
	    		<div class="one_half_last text-center"><a href="/all_states.php">States Covered</a></div>
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
		    			<div class="footer-copyright"><a href="https://warehousespaces.com/terms.php">Terms & Conditions</a> | <a href="https://warehousespaces.com/privacy.php">Privacy Policy</a> | <a href="https://warehousespaces.com/disclaimer.php">Listings Disclaimer</a> | <a href="https://warehousespaces.com/add_listing.php">Submit Listing</a><br /><? echo ($ref_file == "index" ? "Photo credit Mark Hunter<br />" : ""); ?>&copy; <? echo date("Y"); ?> Akimoto Ventures LLC - All rights reserved.</div>
		    		</div>
		    	</div>
		    </div>
	    </div>
	    
<script type="text/javascript">
    function randomItem(){
    console.log('called random');
    for (var i = 0; i < 4; i++){
        var length = $(".homsec .marginxs > div.col-md-3:not(:visible)").length;
        var random = Math.floor(Math.random() * length);
        $(".homsec .marginxs > div.col-md-3:not(:visible)").eq(random).show();
    }
}

	$(document).ready(function() {
	        console.log('test');
	       $( ".singleWarehouseInfo a" ).click(function() {
            $("#inquireModal").css('display','block');
           });
	       $( ".close" ).click(function() {
	           $('.modal-backdrop').remove();
            $("#inquireModal").css('display','none');
           });


	    $(".homsec .marginxs > div.col-md-3").hide();
        randomItem();
	});

</script>	    
    </body>
</html>	    		    