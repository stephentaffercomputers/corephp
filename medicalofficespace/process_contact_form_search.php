<?php
session_start();

$toemail = 'feed@gcreas.com';
//$toemail = 'intern@taffercomputers.com'; 
$headers .= "Content-type: text/plain". "\r\n";

$name = $_POST['contact_name'];
$phone = $_POST['contact_phone'];
$email = $_POST['contact_email'];
$company = $_POST['contact_company'];
$locations = $_POST['contact_location'];
$property_type = $_POST['property_type'];
$sq_ft = $_POST['contact_sq_ft'];
$budget = $_POST['contact_budget'];
$comment = $_POST['contact_message'];
$square_feet = $_POST['square_feet_hidden'];
$budget = $_POST['budget_hidden'];
$source = $_POST['property_url'].($_SESSION["utm_source"] ? "?utm_source=".$_SESSION["utm_source"]."&utm_campaign=".$_SESSION["utm_campaign"] : "");
$search_item = $_POST['search_item'];
if (!$_POST['contact_firstname'])
{
	$referral = "will.gallahue@gmail.com";

	if (mail($toemail, 'Contact from MedicalOfficeSpace.us', "\nName: ".$name."\nSqare Feet: ".$square_feet."\nBudget: ".$budget."\nPhone: ".$phone. "\nEmail: ".$email. "\nCompany: ".$company. "\nLocations: ".$locations. "\nType: ".$property_type. " \nSize: ".$sq_ft. " \nBudget: ".$budget." \nComment: ".$comment." \nSource:  ".$source."\nReferral: ".$referral, $headers)) 
		//header("Location: thank-you.php");
		if(!empty($search_item))
		{
			header("Location: /medical-office-for-rent/United-States/".$search_item);
		}
		else
		{
			header("Location: thank-you.php");
		}
		
	else header("Location: ".$source);
}
else header("Location: ".$source);
?>