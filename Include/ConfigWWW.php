<?php
/*******************************************************************************
*
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
******************************************************************************/


// Database connection constants
$sSERVERNAME = 'gkjebenhaezer';
$sUSER = 'gkjebenhaezer';
$sPASSWORD = 'gkjebenhaezer';
$sDATABASE = 'gkjebenhaezer';

// Establish the database connection
$cnInfoCentral = mysql_connect($sSERVERNAME,$sUSER,$sPASSWORD)
        or die ('Cannot connect to the MySQL server because: ' . mysql_error());

mysql_select_db($sDATABASE)
        or die ('Cannot select the MySQL database because: ' . mysql_error());

$sql = "SHOW TABLES FROM $sDATABASE";
$tablecheck = mysql_num_rows( mysql_query($sql) );




?>