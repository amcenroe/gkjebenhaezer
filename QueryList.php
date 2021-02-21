<?php
/*******************************************************************************
 *
 *  filename    : QueryList.php
 *  last change : 2003-01-07
 *  website     : http://www.infocentral.org
 *  copyright   : Copyright 2001, 2002 Deane Barker
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";

//Set the page title
$sPageTitle = gettext("Daftar Query");

$sSQL = "SELECT * FROM query_qry ORDER BY qry_Name";
$rsQueries = RunQuery($sSQL);

$aFinanceQueries = explode(',', $aFinanceQueries);

require "Include/Header.php";

		$logvar1 = "Report";
		$logvar2 = "Query List";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);

if ($_SESSION['bAdmin'])
{
	echo "<p align=\"center\"><a href=\"QuerySQL.php\">" . gettext("Jalankan Perintah SQL Query Manual") . "</a></p>";
}

while ($aRow = mysql_fetch_array($rsQueries))
{

	extract($aRow);

	// Filter out finance-related queries if the user doesn't have finance permissions
	if ($_SESSION['bFinance'] || !in_array($qry_ID,$aFinanceQueries))
	{
		// Display the query name and description
		echo "<p>";
		echo "<a href=\"QueryView.php?QueryID=" . $qry_ID . "\">" . $qry_Name . "</a>";
		echo "<br>";
		echo $qry_Description;
		echo "</p>";
	}
}

require "Include/Footer.php";

?>
