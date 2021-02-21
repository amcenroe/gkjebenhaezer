<?php
/*******************************************************************************
*
* filename : BulananReport.php
* last change : 2003-01-29
*
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
******************************************************************************/


$refresh = microtime() ;
// Include the function library
//require "Include/Config.php";
//require "Include/Functions.php";

// Database connection constants
$sSERVERNAME = 'gkjebenhaezer';
$sUSER = 'gkjebenhaezer';
$sPASSWORD = 'gkjebenhaezer;';
$sDATABASE = 'gkjebenhaezer';
$sRootPath='/gkjebenhaezer';
$sPort='';
$bHTTPSOnly=FALSE;
$sSharedSSLServer='';
$sHTTP_Host=$_SERVER['HTTP_HOST'];
// Establish the database connection
$cnInfoCentral = mysql_connect($sSERVERNAME,$sUSER,$sPASSWORD)
        or die ('Cannot connect to the MySQL server because: ' . mysql_error());

mysql_select_db($sDATABASE)
        or die ('Cannot select the MySQL database because: ' . mysql_error());

$sql = "SHOW TABLES FROM $sDATABASE";
$tablecheck = mysql_num_rows( mysql_query($sql) );

if (!$tablecheck) {
    die ("There are no tables in installed in your database.  Please install the tables.");
}

// Avoid consecutive slashes when $sRootPath = '/'
if (strlen($sRootPath) < 2) $sRootPath = '';

// Some webhosts make it difficult to use DOCUMENT_ROOT.  Define our own!
$sDocumentRoot = dirname(dirname(__FILE__));

// Initialize the session
session_start();

$version = mysql_fetch_row(mysql_query("SELECT version()"));

if (substr($version[0],0,3) >= "4.1") {
	mysql_query("SET NAMES 'utf8'");
}




function RunQuery($sSQL, $bStopOnError = true)
{
    global $cnInfoCentral;
    global $debug;

    if ($result = mysql_query($sSQL, $cnInfoCentral))
        return $result;
    elseif ($bStopOnError)
    {
        if ($debug)
            die(gettext("Query TIDAK BISA dijalankan.") . "<p>$sSQL<p>" . mysql_error());
        else
            die("Database ERROR atau Data SALAH");
    }
    else
        return FALSE;
}

function AlternateRowStyle($sCurrentStyle)
{
	if ($sCurrentStyle == "RowColorA") {
		return "RowColorB";
	} else {
		return "RowColorA";
	}
}








?>