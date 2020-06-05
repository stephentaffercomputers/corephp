<?
session_start();
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$string = 'warehouse-for-rent';
if (strpos($_SERVER['REQUEST_URI'], $string) !== false) {
    echo 'true';
$new_replace =  str_replace("warehouse-for-rent","commercial-real-estate",$_SERVER['REQUEST_URI']);
$new_link = "https://$_SERVER[HTTP_HOST]$new_replace";
	header("Location: ".$new_link);
}


if (isset($_GET['utm_source'])) $_SESSION["utm_source"] = $_GET['utm_source'];
if (isset($_GET['utm_campaign'])) $_SESSION["utm_campaign"] = $_GET['utm_campaign'];

$mysqli = mysqli_connect("localhost", "searchco_db", "7T2kHlRhuyLHFEKU", "searchco_db");

$title = 'Commercial Real Estate for Rent - View 1000s of Listings';
$meta_desc = 'View available commercial real estate for rent throughout the United States. Browse 1000s of listings to find the perfect space for you. Sizes from 5k to 100k sqft.';

$curr_url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];	

if (isset($_GET["q"])) $q = explode(",", $_GET["q"]);
$states = array(
    'Alabama'=>'AL',
    'Alaska'=>'AK',
    'Arizona'=>'AZ',
    'Arkansas'=>'AR',
    'California'=>'CA',
    'Colorado'=>'CO',
    'Connecticut'=>'CT',
    'Delaware'=>'DE',
    'District of Columbia'=>'DC',
    'Florida'=>'FL',
    'Georgia'=>'GA',
    'Hawaii'=>'HI',
    'Idaho'=>'ID',
    'Illinois'=>'IL',
    'Indiana'=>'IN',
    'Iowa'=>'IA',
    'Kansas'=>'KS',
    'Kentucky'=>'KY',
    'Louisiana'=>'LA',
    'Maine'=>'ME',
    'Maryland'=>'MD',
    'Massachusetts'=>'MA',
    'Michigan'=>'MI',
    'Minnesota'=>'MN',
    'Mississippi'=>'MS',
    'Missouri'=>'MO',
    'Montana'=>'MT',
    'Nebraska'=>'NE',
    'Nevada'=>'NV',
    'New Hampshire'=>'NH',
    'New Jersey'=>'NJ',
    'New Mexico'=>'NM',
    'New York'=>'NY',
    'North Carolina'=>'NC',
    'North Dakota'=>'ND',
    'Ohio'=>'OH',
    'Oklahoma'=>'OK',
    'Oregon'=>'OR',
    'Pennsylvania'=>'PA',
    'Rhode Island'=>'RI',
    'South Carolina'=>'SC',
    'South Dakota'=>'SD',
    'Tennessee'=>'TN',
    'Texas'=>'TX',
    'Utah'=>'UT',
    'Vermont'=>'VT',
    'Virginia'=>'VA',
    'Washington'=>'WA',
    'West Virginia'=>'WV',
    'Wisconsin'=>'WI',
    'Wyoming'=>'WY',
);
//if (sizeof($q) == 1 ) $title = array_search(trim(str_replace("-", " ", $q[0])), $states).' Commercial Space for Rent - ';
if (sizeof($q) == 1 ) $title = $q[0].' Commercial Space for Rent - ';

$ref_file = basename($_SERVER["SCRIPT_FILENAME"], '.php');

if (isset($_GET["id"]))
{
	if (is_numeric(trim($_GET["id"]))) $where_clause = "ListingID = ".trim($_GET["id"]);
	else 
	{ 
		$exp_str = explode(",",trim($_GET["id"]));
		if (count($exp_str) > 1)
		{
			$statecode = trim($exp_str[0]);
			$cityname = trim($exp_str[1]);

			$title = $cityname.' Commercial Space for Rent ';

			$where_clause = ' trim(StreetAddress)= "'. trim($exp_str[0]).'"';
			$where_clause .= ' and trim(CityName)= "'.trim($exp_str[1]).'"';
		}
		else $where_clause = ' trim(StreetAddress)= "'.trim($_GET["id"]).'" ';
	}

	$search_sql_stmt = 'select * from listings where '.$where_clause.';';
	try {
		$result = $mysqli->query($search_sql_stmt);
		
		$row = mysqli_fetch_array($result);
		//var_dump($search_sql_stmt);
		if ($row['ListingIsActive'] == 'n' && $ref_file == 'property_details')
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: https://searchcommercialspace.com/commercial-real-estate/United-States/".$row['StateProvCode']."/".$row['CityName']);
		}

		$title = $row['CityName'].' '.($row['PropertyType'] == 'Industrial' ? 'Warehouse' : $row['PropertyType']).' Space for rent at '.$row['StreetAddress'];
	
		$latitude = $row['Latitude'];
		$longitude = $row['Longitude'];
	}
	catch(exception $e) {var_dump($e);}
}

$ref_file = basename($_SERVER["SCRIPT_FILENAME"], '.php');

if ($ref_file == 'property_details' || $ref_file == 'listing_preview'){ //indivudal listing
	$search_sql = "select * from listings where CityName = '".$row['CityName']."' AND StateProvCode = '".$row['StateProvCode']."' AND ListingIsActive = 'y' AND PropertyType = '".$row['PropertyType']."'";
	try {
		$result = $mysqli->query($search_sql);
		$row_cnt = $result->num_rows;
	}
	catch(exception $e) {var_dump($e);}
	
	$meta_desc = $row['StreetAddress']." offers ".($row['SpaceAvailableTotal'] ? number_format(preg_replace('/\D/', '', $row['SpaceAvailableTotal']))." sq. ft. of " : "").($row['PropertyType'] == 'Industrial' ? 'warehouse' : strtolower($row['PropertyType']))." space for rent. Get a free property report and view more than ".$row_cnt." available ";
	
	switch ($row['PropertyType']) {
		case "Industrial":
   			$meta_desc .= "warehouses";
			break;
		case "Office":
			$meta_desc .= "offices";
			break;
		case "Medical":
			$meta_desc .= "medical offices";
			break;
		case "Retail":
			$meta_desc .= "retail spaces";
			break;
	}	
	
	$meta_desc .= " in ".$row['CityName'];
}
elseif ($ref_file == 'all_cities'){
	$title = 'Commercial Real Estate for Rent - View All US Cities';
	$meta_desc = "Searchcommercialspace.com has listings in hundreds of cities throughout the US. View all of our cities and find the right space for your business.";
}
elseif ($ref_file == 'all_states'){
	$title = 'Commercial Real Estate for Rent - View All States';
	$meta_desc = "Searchcommercialspace.com has listings in many states throughout the US. View all of our cities and find the right space for your business.";
}
elseif ($ref_file == 'all_medical'){
	$title = 'US Medical Office Space for Rent - View All Cities';
	$meta_desc = "Search Commercial Space has medical office listings in hundreds of US cities. View all of our cities and find the right medical space for your business.";
}
elseif ($ref_file == 'all_office'){
	$title = 'US Office Space for Rent - View All Cities';
	$meta_desc = "Search Commercial Space has offices for rent in hundreds of US cities. View all of our cities and find the right office for your business.";
}
elseif ($ref_file == 'all_retail'){
	$title = 'US Retail Space for Rent - View All Cities';
	$meta_desc = "Search Commercial Space has retail listings in hundreds of US cities. View all of our cities and find the right retail space for your business.";
}
elseif ($ref_file == 'all_warehouse'){
	$title = 'US Warehouse Space for Rent - View All Cities';
	$meta_desc = "Search Commercial Space has warehouse listings in hundreds of US cities. View all of our cities and find the right warehouse for your business.";
}
elseif ($ref_file == 'searchresults'){
	$q[0] = str_replace("-", " ", $q[0]);

 	$search_sql = "select * from listings where trim(CityName) = '".trim($q[0])."' AND ListingIsActive = 'y'".(isset($_GET['PropertyType']) ? " AND PropertyType='".$_GET['PropertyType']."'" : "");
	try {
		$result = $mysqli->query($search_sql);
		$row_cnt = $result->num_rows;
	}
	catch(exception $e) {var_dump($e);}

	if (!isset($_GET['PropertyType'])) {
//$q[0] = array_search(trim(str_replace("-", " ", $q[0])), $states);
		$title = $q[0]." Commercial Real Estate Listings - View ".$row_cnt." Spaces";
		$meta_desc = "View new office, warehouse, retail and medical listings in ".$q[0]." and get a free property report. We offer complimentary commercial real estate services";
	}
	else {
		switch ($_GET['PropertyType']) {
			case "office":
				$title = $q[0]." Office Space for Rent - ".(isset($_GET{'page'}) ? "Offices for Lease Page ".$_GET{'page'} : "See Offices for Lease");
 				$meta_desc = "Browse ".(isset($_GET{'page'}) ? "page ".$_GET{'page'}." of " : "").$row_cnt." office space listings in ".$q[0].". Search Commercial Space helps you find the right office and our agents negotiate your lease for free.";
				break;
			case "medical":
				$title = $q[0]." Medical Space for Rent - ".(isset($_GET{'page'}) ? "Medical Offices for Lease Page ".$_GET{'page'} : "Lease Medical Offices");
 				$meta_desc = "Browse ".(isset($_GET{'page'}) ? "page ".$_GET{'page'}." of " : "").$row_cnt." medical space listings in ".$q[0].". Search Commercial Space helps you find the right space and our agents negotiate your lease for free.";
				break;
			case "industrial":
				$title = $q[0]." Warehouse Space for Rent - ".(isset($_GET{'page'}) ? "Warehouses for Lease Page ".$_GET{'page'} : "Lease Warehouses");
 				$meta_desc = "Browse ".(isset($_GET{'page'}) ? "page ".$_GET{'page'}." of " : "").$row_cnt." warehouses for rent in ".$q[0].". Search Commercial Space helps you find the right warehouse and our agents negotiate your lease for free.";
				break;
			case "retail":
				$title = $q[0]." Retail Space for Rent - ".(isset($_GET{'page'}) ? "Retail Spaces for Lease Page ".$_GET{'page'} : "Lease Retail Spaces");
 				$meta_desc = "Browse ".(isset($_GET{'page'}) ? "page ".$_GET{'page'}." of " : "").$row_cnt." retail space listings in ".$q[0].". Search Commercial Space helps you find the best location and our agents negotiate your lease for free.";
				break;
		}	
	}
}
elseif ($ref_file == 'about'){
	$title = 'About searchcommercialspace.com - View 1000s of Commercial Listings';
	$meta_desc = "Learn more about searchcommercialspace.com. We're one of the nations largest warehouse listing sites with commercial spaces for rent and sale.";
}
elseif ($ref_file == 'contact'){
	$title = 'Request Property Report - searchcommercialspace.com';
	$meta_desc = "Contact page for searchcommercialspace.com. We're one of the nations largest warehouse listing sites.";
}


if($row['ListingIsActive'] == 'n') {
$redirect_url = 'https://searchcommercialspace.com/commercial-real-estate/United-States/'.$row['CityName'];
header("HTTP/1.1 301 Moved Permanently");
header('Location:'. $redirect_url);
//break;
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?=$title?></title>
        <meta name="description" content="<?= $meta_desc?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/icomoon-social.css">
        <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,600,800' rel='stylesheet' type='text/css'> -->

        <link rel="stylesheet" href="/css/leaflet.css" />
		<!--[if lte IE 8]>
		    <link rel="stylesheet" href="/css/leaflet.ie.css" />
		<![endif]-->
		<link rel="stylesheet" href="/css/main.css">
		<link href="/css/lwhcustom.css" rel="stylesheet">
		<link rel="stylesheet" href="/css/flexslider.css">
		<link rel="stylesheet" href="/css/custom_style.css">

        <script src="/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
        <script src="/js/jquery-ui-1.9.1.custom.min.js" ></script>
        <!-- <script src="/js/datatables/js/jquery.dataTables.js"></script>
		<script src="/js/datatables/js/fnAddTr.js"></script> -->
        <script>window.jQuery || document.write('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>
        <script src="/js/jquery.validate.bootstrap.js"></script>
        <!-- <script src="http://cdn.leafletjs.com/leaflet-0.5.1/leaflet.js"></script> -->
        <script src="/js/jquery.fitvids.js"></script>
        <script src="/js/jquery.sequence-min.js"></script>
        <script src="/js/jquery.bxslider.js"></script>
        <script src="/js/main-menu.js"></script>
        <script src="/js/template.js"></script>
        <script src="/js/bootstrap3-typeahead.js"></script>
        <script src="/js/jquery.flexslider.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
  <style>
    .flexslider {
      margin-bottom: 10px;
    }

    .flex-control-nav {
      position: relative;
      bottom: auto;
    }

    .custom-navigation {
      display: table;
      width: 100%;
      table-layout: fixed;
    }

    .custom-navigation > * {
      display: table-cell;
    }

    .custom-navigation > a {
      width: 50px;
    }

    .custom-navigation .flex-next {
      text-align: right;
    }
    
    .slider { margin: 50px 0 10px!important;}
#carousel li {margin-right: 5px;}
#carousel img {display: block; opacity: .5; cursor: pointer;}
#carousel img:hover {opacity: 1;}
#carousel .flex-active-slide img {opacity: 1; cursor: default;}
  </style>
<script src='//d3c3cq33003psk.cloudfront.net/opentag-166428-catalystchat.js' async defer></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-51049736-3', 'auto');
  ga('send', 'pageview');

</script>
  <?php 
$url_without_query_string = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if($url_without_query_string == "/")
{
    $url =  'https://'.$_SERVER['HTTP_HOST'];
}
else
{
    $url = 'https://'. $_SERVER['HTTP_HOST'].$url_without_query_string;
}
 ?> 
 <link rel="canonical" href="<?php echo $url;?>">

  </head>
  <body>
<?
error_reporting(1);
?>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        

        <!-- Navigation & Logo-->
        <div class="mainmenu-wrapper">
	        <div class="container topcontainer">
	        <!--	<div class="menuextras topextra">
					<div class="extras">
						<ul>
							<li>
							    <a href="http://www.linkedin.com" title="LinkedIn"><img src="https://searchcommercialspace.com/images/linkedin_32.png" width="24" alt="LinkedIn"></a>
							</li>
							<li>	
								<a href="https://www.facebook.com" title="Join Us!"><img src="https://searchcommercialspace.com/images/facebook_32.png" width="24" alt="Facebook"></a>
							</li>
							<li>
							    <a href="https://twitter.com" title="Follow Us!"><img src="https://searchcommercialspace.com/images/twitter_32.png" width="24" alt="Twitter"></a>
							</li>
							<li>
							    <a href=""><img src="https://searchcommercialspace.com/images/rss_32.png" width="24" alt="RSS Feed"></a>
							</li>
			        	</ul>
					</div>
		        </div>-->
		        <nav id="mainmenu" class="mainmenu">
					<ul>
						<li class="logo-wrapper"><a href="/"><h3>Search Commercial Space</h3></a> <!--<h4>Find Commercial Space For Lease and For Sale</h4>!--></li>
						<li>
							<a href="tel:+1-800-481-0120"><h3>(800) 481-0120</h3></a>
						</li>
						<li>
							<a href="/about.php">ABOUT&nbsp;US</a>
						</li>
						<li>
						    <a href="/contact.php">REQUEST&nbsp;PROPERTY&nbsp;REPORT</a>
						</li>
						<!--<li class="spacing-icons" style="float: right;">
							    <a href="http://www.linkedin.com" title="LinkedIn"><img src="https://searchcommercialspace.com/images/linkedin_32.png" width="24" alt="LinkedIn"></a> <a href="https://www.facebook.com" title="Join Us!"><img src="https://searchcommercialspace.com/images/facebook_32.png" width="24" alt="Facebook"></a> <a href="https://twitter.com" title="Follow Us!"><img src="https://searchcommercialspace.com/images/twitter_32.png" width="24" alt="Twitter"></a> <a href=""><img src="https://searchcommercialspace.com/images/rss_32.png" width="24" alt="RSS Feed"></a></li>!-->
							
					</ul>
				</nav>
			</div>
		</div>
		
		<?php 
		///dev/

		if($_SERVER['REQUEST_URI'] != '/dev/coronavirus.php' && $_SERVER['REQUEST_URI'] != '/coronavirus.php') { ?>
		    <?php if($_SERVER['REQUEST_URI'] == '/dev/') { ?>
		<div class="">
		    <?php } else { ?>
		<div class="" style="background:red;">
		    <?php } ?>
		    <?php if($_SERVER['REQUEST_URI'] == '/dev/') { ?>
		    <div class="container corona-note">
		        <?php } else { ?>
		    <div class="container corona-note">
		       

		        <?php } ?>
		        
		        <div class="col-md-12 row-fluid marginxs" style="margin:0px;">
		        <!--<h2 style="color:#fff;">Covid-19 Update:</h2>-->
		        <div style="width:100%">
		        <h2 style="width:90%; float:left; color:#fff;">Given the current Coronavirus pandemic, we are here as a resource should you wish to contact us about any real estate related issues and potential options/solutions. Hopefully this input may help with any business contingency planning you may be undertaking.</h2>
		        <a style="text-decoration:underline;color:#fff;float:right;text-decoration: none;margin-top: 10px;font-weight: bold;background: #fff;color: #C60001;padding: 10px;" href="coronavirusnew.php">Contact Us</a>
		        </div>
		        <!--<h2>**Given the current coronavirus pandemic impacting many businesses, we are here as a resource should you wish to contact us about any real estate related issues and what the potential options/solutions might be.  Hopefully this input may help with any business contingency planning you may be undertaking. <a href="coronavirus.php">Read More</a></h2>-->
		        </div>
		    </div>
		</div>
		<?php } ?>
		
		