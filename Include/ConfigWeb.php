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
$sSERVERNAME = 'localhost';
$sUSER = 'gkjebenhaezer';
$sPASSWORD = 'gkjebenhaezer';
$sDATABASE = 'gkjebenhaezer';
$sRootPath='/gkjebenhaezer';
// Database Joomla
$sSERVERNAME2 = 'localhost';
$sUSER2 = 'gkjebenhaezer';
$sPASSWORD2 = 'gkjebenhaezer';
$sDATABASE2 = 'gkjebenhaezer';
//
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

function FilterInput($sInput,$type = 'string',$size = 1)
{
	if (strlen($sInput) > 0)
	{
		switch($type) {
			case 'string':
				// or use htmlspecialchars( stripslashes( ))
				$sInput = strip_tags(trim($sInput));
				if (get_magic_quotes_gpc())
        			$sInput = stripslashes($sInput);
				$sInput = mysql_real_escape_string($sInput);
				return $sInput;
			case 'htmltext':
				$sInput = strip_tags(trim($sInput),'<a><b><i><u>');
				if (get_magic_quotes_gpc())
        			$sInput = stripslashes($sInput);
				$sInput = mysql_real_escape_string($sInput);
				return $sInput;
			case 'char':
				$sInput = substr(trim($sInput),0,$size);
				if (get_magic_quotes_gpc())
        			$sInput = stripslashes($sInput);
				$sInput = mysql_real_escape_string($sInput);
				return $sInput;
			case 'int':
				return (int) intval(trim($sInput));
			case 'float':
				return (float) floatval(trim($sInput));
			case 'date':
				// Attempts to take a date in any format and convert it to YYYY-MM-DD format
				return date("Y-m-d",strtotime($sInput));
		}
	}
	else
	{
		return "";
	}
}

   function getWeeks($date, $rollover)
    {
        $cut = substr($date, 0, 8);
        $daylen = 86400;

        $timestamp = strtotime($date);
        $first = strtotime($cut . "00");
        $elapsed = ($timestamp - $first) / $daylen;

        $i = 1;
        $weeks = 1;

        for($i; $i<=$elapsed; $i++)
        {
            $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
            $daytimestamp = strtotime($dayfind);

            $day = strtolower(date("l", $daytimestamp));

            if($day == strtolower($rollover))  $weeks ++;
        }

        return $weeks-1;
    }

function date2Ind($str,$day) {
setlocale (LC_TIME, 'id_ID');

if ($day==1)
//dengan Hari
{$date = strftime( "%A, %d %B %Y", strtotime($str));}
elseif ($day==2)
{$date = strftime( "%d %B %Y", strtotime($str));}
elseif ($day==3)
{$date = strftime( "%d%b%Y", strtotime($str));}
elseif ($day==4)
{$date = strftime( "%A, %d%b%Y", strtotime($str));}
else
{$date = strftime( "%d %b %Y", strtotime($str));}

if ($str==''||$str=='0000-00-00')
{$date='-';}else{$date=$date;}
return $date;
} 

?>
