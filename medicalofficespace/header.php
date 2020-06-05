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
$title = 'US Medical Office Space for Rent - View 1000s of Listings';
$meta_desc = 'View available medical space for rent throughout the United States. Browse 1000s of listings to find the perfect space for you.';

$curr_url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];	

if (isset($_GET["q"])) $q = explode(",", $_GET["q"]);

if (sizeof($q) == 1 ) $title = array_search(trim(str_replace("-", " ", $q[0])), $states).' Medical Space for Rent - ';

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

			$title = $cityname.' Medical Space for Rent ';

			$where_clause = ' trim(StreetAddress)= "'. trim($exp_str[0]).'"';
			$where_clause .= ' and trim(CityName)= "'.trim($exp_str[1]).'"';
		}
		else $where_clause = ' trim(StreetAddress)= "'.trim($_GET["id"]).'" ';
	}

	$search_sql_stmt = 'select * from medical_listing where '.$where_clause.';';
	try {
		$mysqli = mysqli_connect("localhost", "medicalo_db", "7CuDaKPPeXgpKT46", "medicalo_db");
		$result = $mysqli->query($search_sql_stmt);
		$row = mysqli_fetch_array($result);
		//var_dump($search_sql_stmt);
		if ($row['ListingIsActive'] == 'n')
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: https://medicalofficespace.us/medical-office-for-rent/United-States/".$row['StateProvCode']."/".$row['CityName']);
		}

		$title = $row['CityName'].' Medical Office Space for Rent - ' ;//.' '.$row['StateProvCode'];
	
		$latitude = $row['Latitude'];
		$longitude = $row['Longitude'];
	}
	catch(exception $e) {var_dump($e);}
}

$ref_file = basename($_SERVER["SCRIPT_FILENAME"], '.php');

if ($ref_file == 'property_details'){ //indivudal listing
	$title .= $row['StreetAddress'];
	$meta_desc = $row['StreetAddress']." Offers ".($row['SpaceAvailableTotal'] ? number_format(preg_replace('/\D/', '', $row['SpaceAvailableTotal']))." sq. ft. of " : "")."medical space for rent in ".$row['CityName'].". View this property and all other medical office spaces for lease in ".$row['CityName'];
}
elseif ($ref_file == 'all_cities'){
	$title = 'Medical Offices for Rent in the US - View All Cities';
	$meta_desc = "Medicalofficespace.us has listings in hundreds of cities throughout the US. View all of our cities and find the right medical office for your business.";
}
elseif ($ref_file == 'all_states'){
	$title = 'Medical Offices for Lease in the US - View All States';
	$meta_desc = "Medicalofficespace.us has listings in many states throughout the US. View all of our cities and find the right medical office for your business.";
}
elseif ($ref_file == 'searchresults'){
//	$title = array_search(trim(str_replace("-", " ", $q[0])), $states)." Medical Space for Rent - ".(isset($_GET{'page'}) ? "Listings Page ".$_GET{'page'} : "View Medical Listings");
//	$meta_desc = "View ".(isset($_GET{'page'}) ? "page ".$_GET{'page'}." of " : "")."available medical listings in ".array_search(trim(str_replace("-", " ", $q[0])), $states)." and get a free property report. Find great deals and opportunities on medical space for rent in ".array_search(trim(str_replace("-", " ", $q[0])), $states).".";
	$title = $q[0]." Medical Space for Rent - ".(isset($_GET{'page'}) ? "Listings Page ".$_GET{'page'} : "View Medical Listings");
	$meta_desc = "View ".(isset($_GET{'page'}) ? "page ".$_GET{'page'}." of " : "")."available medical listings in ".$q[0]." and get a free property report. Find great deals and opportunities on medical space for rent in ".$q[0].".";

}
elseif ($ref_file == 'about'){
	$title = 'About MedicalOfficeSpace.us - View 1000s of Medical Listings';
	$meta_desc = "Learn more about MedicalOfficeSpace.us. We're one of the nations largest medical office listing sites with medical spaces for rent and sale.";
}
elseif ($ref_file == 'contact'){
	$title = 'Contact Us - MedicalOfficeSpace.us';
	$meta_desc = "Contact page for MedicalOfficeSpace.us. We're one of the nations largest medical listing sites.";
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
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600,800' rel='stylesheet' type='text/css'>

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
        <!--<script src="/js/datatables/js/jquery.dataTables.js"></script>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>        
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41314429-8', 'auto');
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
		        <nav id="mainmenu" class="mainmenu">
					<ul>
						<li class="logo-wrapper"><a href="/"><h3>Medical Office Space</h3></a> <!--<h4>Find Medical Office Space For Lease and For Sale</h4>!--></li>
						<li>
							<a href="tel:+1-800-481-0120"><h3>(800) 481-0120</h3></a>
						</li>
						<li>
							<a href="/about.php">ABOUT&nbsp;US</a>
						</li>
						<li>
						    <a href="/contact.php">REQUEST&nbsp;PROPERTY&nbsp;REPORT</a>
						</li>
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
		
		