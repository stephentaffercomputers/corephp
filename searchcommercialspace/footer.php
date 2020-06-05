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

	    $(".homsec .marginxs > div.col-md-3").hide();
        randomItem();
	});

</script>	    

<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}


var slideIndexnew = 1;
showSlidesnew(slideIndexnew);

function plusSlidesnew(n) {
  showSlidesnew(slideIndexnew += n);
}

function currentSlidenew(n) {
  showSlidesnew(slideIndexnew = n);
}

function showSlidesnew(n) {
  var i;
  var slides = document.getElementsByClassName("mySlidesnew");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndexnew = 1}    
  if (n < 1) {slideIndexnew = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndexnew-1].style.display = "block";  
  dots[slideIndexnew-1].className += " active";
}

</script>

    </body>
</html>	    		    