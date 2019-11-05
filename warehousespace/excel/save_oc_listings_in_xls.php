<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'Classes/PHPExcel/Writer/Excel2007.php';

if (!file_exists("oc_warehouses.xls")) {
	exit("Please copy oc_warehouses.xls here first.\n");
}

$amenities_to_watch = array("\s?[\d']+\sclearance", "high clearance", "skylights", "yard", "secured yard", "fenced yard", "\s?\d*\soffices", "\s?\d*\sprivate offices", "free standing building", "freestanding building", "standalone building", "light industrial zoning", "freeway access", "highway access", "foil insulation", "automotive uses permitted", "\s\d*\s*dock high door", "\s\d*\s*ground level door", "480v", "3 phase power", "three phase power", "ample parking", "fire sprinklers", "ESFR sprinkler system", "central air", "air conditioned", "120v", "208v", "\s?[\d,]+\svolts?", "business park", "solar", "showroom", "truck parking", "freeway frontage", "ground level loading", "exterior platform", "\s?[\d,]+\samps?", "fully secured", "no CAM fees", "street view exposure", "railway access", "street frontage", "energy efficient lighting", "built in 2001", "built in 2002", "built in 2003", "built in 2004", "built in 2005", "built in 2006", "built in 2007", "built in 2008", "built in 2009", "built in 2010", "built in 2011", "built in 2012", "built in 2013", "built in 2014", "rail access", "heavy industrial zoning", "fully sprinklered", "flex space", "secure area", "clear span", "drive-in loading", "\s?[\d']+\sceilings?", "high ceilings?", "railroad access", "^\d\d+\sfoot clear height", "[^\d'][\d']+\sclear height", "grade level loading", "active rail", "air conditioned production area", "production area", "zoned MP", "zoned M-1", "concrete exterior walls", "professional landscaping", "attractive landspace", "truck staging area", "recently rehabbed", "recently renovated", "compressed air throughout", "suspended power outlets", "zoned C-2", "storefront", "concrete tilt up construction", "food grade", "zoned I-2", "zoned I-1", "temperature controlled warehouse", "humidity controlled warehouse", "temperature and humidity controlled warehouse");

$objPHPExcel = PHPExcel_IOFactory::load("oc_warehouses.xls");
$objPHPExcel_new = new PHPExcel();

$objPHPExcel_new->setActiveSheetIndex(0);
$objPHPExcel_new->getActiveSheet()->SetCellValue('A1', 'Property_Type');
$objPHPExcel_new->getActiveSheet()->SetCellValue('B1', 'Property_Name');
$objPHPExcel_new->getActiveSheet()->SetCellValue('C1', 'Price/SF/Month'); 
$objPHPExcel_new->getActiveSheet()->SetCellValue('D1', 'Price/SF/Year');
$objPHPExcel_new->getActiveSheet()->SetCellValue('E1', 'Price/Month');
$objPHPExcel_new->getActiveSheet()->SetCellValue('F1', 'Price/Year');
$objPHPExcel_new->getActiveSheet()->SetCellValue('G1', 'Address');
$objPHPExcel_new->getActiveSheet()->SetCellValue('H1', 'City');
$objPHPExcel_new->getActiveSheet()->SetCellValue('I1', 'StateProvince');
$objPHPExcel_new->getActiveSheet()->SetCellValue('J1', 'Zip');
$objPHPExcel_new->getActiveSheet()->SetCellValue('K1', 'Firm_Name');
$objPHPExcel_new->getActiveSheet()->SetCellValue('L1', 'Firm_City');
$objPHPExcel_new->getActiveSheet()->SetCellValue('M1', 'Property_Description');
$objPHPExcel_new->getActiveSheet()->SetCellValue('N1', 'Office_Class');
$objPHPExcel_new->getActiveSheet()->SetCellValue('O1', 'Available_Square_Feet');
$objPHPExcel_new->getActiveSheet()->SetCellValue('P1', 'Min_Lease_Rate');
$objPHPExcel_new->getActiveSheet()->SetCellValue('Q1', 'Lease_Type');
$objPHPExcel_new->getActiveSheet()->SetCellValue('R1', 'Property_Layout');
$objPHPExcel_new->getActiveSheet()->SetCellValue('S1', 'Suite_Number');
$objPHPExcel_new->getActiveSheet()->SetCellValue('T1', 'Primary_Photo');
$objPHPExcel_new->getActiveSheet()->SetCellValue('U1', 'Photo_2');
$objPHPExcel_new->getActiveSheet()->SetCellValue('V1', 'Photo_3');
$objPHPExcel_new->getActiveSheet()->SetCellValue('W1', 'Photo_4');
$objPHPExcel_new->getActiveSheet()->SetCellValue('X1', 'SitePlan_Photo');
$objPHPExcel_new->getActiveSheet()->SetCellValue('Y1', 'Transaction_Type');
$objPHPExcel_new->getActiveSheet()->SetCellValue('Z1', 'Property_Code');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AA1', 'Agent1_FirstName');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AB1', 'Agent1_LastName');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AC1', 'Agent2_FirstName');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AD1', 'Agent2_LastName');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AE1', 'Firm_Add1');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AF1', 'Firm_Add2');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AG1', 'Firm_Zip');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AH1', 'Coworking');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AI1', 'Executive_Suites');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AJ1', 'Move_In_Ready');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AK1', 'Lat');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AL1', 'Long');
$objPHPExcel_new->getActiveSheet()->SetCellValue('AM1', 'Url');																																																

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

$desc = array();

for ($i = 2; $i <= count($sheetData); $i++)
{
 	$counter = $i - 1;

	$data = $sheetData[$i];
	
	$objPHPExcel_new->getActiveSheet()->SetCellValue('A'.$i, $data['A']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('B'.$i, $data['B']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('C'.$i, $data['C']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('D'.$i, $data['D']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('E'.$i, $data['E']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('F'.$i, $data['F']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('G'.$i, $data['G']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('H'.$i, $data['H']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('I'.$i, $data['I']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('J'.$i, $data['J']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('K'.$i, $data['K']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('L'.$i, $data['L']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('N'.$i, $data['N']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('O'.$i, $data['O']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('P'.$i, $data['P']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('Q'.$i, $data['Q']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('R'.$i, $data['R']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('S'.$i, $data['S']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('T'.$i, $data['T']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('U'.$i, $data['U']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('V'.$i, $data['V']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('W'.$i, $data['W']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('X'.$i, $data['X']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('Y'.$i, $data['Y']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('Z'.$i, $data['Z']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AA'.$i, $data['AA']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AB'.$i, $data['AB']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AC'.$i, $data['AC']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AD'.$i, $data['AD']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AE'.$i, $data['AE']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AF'.$i, $data['AF']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AG'.$i, $data['AG']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AH'.$i, $data['AH']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AI'.$i, $data['AI']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AJ'.$i, $data['AJ']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AK'.$i, $data['AK']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AL'.$i, $data['AL']);
	$objPHPExcel_new->getActiveSheet()->SetCellValue('AM'.$i, $data['AM']);
	
	$desc[$i] = (rand(1, 2) == 1 ? "Located" : "Situated")." in ".trim($data['H']).(rand(1, 2) == 1 ? ", CA" : "")." this ".$data['O']." warehouse at ".$data['G'].($data['B'] == $data['G'] ? "" : " in the ".$data['B'])." offers".(rand(1, 2) == 1 ? " prospective" : "")." tenants".(rand(1, 2) == 1 ? " a number of" : " numerous")." amenities";

	$matches = array();
	
	foreach ($amenities_to_watch AS $value)
	{
		if (!preg_match("/".$value."/i", $data['M'], $match)) continue;
		
		if ($value == "recently rehabbed") $matches[] = "recently renovated";
		
		if ($value == "yard" && !preg_match("/secured yard/i", $data['M']) && !preg_match("/fenced yard/i", $data['M'])) $matches[] = "yard";
		elseif ($value != "yard") 
		{
			if ($value == "production area" && !preg_match("/air conditioned production area/i", $data['M'])) $matches[] = "production area";
			elseif ($value != "production area") $matches[] = trim($match[0]);
		}
	}
	
	if ($matches) {
		$desc[$i] .= " including:<br /><ul>";
		
		foreach ($matches AS $value) $desc[$i] .= "<li>".ucfirst($value)."</li>";
		
		$desc[$i] .= "</ul>";	
	}
	else $desc[$i] .= ".<br /><br />";
	
	$rate = preg_replace("/[^a-zA-Z0-9\$\.\/\s]/", "", trim($data['C'].$data['D'].$data['E'].$data['F']));
	
	if ($rate) 
	{
		$desc[$i] .= (rand(1, 2) == 1 ? "Starting" : "Asking")." rates for this ";
		
		$x = rand(1, 3);
		$desc[$i] .= ($x == 1 ? "property" : ($x == 2 ? "warehouse" : "building"))." are currently ".$rate." and if";
	}
	else $desc[$i] .= "If";
	
	$desc[$i] .= " you'd like more information about warehouse space at ".$data['G']." or any other listing in ".$data['H']." contact ".(rand(1, 2) == 1 ? "us" : "Synergy Real Estate Group")." today. ";
	
	$desc[$i] .= (rand(1, 2) == 1 ? "Request" : "Get")." ".(rand(1, 2) == 1 ? "a" : "your")." ".(rand(1, 2) == 1 ? "free" : "complimentary")." property report today and our ".(rand(1, 2) == 1 ? "experienced" : "veteran")." ";
	
	$x = rand(1, 3);
	$desc[$i] .= ($x == 1 ? "tenant reps" : ($x == 2 ? "tenant representatives" : "commercial real estate agents"))." will help you setup tours and show you the full ".(rand(1, 2) == 1 ? "market" : "warehouse")." inventory for ".$data['H'].".";
	
	$desc[$i] .= "<br /><br /><em>Listing Provided by</em> ".$data['AB']." ".$data['AA'].($data['AD'] ? " and ".$data['AD']." ".$data['AC'] : "")."<br />".$data['K'];

	$objPHPExcel_new->getActiveSheet()->SetCellValue('M'.$i, $desc[$i]);
}

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel_new);
$objWriter->save("oc_warehouses_new.xlsx");
?>