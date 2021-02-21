<?php
/*******************************************************************************
 *
 *  filename    : latlongkonverter.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function deg2dec($location){
list($lat, $long) = explode(',', $location);
//return $lat;
//global $lat;
//global $long;
#latitude
#grep degress
$latdeg = get_string_between($lat, "-", "°");
#grep minutes
$latmin = get_string_between($lat, "°", "'");
#grep second
$latsec = get_string_between($lat, "'", '"');

$declat= $latdeg+((($latmin*60)+($latsec))/3600);
//echo "latitude : ".$declat;

#longitude
#grep degress
$longdeg = get_string_between($long, "+", "°");
#grep minutes
$longmin = get_string_between($long, "°", "'");
#grep second
$longsec = get_string_between($long, "'", '"');

$declong= $longdeg+((($longmin*60)+($longsec))/3600);
//return $declong;
//echo "longitude : ". $declong;
//global $declat;
//global $declong;

return "&Glat=".stripslashes($lat)."&Glong=".stripslashes($long)."&Dlat=".stripslashes($declat)."&Dlong=".stripslashes($declong);
}


//Set the page title
$sPageTitle = gettext("Konverter - Koordinat Peta GKJ Bekti");

//Get the AssetID out of the querystring
$iKoordinat = FilterInput($_GET["Koordinat"]);
$Glat = $_GET["Glat"];
$Glong = $_GET["Glong"];
$Dlat = $_GET["Dlat"];
$Dlong = $_GET["Dlong"];




// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?AssetID= manually)
if (strlen($iKoordinat) > 0)
{
	$sSQL = "SELECT per_fam_ID FROM person_per WHERE per_ID = " . $_SESSION['iUserID'];
	$rsaset = RunQuery($sSQL);
	extract(mysql_fetch_array($rsaset));

	if (mysql_num_rows($rsaset) == 0)
	{
		Redirect("Menu.php");
		exit;
	}

	if ( !(
	       $_SESSION['bEditRecords'] ||
	       ($_SESSION['bEditSelf'] && $_SESSION['iUserID']) ||
	       ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
		  )
	   )
	{
		Redirect("Menu.php");
		exit;
	}
	
	

}
elseif (!$_SESSION['bAddRecords'])
{
	Redirect("Menu.php");
	exit;
}
// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
	extract ($aRow);
	$aSecurityType[$lst_OptionID] = $lst_OptionName;
}

if (isset($_POST["KoordinatSubmit"]) || isset($_POST["KoordinatSubmitAndAdd"]))
{

	//Get all the variables from the request object and assign them locally	

	$sKoordinat = $_POST["Koordinat"];
//echo $sKoordinat;	
$parsed = deg2dec($sKoordinat);

//echo $parsed;
Redirect("latlongkonverter.php?".$parsed);

	}
else {


}
require "Include/Header.php";



?>

<form method="post" action="latlongkonverter.php?Koordinat=<?php echo $Koordinat; ?>" name="latlongkonverter">

<table cellpadding="3" align="center" valign="top" >

	<tr>
		<td <?php if ($numCustomFields > 0) echo "colspan=\"2\""; ?> align="center">
			<input type="submit" class="icButton" value="<?php echo gettext("Konversikan"); ?>" name="KoordinatSubmit">
		</td>
	</tr>

	<tr>
		<td>
		<table cellpadding="0" valign="top" >
			<tr>
				<td colspan="2" align="center"><h3><?php echo gettext("Konversi Google Koordinat (DMS:Degress Minutes Second) ke Desimal"); ?></h3></td>
			</tr>
		
			<tr>
				<td class="LabelColumn"><?php echo gettext("Masukkan Koordinat Google Map :"); ?></td>
				<td class="TextColumn"><input type="text" name="Koordinat" id="Koordinat" ></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Latitude :"); ?></td>
				<td class="LabelColumn"><?php echo stripslashes($Glat); ?></td>
				<td class="LabelColumn"><?php echo stripslashes($Dlat); ?></td>
			</tr>
			<tr>
				<td class="LabelColumn"><?php echo gettext("Longitude :"); ?></td>
				<td class="LabelColumn"><?php echo stripslashes($Glong); ?></td>
				<td class="LabelColumn"><?php echo stripslashes($Dlong); ?></td>
			</tr>
		</table>
		</td>
	</tr>	



</table>
</form>
