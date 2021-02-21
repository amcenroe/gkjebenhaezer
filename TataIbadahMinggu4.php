<?php
/*******************************************************************************
 *
 *  filename    : TataIbadahMinggu4.php
 *  copyright   : 2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/
	if (!isset($_SESSION['iUserID']))
	{
	require "Include/ConfigWeb.php";

	}else
	{
//Include the function library
require "Include/Config.php";
require "Include/Functions.php";
}
// Get the person ID from the querystring
$iTGL = FilterInput($_GET["TGL"],'date');
$iBHS = FilterInput($_GET["BHS"],'string');



// Get this LiturgiData
$sSQL = "select * from LiturgiGKJBekti 
		 WHERE Tanggal = '" . $iTGL . "' AND Bahasa  = '" .$iBHS ."'" ;

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

//Set the page title
				$TGL=$Tanggal;
				$MGG=getWeeks("$Tanggal","sunday");
				$TGLIND=date2Ind($Tanggal,2);
				 
				
$sPageTitle = gettext("Tata Ibadah Minggu Keempat $TGLIND");


	if ($iBHS=="Indonesia")
	{
	require "TataIbadahMinggu4TextIndonesia.php";
	}else
	{
	require "TataIbadahMinggu4TextJawa.php";
	}
	
?>


