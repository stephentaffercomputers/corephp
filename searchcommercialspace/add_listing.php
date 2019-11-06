<?php 
include 'header.php';

function list_prop_types(){
	$sql_list_prop_types = "select distinct PropertyType, PropertySubType from listings order by PropertyType, PropertySubType;";

	try {
        $mysqli = mysqli_connect("localhost", "searchco_db", "7T2kHlRhuyLHFEKU", "searchco_db");
        $result = $mysqli->query($sql_list_prop_types);
        }
    catch(exception $e) { var_dump($e);}
    
    $p_type_dropdown ='<select name="listing_type" id="listing_type" class="form-control">';
    
	while ($row = mysqli_fetch_array($result)) $p_type_dropdown .= '<option value="'.$row['PropertySubType'].'"'.($_GET['prop-type'] == $row['PropertySubType'] ? ' selected' : '').'>'.$row['PropertyType'].'-'.$row['PropertySubType'].'</option>';
	
	$p_type_dropdown.= '</select>';
    print $p_type_dropdown;

}     
?>
		<div class="section section-breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1>Add Listing</h1>
					</div>
				</div>
			</div>
		</div>

	    <form method='post' enctype='multipart/form-data'  id='gform_1'  action='save_listing.php' role='form'>        
        <div class="section">
	        <div class="container">
<h3>Submission and Administration of Featured Properties</h3>
<br>
Customer has had and shall have the opportunity to submit to Featured Property descriptions, photographs, images, contact or other information (collectively, the "Submitted Content") for each featured listing on our site. You represent and warrant that (a) you own or have the full right, power and authority to grant to searchcommercialspace.com use of and rights in and to all Submitted Content that you upload, post, e-mail or otherwise transmit to searchcommercialspace.com; (b) your license of such content to searchcommercialspace.com hereunder does not, and the use or license of such content by searchcommercialspace.com to third parties will not, infringe any right or interest owned or possessed by any third party; and (c) there are no claims, judgments or settlements to be paid by you, or pending claims or litigation, relating to such content.
<br /><br />
With respect to all Submitted Content you have uploaded in the past or elect to upload in the future, post, e-mail or otherwise transmit to or via the Service, searchcommercialspace.com acknowledges that you retain any applicable ownership rights that you may have with respect to the Submitted Content. You nonetheless grant searchcommercialspace.com and its affiliates (including without limitation other searchcommercialspace.com companies) and their licensees a royalty-free, worldwide, perpetual, irrevocable, non-exclusive and fully sub-licensable right and license (through multiple tiers) to use, reproduce, adapt, perform, display, publish, translate, prepare derivative works from, modify, distribute, sell, and take any other action with respect to all such Submitted Content (in whole or part), whether submitted in the past or in the future, and/or to incorporate it in other works in any form, media, or technology now known or later developed. You further acknowledge and agree that searchcommercialspace.com may preserve any such Submitted Content, whether submitted in the past or in the future, and may also disclose such Submitted Content in its sole discretion (including without limitation within other products offered by searchcommercialspace.com and its affiliates, including other searchcommercialspace.com companies).
<br /><br />
Customer agrees not to submit any Submitted Content to searchcommercialspace.com unless the Customer has received all necessary rights and authorizations, including from the photographer or videographer and/or copyright owner of any photographs or videos, to publish and advertise the property listing on the Customer's website or on searchcommercialspace.com's website. Specifically, Customer will not submit a photograph if Customer received the photograph from a third party information provider under the terms of a license that does not allow posting of such photograph. The Company may, in its sole discretion but without any obligation to search for such, remove property listings that are alleged to have been submitted in violation of this provision. In addition, the Company may require additional evidence of compliance with this provision from Customers who are alleged to have submitted property listings, Submitted Content or other information in violation of this Agreement. The Company will, in its sole discretion, remove content and refuse service to, any Customer who repeatedly or knowingly violates this Agreement. Customer agrees to maintain accurate contact information (specifically, a valid phone number and email address) in order to submit and maintain active property listings on the searchcommercialspace.com website. Customer shall not use robot, spider or other automated service to submit listings on the searchcommercialspace.com website. Additionally, the Customer agrees to allow submitted property listing(s) and Submitted Content, or any part therein, to be searched, displayed, accessed, downloaded, copied, and otherwise referred to by users of the Customer's website, the searchcommercialspace.com website and other searchcommercialspace.com partner or affiliate websites, including without limitation other searchcommercialspace.com company websites. The Company shall have the sole authority to choose the manner in which any property listing will be searched, displayed, accessed, downloaded, copied, and otherwise used on the searchcommercialspace.com website and Company shall have the right to modify the property listing in the exercise of its rights under this Agreement. The Customer (a) represents and warrants that all properties and associated information provided by the Customer, including Submitted Content, will be accurate; (b) agrees not to post a property listing on the public searchcommercialspace.com under a name other than the individually named licensed real estate agents that have been engaged by the property owner to market the property under the terms of a duly executed listing agreement with the owner (shared accounts, e.g. listings@abcrealty.com are prohibited); (c) agrees to administer the properties provided by the Customer and maintain their accuracy at all times. The Company reserves, in a manner consistent with reasonable commercial business practices, the right to remove all or any part of the property listings posted on the Customer's website or on the searchcommercialspace.com website. The Customer is entirely responsible, and Company accepts no responsibility, for the Submitted Content from the Customer. While the Company shall take all reasonable efforts for data backup and business resumption, the Customer will be solely responsible for retaining back-up copies of all information, photographs and other materials it provides to searchcommercialspace.com. searchcommercialspace.com may add digital watermarks to certain parts of your property listing, including photographs. We add these digital watermarks to protect against the copying or further distribution of your photographs without your permission. Customer agrees that searchcommercialspace.com may adjust portions of the information contained within the Service (e.g., within property listings). Any such adjustments will have no material impact on the meaning and interpretation of property listings, but will serve as a means of uniquely identifying the property listings as having been supplied to the Customer. Customer accepts that this is a legitimate and lawful security precaution on the part of searchcommercialspace.com, and accepts further that in the event that any third party has access to property listings that can be identified as having Customer's unique adjustments a prima facie breach of security and of these Terms of Use on the part of Customer may be assumed by searchcommercialspace.com.<br /><br /> 
                <div class="form-group">
	                <label>I agree *</label><br />
					<input id="listing_agree" type="checkbox" value="Yes" /> Yes
				</div>
            </div>
        </div>
        <div class="section section-white" id="listing_form" style="display: none;">
	        <div class="container">
	        	<div class="col-sm-4">
					<div class="form-group">
					    <label for='listing_title' >Listing Title</label>
						<input class="form-control" name='listing_title' id='listing_title' type='text' value='' />
					</div>
					<div class="form-group">
						<label for='input_1_16' >Listing Street Address</label>
						<input class="form-control" name='listing_address' id='listing_address' type='text' value='' />
					</div>
					<div class="form-group">
						<label for='listing_city' >City</label>
						<input class="form-control" name='listing_city' id='listing_city' type='text' value='' />
					</div>
					<div class="form-group">
						<label for='listing_state' >State</label>
						<input class="form-control" name='listing_state' id='listing_state' type='text' value='' />
					</div>
					<div class="form-group">
						<label for='listing_zip' >Zip</label>
						<input class="form-control" name='listing_zip' id='listing_zip' type='text' value='' />
					</div>	
					<div class="form-group">
						<label for='listing_type' >Property Type</label>
						<? list_prop_types(); ?>
					</div>
					<div class="form-group">
						<label for='listing_size' >Maximum Size (sqft)</label>
						<input class="form-control" name='listing_size' id='listing_size' type='text' value='' />
					</div>
					<div class="form-group">
						<label for='listing_size' >Website URL</label>
						<input class="form-control" name='listing_url' id='listing_url' type='text' value='' />
					</div>	
					<div class="form-group">
						<label for='listing_description' >Description</label>
						<textarea name='listing_description' id='listing_description' class="form-control" rows='10' cols='50'></textarea>
					</div>
					<div class="form-group">
						<label for='listing_image' >Upload Image/Floor Plan</label>
						<input type='hidden' name='MAX_FILE_SIZE' value='134217728' /><input name='listing_image' id='listing_image' type='file' />
					</div>
					<div class="form-group">
						<label class='listing_have_right' >I have the right to use the uploaded image. *</label><br />
						<input name='listing_have_right' type='checkbox' value='Yes, I have rights to use the uploaded image.'  id='listing_have_right' required='' /> Yes, I have rights to use the uploaded image.<br />
						<h6><small>By checking this box you acknowledge that you own the copyright to the image or have permission to use it from the copyright holder.</h6></small>
					</div>
					<div class="form-group">
					    <button class="btn btn-block" type="submit" id="submit-form" name="submit_form" onclick=''>Submit</button>
						<input type='hidden' name='is_submit' value='1' />
					</div>
                </div>
                <div class="col-sm-4">
					<div class="form-group">
					    <label for='AgentName' >Contact Name</label>
						<input class="form-control" name='AgentName' id='AgentName' type='text' value='' required>
					</div>
					<div class="form-group">
						<label for='AgentEmail' >Email</label>
						<input class="form-control" name='AgentEmail' id='AgentEmail' type='text' value='' required>
					</div>
					<div class="form-group">
						<label for='AgentPhone' >Phone</label>
						<input class="form-control" name='AgentPhone' id='AgentPhone' type='text' value='' required>
					</div>
                </div>
			</div>
		</div>
		</form>	
<script type="text/javascript">
var checkbox = document.getElementById('listing_agree');
var delivery_div = document.getElementById('listing_form');

checkbox.onclick = function() {
   console.log(this);
   if(this.checked) {
     delivery_div.style['display'] = 'block';
   } else {
     delivery_div.style['display'] = 'none';
   }
};
</script>
<?php include 'footer.php'; ?>