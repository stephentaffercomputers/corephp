<?
session_start();

if (isset($_GET['utm_source'])) $_SESSION["utm_source"] = $_GET['utm_source'];
if (isset($_GET['utm_campaign'])) $_SESSION["utm_campaign"] = $_GET['utm_campaign'];
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
if (isset($_GET["q"])) $q = explode(",", $_GET["q"]);
$get_request_url = explode("/", $_SERVER['REQUEST_URI']);
//print_r($get_request_url);

$title = 'US Warehouse Space for Rent - View 1000s of Listings';
$meta_desc = 'View available warehouse space for rent throughout the United States. Browse 1000s of listings to find the perfect space for you. Sizes from 5k to 100k sqft.';

$curr_url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];	


//echo $q[1];
//if (sizeof($q) == 1 ) $title = array_search(trim(str_replace("-", " ", $q[0])), $states).' Warehouse Space for Rent - ';

//if (sizeof($q) == 1 ) $title = $q[0].' Warehouse Space for Rent - ';

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

			$title = $cityname.' Warehouse Space for Rent ';

			$where_clause = ' trim(StreetAddress)= "'. trim($exp_str[0]).'"';
			$where_clause .= ' and trim(CityName)= "'.trim($exp_str[1]).'"';
		}
		else $where_clause = ' trim(StreetAddress)= "'.trim($_GET["id"]).'" ';
	}

	$search_sql_stmt = 'select * from warehouse_listing where '.$where_clause.';';
	try {
		$mysqli = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");
		$result = $mysqli->query($search_sql_stmt);
		
		$row = mysqli_fetch_array($result);
		//var_dump($search_sql_stmt);
		if ($row['ListingIsActive'] == 'n' && $ref_file == 'property_details')
		{
//			header("HTTP/1.1 301 Moved Permanently");
	//		header("Location: https://warehousespaces.com/warehouse-for-rent/United-States/".$row['StateProvCode']."/".$row['CityName']);
		}

               if($row['created_from'] == 'json')
               {
		$title = $row['CityName'].' Warehouse Space for Sale - ' ;//.' '.$row['StateProvCode'];
               }
               else {
		$title = $row['CityName'].' Warehouse Space for Rent - ' ;//.' '.$row['StateProvCode'];
               }
	
		$latitude = $row['Latitude'];
		$longitude = $row['Longitude'];
	}
	catch(exception $e) {var_dump($e);}
}

$ref_file = basename($_SERVER["SCRIPT_FILENAME"], '.php');

if ($ref_file == 'property_details' || $ref_file == 'listing_preview'){ //indivudal listing

          $str_address = explode(',', $row['StreetAddress']);

         if($row['created_from'] == 'json') {

           $title = $row['CityName'] .' Warehouse Space for Sale - ' . $str_address[0];
	$meta_desc = $row['StreetAddress']." Offers ".($row['SpaceAvailableTotal'] ? number_format(preg_replace('/\D/', '', $row['SpaceAvailableTotal']))." sq. ft. of " : "")."warehouse space for sale in ".$row['CityName'].". View this property and all other warehouses for sale in ".$row['CityName'];
        } else {
           $title = $row['CityName'].' Warehouse Space for Rent - '.$str_address[0]; 
 	$meta_desc = $row['StreetAddress']." Offers ".($row['SpaceAvailableTotal'] ? number_format(preg_replace('/\D/', '', $row['SpaceAvailableTotal']))." sq. ft. of " : "")."warehouse space for rent in ".$row['CityName'].". View this property and all other warehouses for rent in ".$row['CityName'];
       }
}
elseif ($ref_file == 'all_cities'){
         if($row['created_from'] == 'json') {
	$title = 'Warehouses for Sale Throughout the US - View All Cities';
	$meta_desc = "Warehousespaces.com has listings in hundreds of cities throughout the US. View all of our cities and find the right warehouse for your business.";
         } else { 
	$title = 'Warehouses for Rent Throughout the US - View All Cities';
	$meta_desc = "Warehousespaces.com has listings in hundreds of cities throughout the US. View all of our cities and find the right warehouse for your business.";
        }
}
elseif ($ref_file == 'all_states'){
	$title = 'Warehouses for Lease Throughout the US - View All States';
	$meta_desc = "Warehousespaces.com has listings in many states throughout the US. View all of our cities and find the right warehouse for your business.";
}
elseif ($ref_file == 'searchresults'){
	$title .= (isset($_GET{'page'}) ? "Listings Page ".$_GET{'page'} : "View All Listings");
	//$title .= (isset($_GET{'page'}) ? "Listings Page ".$_GET{'page'} : "View All Listings");
  if($_REQUEST['rent_property'] == 'all') {
    $title = $q[0] . ' Warehouse Space for Rent - View All Listings';
    $meta_desc = "View all available for rent listings for warehouse space in ".$q[0] ." Find great deals on warehouses for rent and get a free property report for any listing.";

  } else if($_REQUEST['sell_property'] == 'all') {
    $title = $q[0] . ' Warehouse Space for Sale - View All Listings';
    $meta_desc = "View all available for sale listings for warehouse space in ".$q[0] ." Find great deals on warehouses for sale and get a free property report for any listing.";
  } else {
    $title = $q[0] . ' Warehouses - View Warehouse Space for Rent or Sale';
    $meta_desc = "View every listing for warehouse space in ".$q[0] ." with properties for sale or rent. Find the next space for your business and utilize our free commercial real estate services to get the best deal available.";
  }


}
elseif ($ref_file == 'about'){
	$title = 'About WarehouseSpaces.com - View 1000s of Warehouse Listings';
	$meta_desc = "Learn more about WarehouseSpaces.com. We're one of the nations largest warehouse listing sites with warehouses for rent and sale.";
}
elseif ($ref_file == 'contact'){
	$title = 'Request Property Report - WarehouseSpaces.com';
	$meta_desc = "Contact page for WarehouseSpaces.com. We're one of the nations largest warehouse listing sites.";
}

if($row['ListingIsActive'] == 'n') {
$redirect_url = 'https://warehousespaces.com/warehouses/United-States/'.$row['CityName'];
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
        <link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
        <!--<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600,800' rel='stylesheet' type='text/css'>-->

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
<!--        <script src="/js/datatables/js/jquery.dataTables.js"></script>
		<script src="/js/datatables/js/fnAddTr.js"></script>-->
        <script>window.jQuery || document.write('<script src="/js/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>
        <script src="/js/jquery.validate.bootstrap.js"></script>
        <!--<script src="http://cdn.leafletjs.com/leaflet-0.5.1/leaflet.js"></script>-->
        <script src="/js/jquery.fitvids.js"></script>
        <script src="/js/jquery.sequence-min.js"></script>
        <script src="/js/jquery.bxslider.js"></script>
        <script src="/js/main-menu.js"></script>
        <script src="/js/template.js"></script>
        <script src="/js/bootstrap3-typeahead.js"></script>
        <script src="/js/jquery.flexslider.js"></script>
        
        <script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-51049736-1', 'warehousespaces.com');
		ga('send', 'pageview');
        </script>
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
  <!--<script src='//d3c3cq33003psk.cloudfront.net/opentag-166428-catalystchat.js' async defer></script>-->
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
							    <a href="http://www.linkedin.com" title="LinkedIn"><img src="https://warehousespaces.com/images/linkedin_32.png" width="24" alt="LinkedIn"></a>
							</li>
							<li>	
								<a href="https://www.facebook.com" title="Join Us!"><img src="https://warehousespaces.com/images/facebook_32.png" width="24" alt="Facebook"></a>
							</li>
							<li>
							    <a href="https://twitter.com" title="Follow Us!"><img src="https://warehousespaces.com/images/twitter_32.png" width="24" alt="Twitter"></a>
							</li>
							<li>
							    <a href=""><img src="https://warehousespaces.com/images/rss_32.png" width="24" alt="RSS Feed"></a>
							</li>
			        	</ul>
					</div>
		        </div>-->
		        <nav id="mainmenu" class="mainmenu">
					<ul>
						<li class="logo-wrapper"><a href="/"><h3>Warehouse Spaces</h3></a> <!--<h4>Find Warehouse Space For Lease and For Sale</h4>!--></li>
						<li>
							<a href="tel:+1-855-989-5894"><h3>(855) 989-5894</h3></a>
						</li>
						<li>
							<a href="/about.php">ABOUT US</a>
						</li>
						<li>
						    <a href="/contact.php">FREE PROPERTY REPORT</a>
						</li>
						<!--<li class="spacing-icons" style="float: right;">
							    <a href="http://www.linkedin.com" title="LinkedIn"><img src="https://warehousespaces.com/images/linkedin_32.png" width="24" alt="LinkedIn"></a> <a href="https://www.facebook.com" title="Join Us!"><img src="https://warehousespaces.com/images/facebook_32.png" width="24" alt="Facebook"></a> <a href="https://twitter.com" title="Follow Us!"><img src="https://warehousespaces.com/images/twitter_32.png" width="24" alt="Twitter"></a> <a href=""><img src="https://warehousespaces.com/images/rss_32.png" width="24" alt="RSS Feed"></a></li>!-->
							
					</ul>
				</nav>
			</div>
		</div>