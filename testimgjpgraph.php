<?php
/*******************************************************************************
*
* filename : LapBulanan.php
* last change : 2003-01-29
*
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  2011 Erwin Pratama for GKJ Bekasi Timur (www.gljbekasitimur.org)
******************************************************************************/

$refresh = microtime() ;
// Include the function library
require "Include/Config.php";
require "Include/Functions.php";
//require "Include/Header-Print.php";

$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;

//Print_r ($_SESSION);
		$logvar1 = "Report";
		$logvar2 = "Executive Summary";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

$iTahun = $_GET["Tahun"];
if ($iTahun == '' ) {
  $TGLSKR = date("Y-m-d");
  $THNSKR = date("Y");
} else {
  $TGLSKR = $iTahun . "-12-31";
  $THNSKR = $iTahun; 
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Testing JPGRAPHy</title>


</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >

		<?php
				echo "<img src=\"streaming.php?&amp;$refresh \" width=\"360\" ><br>" ;
		?>


</body>


