<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

function thumbnail($src, $maxw=190, $stampnow, $baseFileName, $upload_location) 
{
	$gdver = 2;

	if (!function_exists("imagecreate") || !function_exists("imagecreatetruecolor")) echo "No image create functions! (thumbnail function)";

	$size = @getimagesize ($src);
	
	if (!$size) echo("Image File Not Found - ".$src."! (thumbnail function)");
	else 
	{
		//CHECK THE IMAGE WIDTH
		if ($size[0] > $maxw) 
		{
			$newx = intval ($maxw);
			$newy = intval ($size[1] * ($maxw / $size[0]));
		} 
		else 
		{
			$newx = $size[0];  //WIDTH
			$newy = $size[1];  //HEIGHT
		}

		if ($gdver == 1) $destimg = imagecreate($newx, $newy);
		else $destimg = @imagecreatetruecolor($newx, $newy);;
		
		//CHECK THE FILE TYPE [1=GIF, 2=JPG, 3=PNG]
		if ($size[2] == 1)   //IF ITS A GIF IMAGE
		{
			if (!function_exists("imagecreatefromgif")) echo "Cannot Handle GIF Format!";
			else 
			{
				$sourceimg = imagecreatefromgif($src);

				if ($gdver == 1)
					imagecopyresized($destimg, $sourceimg, 0,0,0,0, $newx, $newy, $size[0], $size[1]);
				else
					@imagecopyresampled($destimg, $sourceimg, 0,0,0,0, $newx, $newy, $size[0], $size[1]);
			}
		}
		elseif ($size[2]==2)  //IF ITS A JPG IMAGE
		{
			set_time_limit(0);
		
			$sourceimg = imagecreatefromjpeg($src);

			if ($gdver == 1)
				imagecopyresized($destimg, $sourceimg, 0,0,0,0, $newx, $newy, $size[0], $size[1]);
			else
				@imagecopyresampled($destimg, $sourceimg, 0,0,0,0, $newx, $newy, $size[0], $size[1]);

			$JPG_QUALITY = 80; // output jpeg quality
			
			$newFileName = $baseFileName;
			$fileInfo = $upload_location."/".$newFileName;
			
			imagejpeg($destimg, $fileInfo, $JPG_QUALITY); // the imagejpeg function outputs the image to browser or file

			return array($newFileName, $newx, $newy); 
		}
		elseif ($size[2] == 3)  //IF ITS A PNG IMAGE
		{
			$sourceimg = imagecreatefrompng($src);

			if ($gdver == 1)
				imagecopyresized ($destimg, $sourceimg, 0,0,0,0, $newx, $newy, $size[0], $size[1]);
			else
				@imagecopyresampled ($destimg, $sourceimg, 0,0,0,0, $newx, $newy, $size[0], $size[1]);
		}
		else echo "Image Type Not Handled!";
	}

	imagedestroy ($destimg);
	imagedestroy ($sourceimg);
}

/** Include PHPExcel_IOFactory */
require_once 'Classes/PHPExcel/IOFactory.php';

if (!file_exists("warehouses.xls")) {
	exit("Please copy irvine_office.xls here first.\n");
}

$objPHPExcel = PHPExcel_IOFactory::load("warehouses.xls");

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

for ($i = 2600; $i <= count($sheetData); $i++)
{
	$counter = $i - 1;

	$data = $sheetData[$i];

	$listing_id = $counter."_".str_replace(" ", "_", $data['B']);
	$listing_id = str_replace("/", "_", $listing_id);

	if ($data['T'])
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $data['T']);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

		// Getting binary data
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		
		$image = curl_exec($ch);

		if ($image)
		{
			$src = '../images/property_photos/'.$listing_id.".jpg";

			$f = fopen($src, 'w');
			fwrite($f, $image);
			fclose($f);
		}

		curl_close($ch);

		$j = 1;
		
		if ($data['U'])
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $data['U']);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

			// Getting binary data
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		
			$image = curl_exec($ch);

			if ($image)
			{
				$src = '../images/property_photos/'.$listing_id."-".$j.".jpg";

				$f = fopen($src, 'w');
				fwrite($f, $image);
				fclose($f);

				$j++;
			}
			
			curl_close($ch);
		}

		if ($data['V'])
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $data['V']);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

			// Getting binary data
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		
			$image = curl_exec($ch);

			if ($image)
			{
				$src = '../images/property_photos/'.$listing_id."-".$j.".jpg";

				$f = fopen($src, 'w');
				fwrite($f, $image);
				fclose($f);

				$j++;
			}
			
			curl_close($ch);
		}

		if ($data['W'])
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $data['W']);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

			// Getting binary data
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		
			$image = curl_exec($ch);

			if ($image)
			{
				$src = '../images/property_photos/'.$listing_id."-".$j.".jpg";

				$f = fopen($src, 'w');
				fwrite($f, $image);
				fclose($f);

				$j++;
			}
			
			curl_close($ch);
		}
	}
}
?>