<?php
/*******************************************************************************
*
*  filename    : /Include/Functions.php
*  website     : http://www.churchdb.org
*  copyright   : Copyright 2001-2003 Deane Barker, Chris Gebhardt
*
*  Additional Contributors:
*  2006 Ed Davis
*  2010 Erwin Pratama for GKJ Bekasi Timur
*
*
*  Copyright Contributors
*
*  ChurchInfo is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  This file best viewed in a text editor with tabs stops set to 4 characters
*
******************************************************************************/
// Initialization common to all ChurchInfo scripts

// Set error reporting
if ($debug == true)
	Error_reporting ( E_ALL ^ E_NOTICE);
else
	error_reporting(0);

//
// Basic security checks:
//
if (!$bSuppressSessionTests)  // This is used for the login page only.
{
	// Basic security: If the UserID isn't set (no session), redirect to the login page
	if (!isset($_SESSION['iUserID']))
	{
		Redirect("Default.php");
		exit;
	}

	// Check for login timeout.  If login has expired, redirect to login page
	if ($sSessionTimeout > 0)
	{
		if ((time() - $_SESSION['tLastOperation']) > $sSessionTimeout)
		{
			Redirect("Default.php?timeout");
			exit;
		}
		else {
			$_SESSION['tLastOperation'] = time();
		}
	}

	// If this user needs to change password, send to that page
	if ($_SESSION['bNeedPasswordChange'] && !isset($bNoPasswordRedirect))
	{
		Redirect("UserPasswordChange.php?PersonID=" . $_SESSION['iUserID']);
		exit;
	}

    // Check if https is required, if so, make sure we're using https.
    // Redirect back to login page using https if required.
    // This prevents someone from accessing via http by typing in the URL
    if ($bHTTPSOnly)
    {
        if(!($_SERVER['HTTPS'] == 'on'))
        {
            $_SESSION['bSecureServer'] = TRUE;
            Redirect('Default.php');
            exit;
        }
    }

    // Make sure visitor got here using a valid URL.
    // If not, try to redirect to correct page, else "Menu.php"
    // This check will only be performed if $_SERVER['PHP_SELF'] is set
    if (isset($_SERVER['PHP_SELF'])) {
        $sPathExtension = substr($_SERVER['PHP_SELF'],strlen($sRootPath));
        $sFullPath = str_replace( '\\','/',$sDocumentRoot.$sPathExtension);
        if (!(file_exists($sFullPath) && is_readable($sFullPath))) {
            $sNewPath = substr($sFullPath,0,strpos($sFullPath,'.php')+4);
            if (file_exists($sNewPath) && is_readable($sNewPath)) {
                $sPage = substr($sNewPath,strrpos($sNewPath,'/')+1);
                Redirect($sPage);
            } else {
                Redirect('Menu.php');
            }
            exit;
        }
    }
}
// End of basic security checks

// If Magic Quotes is turned off, do the same thing manually..
if (!$_SESSION['bHasMagicQuotes'])
{
	foreach ($_REQUEST as $key=>$value) $value = addslashes($value);
}

// Constants
$aPropTypes = array(
	1 => gettext("Benar / Salah"),
	2 => gettext("Tanggal"),
	3 => gettext("Text Field (50 char)"),
	4 => gettext("Text Field (100 char)"),
	5 => gettext("Text Field (long)"),
	6 => gettext("Tahun"),
	7 => gettext("Season"),
	8 => gettext("Nomor"),
	9 => gettext("Jemaat dari Kelompok"),
	10 => gettext("Uang"),
	11 => gettext("Nomor Telepon"),
	12 => gettext("Daftar Drop-Down Custom")
);

// Are they adding an entire group to the cart?
if (isset($_GET["AddGroupToPeopleCart"])) {
	AddGroupToPeopleCart(FilterInput($_GET["AddGroupToPeopleCart"],'int'));
	$sGlobalMessage = gettext("Group successfully added to the Cart.");
}

// Are they removing an entire group from the Cart?
if (isset($_GET["RemoveGroupFromPeopleCart"])) {
	RemoveGroupFromPeopleCart(FilterInput($_GET["RemoveGroupFromPeopleCart"],'int'));
	$sGlobalMessage = gettext("Group successfully removed from the Cart.");
}

// Are they adding a person to the Cart?
if (isset($_GET["AddToPeopleCart"])) {
	AddToPeopleCart(FilterInput($_GET["AddToPeopleCart"],'int'));
	$sGlobalMessage = gettext("Selected record successfully added to the Cart.");
}

// Are they removing a person from the Cart?
if (isset($_GET["RemoveFromPeopleCart"])) {
	RemoveFromPeopleCart(FilterInput($_GET["RemoveFromPeopleCart"],'int'));
	$sGlobalMessage = gettext("Selected record successfully removed from the Cart.");
}

// Are they emptying their cart?
if ($_GET["Action"] == "EmptyCart") {
	unset($_SESSION['aPeopleCart']);
	$sGlobalMessage = gettext("Your cart has been successfully emptied.");
}

if (isset($_POST["BulkAddToCart"])) {

	$aItemsToProcess = explode(",",$_POST["BulkAddToCart"]);

	if (isset($_POST["AndToCartSubmit"]))
	{
		if (isset($_SESSION['aPeopleCart']))
			$_SESSION['aPeopleCart'] = array_intersect($_SESSION['aPeopleCart'],$aItemsToProcess);
	}
	elseif (isset($_POST["NotToCartSubmit"]))
	{
		if (isset($_SESSION['aPeopleCart']))
			$_SESSION['aPeopleCart'] = array_diff($_SESSION['aPeopleCart'],$aItemsToProcess);
	}
	else
	{
		for ($iCount = 0; $iCount < count($aItemsToProcess); $iCount++) {
			AddToPeopleCart(str_replace(",","",$aItemsToProcess[$iCount]));
		}
		$sGlobalMessage = $iCount . " " . gettext("item(s) added to the Cart.");
	}
}

//
// Some very basic functions that all scripts use
//

//$random1=rand(100000000,999999999);
//$random2=microtime(TRUE);
//$refresh=generateRandomString()."".$random1."".$random2;

$refresh = microtime(true) ;
$refresh=$refresh+$GID+randint();

function RedirectURL($sRelativeURL)
// Convert a relative URL into an absolute URL and return absolute URL.
{
    global $sRootPath;
    global $sDocumentRoot;
    global $sSharedSSLServer;
    global $sHTTP_Host;
    global $bHTTPSOnly;
    global $sPort;

    // Check if port number needs to be included in URL
    if ($sPort)
        $sPortString = ":$sPort";
    else
        $sPortString = '';

    // http or https ?
	if ($_SESSION['bSecureServer'] || $bHTTPSOnly)
        $sRedirectURL = 'https://';
	else
		$sRedirectURL = 'http://';

    // Using a shared SSL certificate?
    if (strlen($sSharedSSLServer) && $_SESSION['bSecureServer'])
        $sRedirectURL .= $sSharedSSLServer . $sPortString . '/' . $sHTTP_Host;
    else
        $sRedirectURL .= $sHTTP_Host . $sPortString;

    // If root path is already included don't add it again
    if (!$sRootPath) {
        // This check is not meaningful if installed in top level web directory
        $sRelativeURLPath = '/' . $sRelativeURL;
    } elseif (strpos($sRelativeURL, $sRootPath)===FALSE) {
        // sRootPath is not in sRelativeURL.  Add it
        $sRelativeURLPath = $sRootPath . '/' . $sRelativeURL;
    } else {
        // sRootPath already in sRelativeURL.  Don't add
        $sRelativeURLPath = $sRelativeURL;
    }

    // Test if file exists before redirecting.  May need to remove
    // query string first.
    $iQueryString = strpos($sRelativeURLPath,'?');
    if ($iQueryString) {
        $sPathExtension = substr($sRelativeURLPath,0,$iQueryString);
    } else {
        $sPathExtension = $sRelativeURLPath;
    }

    // $sRootPath gets in the way when verifying if the file exists
    $sPathExtension = substr($sPathExtension,strlen($sRootPath));
    $sFullPath = str_replace('\\','/',$sDocumentRoot.$sPathExtension);

    // With the query string removed we can test if file exists
    if (file_exists($sFullPath) && is_readable($sFullPath)) {
        $sRedirectURL .= $sRelativeURLPath;
    } else {
        $sErrorMessage = 'Fatal Error: Cannot access file: '.$sFullPath."<br>\n";
        $sErrorMessage .= "\$sPathExtension = $sPathExtension<br>\n";
        $sErrorMessage .= "\$sDocumentRoot = $sDocumentRoot<br>\n";

        die ($sErrorMessage);
    }

    return $sRedirectURL;
}

// Convert a relative URL into an absolute URL and redirect the browser there.
function Redirect($sRelativeURL)
{
    $sRedirectURL = RedirectURL($sRelativeURL);
	header("Location: " . $sRedirectURL);
	exit;
}

// Returns the current fiscal year
function CurrentFY()
{
	global $iFYMonth;

	$yearNow = date ("Y");
	$monthNow = date ("m");
	$FYID = $yearNow - 1996;
	if ($monthNow >= $iFYMonth)
		$FYID += 1;
	return ($FYID);
}

// PrintFYIDSelect: make a fiscal year selection menu.
function PrintFYIDSelect ($iFYID, $selectName)
{
	echo "<select name=\"" . $selectName . "\">";

	echo "<option value=\"0\">" . gettext("Select Fiscal Year") . "</option>";

	for ($fy = 1; $fy < CurrentFY() + 2; $fy++) {
		echo "<option value=\"" . $fy . "\"";
		if ($iFYID == $fy)
			echo " selected";
		echo ">";
		echo MakeFYString ($fy);
	}
	echo "</select>";
}

// Formats a fiscal year string
function MakeFYString ($iFYID)
{
	global $iFYMonth;
	$monthNow = date ("m");

	if ($iFYMonth == 1)
		return (1996 + $iFYID);
	else
		return (1995 + $iFYID . "/" . substr (1996 + $iFYID, 2, 2));
}

// Runs an SQL query.  Returns the result resource.
// By default stop on error, unless a second (optional) argument is passed as false.
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

// Sanitizes user input as a security measure
// Optionally, a filtering type and size may be specified.  By default, strip any tags from a string.
// Note that a database connection must already be established for the mysql_real_escape_string function to work.
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

//
// Adds a person to a group with specified role.
// Returns false if the operation fails. (such as person already in group)
//
function AddToGroup($iPersonID, $iGroupID, $iRoleID)
{
	global $cnInfoCentral;

	// Was a RoleID passed in?
	if ($iRoleID == 0) {
		// No, get the Default Role for this Group
		$sSQL = "SELECT grp_DefaultRole FROM group_grp WHERE grp_ID = " . $iGroupID;
		$rsRoleID = RunQuery($sSQL);
		$Row = mysql_fetch_row($rsRoleID);
		$iRoleID = $Row[0];
	}

	$sSQL = "INSERT INTO person2group2role_p2g2r (p2g2r_per_ID, p2g2r_grp_ID, p2g2r_rle_ID) VALUES (" . $iPersonID . ", " . $iGroupID . ", " . $iRoleID . ")";
	$result = RunQuery($sSQL,false);

	if ($result)
	{
		// Check if this group has special properties
		$sSQL = "SELECT grp_hasSpecialProps FROM group_grp WHERE grp_ID = " . $iGroupID;
		$rsTemp = RunQuery($sSQL);
		$rowTemp = mysql_fetch_row($rsTemp);
		$bHasProp = $rowTemp[0];

		if ($bHasProp == 'true')
		{
			$sSQL = "INSERT INTO `groupprop_" . $iGroupID . "` (`per_ID`) VALUES ('" . $iPersonID . "')";
			RunQuery($sSQL);
		}
	}

	return $result;
}

function RemoveFromGroup($iPersonID, $iGroupID)
{

  $sSQL = "UPDATE `person_per` SET `per_WorkPhone` = \"\" WHERE `per_ID` = ".$iPersonID."  LIMIT 1 ";
  $rsUpdateKelompokPersonPer = RunQuery($sSQL);
  
	$sSQL = "DELETE FROM person2group2role_p2g2r WHERE p2g2r_per_ID = " . $iPersonID . " AND p2g2r_grp_ID = " . $iGroupID;
	RunQuery($sSQL);

	// Check if this group has special properties
	$sSQL = "SELECT grp_hasSpecialProps FROM group_grp WHERE grp_ID = " . $iGroupID;
	$rsTemp = RunQuery($sSQL);
	$rowTemp = mysql_fetch_row($rsTemp);
	$bHasProp = $rowTemp[0];

	if ($bHasProp == 'true')
	{
		$sSQL = "DELETE FROM `groupprop_" . $iGroupID . "` WHERE `per_ID` = '" . $iPersonID . "'";
		RunQuery($sSQL);
	}

	// Reset any group specific property fields of type "Person from Group" with this person assigned
	$sSQL = "SELECT grp_ID, prop_Field FROM groupprop_master WHERE type_ID = 9 AND prop_Special = " . $iGroupID;
	$result = RunQuery($sSQL);
	while ($aRow = mysql_fetch_array($result))
	{
		$sSQL = "UPDATE groupprop_" . $aRow['grp_ID'] . " SET " . $aRow['prop_Field'] . " = NULL WHERE " . $aRow['prop_Field'] . " = " . $iPersonID;
		RunQuery($sSQL);
	}

	// Reset any custom person fields of type "Person from Group" with this person assigned
	$sSQL = "SELECT custom_Field FROM person_custom_master WHERE type_ID = 9 AND custom_Special = " . $iGroupID;
	$result = RunQuery($sSQL);
	while ($aRow = mysql_fetch_array($result))
	{
		$sSQL = "UPDATE person_custom SET " . $aRow['custom_Field'] . " = NULL WHERE " . $aRow['custom_Field'] . " = " . $iPersonID;
		RunQuery($sSQL);
	}
}

//
// Adds a volunteer opportunity assignment to a person
//
function AddVolunteerOpportunity($iPersonID, $iVolID)
{
	$sSQL = "INSERT INTO person2volunteeropp_p2vo (p2vo_per_ID, p2vo_vol_ID) VALUES (" . $iPersonID . ", " . $iVolID . ")";
	$result = RunQuery($sSQL,false);
	return $result;
}

function RemoveVolunteerOpportunity($iPersonID, $iVolID)
{
	$sSQL = "DELETE FROM person2volunteeropp_p2vo WHERE p2vo_per_ID = " . $iPersonID . " AND p2vo_vol_ID = " . $iVolID;
	RunQuery($sSQL);
}

function ConvertCartToString($aCartArray)
{
	// Implode the array
	$sCartString = implode(",", $aCartArray);

	// Make sure the comma is chopped off the end
	if (substr($sCartString, strlen($sCartString) - 1, 1) == ",") {
		$sCartString = substr($sCartString, 0, strlen($sCartString) - 1);
	}

	// Make sure there are no duplicate commas
	$sCartString = str_replace(",,", "", $sCartString);

	return $sCartString;
}


/******************************************************************************
 * Returns the proper information to use for a field.
 * Person info overrides Family info if they are different.
 * If using family info and bFormat set, generate HTML tags for text color red.
 * If neither family nor person info is available, return an empty string.
 *****************************************************************************/

function SelectWhichInfo($sPersonInfo, $sFamilyInfo, $bFormat = false)
{
	global $bShowFamilyData;

	if ($bShowFamilyData) {

		if ($bFormat) {
			$sFamilyInfoBegin = "<span style=\"color: red;\">";
			$sFamilyInfoEnd = "</span>";
		}

		if ($sPersonInfo != "") {
			return $sPersonInfo;
		} elseif ($sFamilyInfo != "") {
			if ($bFormat) {
				return $sFamilyInfoBegin . $sFamilyInfo . $sFamilyInfoEnd;
			} else {
				return $sFamilyInfo;
			}
		} else {
			return "";
		}

	} else {
		if ($sPersonInfo != "")
			return $sPersonInfo;
		else
			return "";
	}
}

//
// Returns the correct address to use via the sReturnAddress arguments.
// Function value returns 0 if no info was given, 1 if person info was used, and 2 if family info was used.
// We do address lines 1 and 2 in together because seperately we might end up with half family address and half person address!
//
function SelectWhichAddress(&$sReturnAddress1, &$sReturnAddress2, $sPersonAddress1, $sPersonAddress2, $sFamilyAddress1, $sFamilyAddress2, $bFormat = false)
{
	global $bShowFamilyData;

	if ($bShowFamilyData) {

		if ($bFormat) {
			$sFamilyInfoBegin = "<span style=\"color: red;\">";
			$sFamilyInfoEnd = "</span>";
		}

		if ($sPersonAddress1 || $sPersonAddress2) {
				$sReturnAddress1 = $sPersonAddress1;
				$sReturnAddress2 = $sPersonAddress2;
				return 1;
		} elseif ($sFamilyAddress1 || $sFamilyAddress2) {
			if ($bFormat) {
				if ($sFamilyAddress1)
					$sReturnAddress1 = $sFamilyInfoBegin . $sFamilyAddress1 . $sFamilyInfoEnd;
				else $sReturnAddress1 = "";
				if ($sFamilyAddress2)
					$sReturnAddress2 = $sFamilyInfoBegin . $sFamilyAddress2 . $sFamilyInfoEnd;
				else $sReturnAddress2 = "";
				return 2;
			} else {
				$sReturnAddress1 = $sFamilyAddress1;
				$sReturnAddress2 = $sFamilyAddress2;
				return 2;
			}
		} else {
			$sReturnAddress1 = "";
			$sReturnAddress2 = "";
			return 0;
		}

	} else {
		if ($sPersonAddress1 || $sPersonAddress2) {
			$sReturnAddress1 = $sPersonAddress1;
			$sReturnAddress2 = $sPersonAddress2;
			return 1;
		} else {
			$sReturnAddress1 = "";
			$sReturnAddress2 = "";
			return 0;
		}
	}
}

function ChopLastCharacter($sText)
{
	return substr($sText,0,strlen($sText) - 1);
}

function AddToPeopleCart($sID)
{
	// make sure the cart array exists
	if(isset($_SESSION['aPeopleCart']))
	{
		if (!in_array($sID, $_SESSION['aPeopleCart'], false))
        {
			$_SESSION['aPeopleCart'][] = $sID;
		}
	}
	else
		$_SESSION['aPeopleCart'][] = $sID;
}

function AddArrayToPeopleCart($aIDs)
{
    if(is_array($aIDs)) // Make sure we were passed an array
    {
        foreach($aIDs as $value)
        {
            AddToPeopleCart($value);
        }
    }
}


// Add group to cart
function AddGroupToPeopleCart($iGroupID)
{
	//Get all the members of this group
	$sSQL =	"SELECT p2g2r_per_ID FROM person2group2role_p2g2r " .
			"WHERE p2g2r_grp_ID = " . $iGroupID;
	$rsGroupMembers = RunQuery($sSQL);

	//Loop through the recordset
	while ($aRow = mysql_fetch_array($rsGroupMembers))
	{
		extract($aRow);

		//Add each person to the cart
		AddToPeopleCart($p2g2r_per_ID);
	}
}

function IntersectArrayWithPeopleCart($aIDs)
{
    if(isset($_SESSION['aPeopleCart']) && is_array($aIDs)) {
        $_SESSION['aPeopleCart'] = array_intersect($_SESSION['aPeopleCart'], $aIDs);
    }
}

function RemoveFromPeopleCart($sID)
{
	// make sure the cart array exists
	// we can't remove anybody if there is no cart
	if(isset($_SESSION['aPeopleCart']))
	{
		unset($aTempArray); // may not need this line, but make sure $aTempArray is empty
		$aTempArray[] = $sID; // the only element in this array is the ID to be removed
		$_SESSION['aPeopleCart'] = array_diff($_SESSION['aPeopleCart'],$aTempArray);
	}
}

function RemoveArrayFromPeopleCart($aIDs)
{
	// make sure the cart array exists
	// we can't remove anybody if there is no cart
	if(isset($_SESSION['aPeopleCart']) && is_array($aIDs))
	{
		$_SESSION['aPeopleCart'] = array_diff($_SESSION['aPeopleCart'],$aIDs);
	}
}

// Remove group from cart
function RemoveGroupFromPeopleCart($iGroupID)
{
	//Get all the members of this group
	$sSQL =	"SELECT p2g2r_per_ID FROM person2group2role_p2g2r " .
			"WHERE p2g2r_grp_ID = " . $iGroupID;
	$rsGroupMembers = RunQuery($sSQL);

	//Loop through the recordset
	while ($aRow = mysql_fetch_array($rsGroupMembers))
	{
		extract($aRow);

		//remove each person from the cart
		RemoveFromPeopleCart($p2g2r_per_ID);
	}
}


// Reinstated by Todd Pillars for Event Listing
// Takes MYSQL DateTime
// bWithtime 1 to be displayed
function FormatDate($dDate, $bWithTime=FALSE)
{
    if ($dDate == '' || $dDate == '0000-00-00 00:00:00' || $dDate == '0000-00-00')
        return ('');

	if (strlen($dDate)==10) // If only a date was passed append time
		$dDate = $dDate . ' 12:00:00';	// Use noon to avoid a shift in daylight time causing
										// a date change.

	if (strlen($dDate)!=19)
		return ('');

	// Verify it is a valid date
	$sScanString = substr($dDate,0,10);
	list($iYear, $iMonth, $iDay) = sscanf($sScanString,"%04d-%02d-%02d");

	if ( !checkdate($iMonth,$iDay,$iYear) )
		return ('Unknown');

    // PHP date() function is not used because it is only robust for dates between
    // 1970 and 2038.  This is a problem on systems that are limited to 32 bit integers.
    // To handle a much wider range of dates use MySQL date functions.

    $sSQL = "SELECT DATE_FORMAT('$dDate', '%b') as mn, "
    .       "DAYOFMONTH('$dDate') as dm, YEAR('$dDate') as y, "
    .       "DATE_FORMAT('$dDate', '%k') as h, "
    .       "DATE_FORMAT('$dDate', ':%i') as m";
    extract(mysql_fetch_array(RunQuery($sSQL)));

    $month = gettext("$mn"); // Allow for translation of 3 character month abbr

    if ($h > 11) {
        $sAMPM = gettext('pm');
        if ($h > 12) {
            $h = $h-12;
        }
    } else {
        $sAMPM = gettext('am');
        if ($h == 0) {
            $h = 12;
        }
    }

    if ($bWithTime) {
        return ("$dm $month $y $h$m $sAMPM");
    } else {
        return ("$dm $month $y");
    }

}

function AlternateRowStyle($sCurrentStyle)
{
	if ($sCurrentStyle == "RowColorA") {
		return "RowColorB";
	} else {
		return "RowColorA";
	}
}

function ConvertToBoolean($sInput)
{
	if (empty($sInput)) {
		return False;
	} else {
		if (is_numeric($sInput)) {
			if ($sInput == 1) {
				return True;
			} else {
				return False;
			}
		}
		else
		{
			$sInput = strtolower($sInput);
			if (in_array($sInput,array("true","yes","si"))) {
				return True;
			} else {
				return False;
			}
		}
	}
}

function ConvertFromBoolean($sInput)
{
	if ($sInput) {
		return 1;
	} else {
		return 0;
	}
}

//
// Collapses a formatted phone number as long as the Country is known
// Eg. for United States:  555-555-1212 Ext. 123 ==> 5555551212e123
//
// Need to add other countries besides the US...
//
function CollapsePhoneNumber($sPhoneNumber,$sPhoneCountry)
{
	switch ($sPhoneCountry)	{

	case "United States":
		$sCollapsedPhoneNumber = "";
		$bHasExtension = false;

		// Loop through the input string
		for ($iCount = 0; $iCount <= strlen($sPhoneNumber); $iCount++) {

			// Take one character...
			$sThisCharacter = substr($sPhoneNumber, $iCount, 1);

			// Is it a number?
			if (Ord($sThisCharacter) >= 48 && Ord($sThisCharacter) <= 57) {
				// Yes, add it to the returned value.
				$sCollapsedPhoneNumber .= $sThisCharacter;
			}
			// Is the user trying to add an extension?
			else if (!$bHasExtension && ($sThisCharacter == "e" || $sThisCharacter == "E")) {
				// Yes, add the extension identifier 'e' to the stored string.
				$sCollapsedPhoneNumber .= "e";
				// From now on, ignore other non-digits and process normally
				$bHasExtension = true;
			}
		}
		break;

	default:
		$sCollapsedPhoneNumber = $sPhoneNumber;
		break;
	}

	return $sCollapsedPhoneNumber;
}


//
// Expands a collapsed phone number into the proper format for a known country.
//
// If, during expansion, an unknown format is found, the original will be returned
// and the a boolean flag $bWeird will be set.  Unfortunately, because PHP does not
// allow for pass-by-reference in conjunction with a variable-length argument list,
// a dummy variable will have to be passed even if this functionality is unneeded.
//
// Need to add other countries besides the US...
//
function ExpandPhoneNumber($sPhoneNumber,$sPhoneCountry,&$bWeird)
{
	$bWeird = false;
	$length = strlen($sPhoneNumber);

	switch ($sPhoneCountry)	{

	case "United States":

		if ($length == 0)
			return "";

		// 7 digit phone # with extension
		else if (substr($sPhoneNumber,7,1) == "e")
			return substr($sPhoneNumber,0,3) . "-" . substr($sPhoneNumber,3,4) . " Ext." . substr($sPhoneNumber,8,6);

		// 10 digit phone # with extension
		else if (substr($sPhoneNumber,10,1) == "e")
			return substr($sPhoneNumber,0,3) . "-" . substr($sPhoneNumber,3,3) . "-" . substr($sPhoneNumber,6,4) . " Ext." . substr($sPhoneNumber,11,6);

		else if ($length == 7)
			return substr($sPhoneNumber,0,3) . "-" . substr($sPhoneNumber,3,4);

		else if ($length == 10)
			return substr($sPhoneNumber,0,3) . "-" . substr($sPhoneNumber,3,3) . "-" . substr($sPhoneNumber,6,4);

		// Otherwise, there is something weird stored, so just leave it untouched and set the flag
		else
		{
     		$bWeird = true;
			return $sPhoneNumber;
		}

	break;

	// If the country is unknown, we don't know how to format it, so leave it untouched
	default:
		return $sPhoneNumber;
	}
}

//
// Prints age in years, or in months if less than one year old
//
function PrintAge($Month,$Day,$Year,$Flags)
{
	echo FormatAge ($Month,$Day,$Year,$Flags);
}

//
// Formats an age string: age in years, or in months if less than one year old
//
function FormatAge($Month,$Day,$Year,$Flags)
{
	if (($Flags & 1) ) //||!$_SESSION['bSeePrivacyData']
	{
		return;

	}

	if ($Year > 0)
	{
		if ($Year == date("Y"))
		{
			$monthCount = date("m") - $Month;
			if ($Day > date("d"))
				$monthCount--;
			if ($monthCount == 1)
				return (gettext("1 bulan"));
			else
				return ( $monthCount . " " . gettext("bulan"));
		}
		elseif ($Year == date("Y")-1)
		{
			$monthCount =  12 - $Month + date("m");
			if ($Day > date("d"))
				$monthCount--;
			if ($monthCount >= 12)
				return ( gettext("1 tahun"));
			elseif ($monthCount == 1)
				return ( gettext("1 bulan"));
			else
				return ( $monthCount . " " . gettext("bulan"));
		}
		elseif ( $Month > date("m") || ($Month == date("m") && $Day > date("d")) )
			return ( date("Y")-1 - $Year . " " . gettext("tahun"));
		else
			return ( date("Y") - $Year . " " . gettext("tahun"));
	}
	else
		return ( gettext("Tidak Ada Data"));
}

//
// Formats an age string: age in years, or in months if less than one year old
//
function FormatAgeRip($Month,$Day,$Year,$RipDate)
{

$RipDate = explode('-', $RipDate);
$RipMonth = $RipDate[1];
$RipDay   = $RipDate[2];
$RipYear  = $RipDate[0];

	if ($Year > 0)
	{
		if ($Year == $RipYear)
		{
			$monthCount = $RipMonth - $Month;
			if ($Day > $RipDay)
				$monthCount--;
			if ($monthCount == 1)
				return (gettext("1 bulan"));
			else
				return ( $monthCount . " " . gettext("bulan"));
		}
		elseif ($Year == $RipYear -1)
		{
			$monthCount =  12 - $Month + $RipMonth;
			if ($Day > $RipDay )
				$monthCount--;
			if ($monthCount >= 12)
				return ( gettext("1 tahun"));
			elseif ($monthCount == 1)
				return ( gettext("1 bulan"));
			else
				return ( $monthCount . " " . gettext("bulan"));
		}
		elseif ( $Month > $RipMonth || ($Month == $RipMonth && $Day > $RipDay ) )
			return ( $RipYear-1 - $Year . " " . gettext("tahun"));

		else
			return ( $RipYear - $Year . " " . gettext("tahun "));

			
	}
	else
		return ( gettext("Tidak Ada Data"));
}

function JangkaWaktu($StartDate,$EndDate)
{

$StartDate = explode('-', $StartDate);
$Month = $StartDate[1];
$Day   = $StartDate[2];
$Year  = $StartDate[0];

$EndDate = explode('-', $EndDate);
$EndMonth = $EndDate[1];
$EndDay   = $EndDate[2];
$EndYear  = $EndDate[0];

	if ($Year > 0)
	{
		if ($Year == $EndYear)
		{
			$monthCount = $EndMonth - $Month;
			if ($Day > $EndDay)
				$monthCount--;
			if ($monthCount == 1)
				return (gettext("1 bulan"));
			else
				return ( $monthCount . " " . gettext("bulan"));
		}
		elseif ($Year == $EndYear -1)
		{
			$monthCount =  12 - $Month + $EndMonth;
			if ($Day > $EndDay )
				$monthCount--;
			if ($monthCount >= 12)
				return ( gettext("1 tahun"));
			elseif ($monthCount == 1)
				return ( gettext("1 bulan"));
			else
				return ( $monthCount . " " . gettext("bulan"));
		}
		elseif ( $Month > $EndMonth || ($Month == $EndMonth && $Day > $EndDay ) )
			return ( $EndYear-1 - $Year . " " . gettext("tahun"));

		else
			return ( $EndYear - $Year . " " . gettext("tahun "));

			
	}
	else
		return ( gettext("Tidak Ada Data"));
}

// Returns a string of a person's full name, formatted as specified by $Style
// $Style = 0  :  "Title FirstName MiddleName LastName, Suffix"
// $Style = 1  :  "Title FirstName MiddleInitial. LastName, Suffix"
// $Style = 2  :  "LastName, Title FirstName MiddleName, Suffix"
// $Style = 3  :  "LastName, Title FirstName MiddleInitial., Suffix"
// $Style = 9  :  "First Name Only"
//


function FormatFullName($Title, $FirstName, $MiddleName, $LastName, $Suffix, $Style)
{
	$nameString = "";

	switch ($Style) {

	case 0:
		if ($Title) $nameString .= $Title . " ";
		$nameString .= $FirstName;
		if ($MiddleName) $nameString .= " " . $MiddleName;
		if ($LastName) $nameString .= " " . $LastName;
		if ($Suffix) $nameString .= ", " . $Suffix;
		break;

	case 1:
		if ($Title) $nameString .= $Title . " ";
		$nameString .= $FirstName;
		if ($MiddleName) $nameString .= " " . strtoupper($MiddleName{0}) . ".";
		if ($LastName) $nameString .= " " . $LastName;
		if ($Suffix) $nameString .= ", " . $Suffix;
		break;

	case 2:
		if ($LastName) $nameString .= $LastName . ", ";
		if ($Title) $nameString .= $Title . " ";
		$nameString .= $FirstName;
		if ($MiddleName) $nameString .= " " . $MiddleName;
		if ($Suffix) $nameString .= ", " . $Suffix;
		break;

	case 3:
		if ($LastName) $nameString .= $LastName . ", ";
		if ($Title) $nameString .= $Title . " ";
		$nameString .= $FirstName;
		if ($MiddleName) $nameString .= " " . strtoupper($MiddleName{0}) . ".";
		if ($Suffix) $nameString .= ", " . $Suffix;
		break;

	case 9:
		if ($Title) $nameString .= $Title . " ";
		$nameString .= $FirstName;
		if ($MiddleName) $nameString .= " " ;
		if ($LastName) $nameString .= " " ;
		if ($Suffix) $nameString .= ", " ;
		break;

	}

	return $nameString;
}

// Generate a nicely formatted string for "FamilyName - Address / City, State" with available data
function FormatAddressLine($Address, $City, $State)
{
	$sText = "";

	if ($Address != "" || $City != "" || $State != "") { $sText = " - "; }
	$sText .= $Address;
	if ($Address != "" && ($City != "" || $State != "")) { $sText .= " / "; }
	$sText .= $City;
	if ($City != "" && $State != "") { $sText .= ", "; }
	$sText .= $State;

	return $sText;
}

//
// Formats the data for a custom field for display-only uses
//
function displayCustomField($type, $data, $special)
{
	global $cnInfoCentral;

	switch ($type)
	{
		// Handler for boolean fields
		case 1:
			if ($data == 'true')
				return gettext("Yes");
			elseif ($data == 'false')
				return gettext("No");
			break;

		// Handler for date fields
		case 2:
            return FormatDate($data);
            break;
        // Handler for text fields, years, seasons, numbers, money
		case 3:
		case 4:
		case 6:
		case 8:
		case 10:
			return $data;
			break;


		// Handler for extended text fields (MySQL type TEXT, Max length: 2^16-1)
		case 5:
			/*if (strlen($data) > 100) {
				return substr($data,0,100) . "...";
			}else{
				return $data;
			}
			*/
			return $data;
			break;

		// Handler for season.  Capitalize the word for nicer display.
		case 7:
			return ucfirst($data);
			break;

		// Handler for "person from group"
		case 9:
			if ($data > 0) {
				$sSQL = "SELECT per_FirstName, per_LastName FROM person_per WHERE per_ID =" . $data;
				$rsTemp = RunQuery($sSQL);
				extract(mysql_fetch_array($rsTemp));
				return $per_FirstName . " " . $per_LastName;
			}
			else return "";
			break;

		// Handler for phone numbers
		case 11:
			return ExpandPhoneNumber($data,$special,$dummy);
			break;

		// Handler for custom lists
		case 12:
			if ($data > 0) {
				$sSQL = "SELECT lst_OptionName FROM list_lst WHERE lst_ID = $special AND lst_OptionID = $data";
				$rsTemp = RunQuery($sSQL);
				extract(mysql_fetch_array($rsTemp));
				return $lst_OptionName;
			}
			else return "";
			break;

		// Otherwise, display error for debugging.
		default:
			return gettext("Editor ID SALAH!");
			break;
	}
}


//
// Generates an HTML form <input> line for a custom field
//
function formCustomField($type, $fieldname, $data, $special, $bFirstPassFlag)
{
	global $cnInfoCentral;

	switch ($type)
	{
		// Handler for boolean fields
		case 1:
			echo "<input type=\"radio\" Name=\"" . $fieldname . "\" value=\"true\"";
				if ($data == 'true') { echo " checked"; }
				echo ">Yes";
			echo "<input type=\"radio\" Name=\"" . $fieldname . "\" value=\"false\"";
				if ($data == 'false') { echo " checked"; }
				echo ">No";
			echo "<input type=\"radio\" Name=\"" . $fieldname . "\" value=\"\"";
				if (strlen($data) == 0) { echo " checked"; }
				echo ">Unknown";
			break;

		// Handler for date fields
		case 2:
			echo "<input type=\"text\" id=\"" . $fieldname . "\" Name=\"" . $fieldname . "\" maxlength=\"10\" size=\"15\" value=\"" . $data . "\">&nbsp;<input type=\"image\" onclick=\"return showCalendar('$fieldname', 'y-mm-dd');\" src=\"Images/calendar.gif\"> " . gettext("[format: YYYY-MM-DD]");
			break;

		// Handler for 50 character max. text fields
		case 3:
			echo "<input type=\"text\" Name=\"" . $fieldname . "\" maxlength=\"50\" size=\"50\" value=\"" . htmlentities(stripslashes($data),ENT_NOQUOTES, "UTF-8") . "\">";
			break;

		// Handler for 100 character max. text fields
		case 4:
			echo "<textarea Name=\"" . $fieldname . "\" cols=\"40\" rows=\"2\" onKeyPress=\"LimitTextSize(this,100)\">" . htmlentities(stripslashes($data),ENT_NOQUOTES, "UTF-8") . "</textarea>";
			break;

		// Handler for extended text fields (MySQL type TEXT, Max length: 2^16-1)
		case 5:
			echo "<textarea Name=\"" . $fieldname . "\" cols=\"60\" rows=\"4\" onKeyPress=\"LimitTextSize(this, 65535)\">" . htmlentities(stripslashes($data),ENT_NOQUOTES, "UTF-8") . "</textarea>";
			break;

		// Handler for 4-digit year
		case 6:
			echo "<input type=\"text\" Name=\"" . $fieldname . "\" maxlength=\"4\" size=\"6\" value=\"" . $data . "\">";
			break;

		// Handler for season (drop-down selection)
		case 7:
			echo "<select name=\"$fieldname\">";
			echo "	<option value=\"none\">" . gettext("Select Season") . "</option>";
			echo "	<option value=\"winter\"";
			if ($data == 'winter') { echo " selected"; }
			echo ">" . gettext("Winter") . "</option>";
			echo "	<option value=\"spring\"";
			if ($data == 'spring') { echo " selected"; }
			echo ">" . gettext("Spring") . "</option>";
			echo "	<option value=\"summer\"";
			if ($data == 'summer') { echo "selected"; }
			echo ">" . gettext("Summer") . "</option>";
			echo "	<option value=\"fall\"";
			if ($data == 'fall') { echo " selected"; }
			echo ">" . gettext("Fall") . "</option>";
			echo "</select>";
			break;

		// Handler for integer numbers
		case 8:
			echo "<input type=\"text\" Name=\"" . $fieldname . "\" maxlength=\"11\" size=\"15\" value=\"" . $data . "\">";
			break;

		// Handler for "person from group"
		case 9:
			// ... Get First/Last name of everyone in the group, plus their person ID ...
			// In this case, prop_Special is used to store the Group ID for this selection box
			// This allows the group special-property designer to allow selection from a specific group

			$sSQL = "SELECT person_per.per_ID, person_per.per_FirstName, person_per.per_LastName
						FROM person2group2role_p2g2r
						LEFT JOIN person_per ON person2group2role_p2g2r.p2g2r_per_ID = person_per.per_ID
						WHERE p2g2r_grp_ID = " . $special . " ORDER BY per_FirstName";

			$rsGroupPeople = RunQuery($sSQL);

			echo "<select name=\"" . $fieldname . "\">";
				echo "<option value=\"0\"";
				if ($data <= 0) echo " selected";
				echo ">" . gettext("Tidak ada Data") . "</option>";
				echo "<option value=\"0\">-----------------------</option>";

				while ($aRow = mysql_fetch_array($rsGroupPeople))
				{
					extract($aRow);

					echo "<option value=\"" . $per_ID . "\"";
					if ($data == $per_ID) echo " selected";
					echo ">" . $per_FirstName . "&nbsp;" . $per_LastName . "</option>";
				}

			echo "</select>";
			break;

		// Handler for money amounts
		case 10:
			echo "<input type=\"text\" Name=\"" . $fieldname . "\" maxlength=\"13\" size=\"16\" value=\"" . $data . "\">";
			break;

		// Handler for phone numbers
		case 11:

			// This is silly. Perhaps ExpandPhoneNumber before this function is called!
			if ($bFirstPassFlag)
				// in this case, $special is the phone country
				$data = ExpandPhoneNumber($data,$special,$bNoFormat_Phone);
			if (isset($_POST[$fieldname . "noformat"]))
				$bNoFormat_Phone = true;

			echo "<input type=\"text\" Name=\"" . $fieldname . "\" maxlength=\"30\" size=\"30\" value=\"" . htmlentities(stripslashes($data),ENT_NOQUOTES, "UTF-8") . "\">";
			echo "<br><input type=\"checkbox\" name=\"" . $fieldname . "noformat\" value=\"1\"";
			if ($bNoFormat_Phone) echo " checked";
			echo ">" . gettext("Do not auto-format");
			break;

		// Handler for custom lists
		case 12:
			$sSQL = "SELECT * FROM list_lst WHERE lst_ID = $special ORDER BY lst_OptionSequence";
			$rsListOptions = RunQuery($sSQL);

			echo "<select name=\"" . $fieldname . "\">";
				echo "<option value=\"0\" selected>" . gettext("Tidak Ada Data") . "</option>";
				echo "<option value=\"0\">-----------------------</option>";

				while ($aRow = mysql_fetch_array($rsListOptions))
				{
					extract($aRow);
					echo "<option value=\"" . $lst_OptionID . "\"";
					if ($data == $lst_OptionID)	echo " selected";
					echo ">" . $lst_OptionName . "</option>";
				}

			echo "</select>";
			break;

		// Otherwise, display error for debugging.
		default:
			echo "<b>" . gettext("Editor ID SALAH!") . "</b>";
			break;
	}
}

function assembleYearMonthDay($sYear, $sMonth, $sDay, $pasfut = "future") {
// This function takes a year, month and day from parseAndValidateDate.  On success this
// function returns a string in the form "YYYY-MM-DD".  It returns FALSE on failure.
// The year can be either 2 digit or 4 digit.  If a 2 digit year is passed the $passfut
// indicates whether to return a 4 digit year in the past or the future.  The parameter
// $passfut is not needed for the current year.  If unspecified it assumes the two digit year
// is either this year or one of the next 99 years.


	// Parse the year
	// Take a 2 or 4 digit year and return a 4 digit year.  Use $pasfut to determine if
	// two digit year maps to past or future 4 digit year.
	if (strlen($sYear) == 2) {
		$thisYear = date('Y');
		$twoDigit = substr($thisYear,2,2);
		if ($sYear == $twoDigit) {
			// Assume 2 digit year is this year
			$sYear = substr($thisYear,0,4);
		} elseif ($pasfut == "future") {
			// Assume 2 digit year is in next 99 years
			if ($sYear > $twoDigit) {
				$sYear = substr($thisYear,0,2) . $sYear;
			} else {
				$sNextCentury = $thisYear + 100;
				$sYear = substr($sNextCentury,0,2) . $sYear;
			}
		} else {
			// Assume 2 digit year was is last 99 years
			if ($sYear < $twoDigit) {
				$sYear = substr($thisYear,0,2) . $sYear;
			} else {
				$sLastCentury = $thisYear - 100;
				$sYear = substr($sLastCentury,0,2) . $sYear;
			}
		}
	} elseif (strlen($sYear) == 4) {
		$sYear = $sYear;
	} else {
		return FALSE;
	}

	// Parse the Month
	// Take a one or two character month and return a two character month
	if (strlen($sMonth) == 1) {
		$sMonth = "0" . $sMonth;
	} elseif (strlen($sMonth) == 2) {
		$sMonth = $sMonth;
	} else {
		return FALSE;
	}

	// Parse the Day
	// Take a one or two character day and return a two character day
	if (strlen($sDay) == 1) {
		$sDay = "0" . $sDay;
	} elseif (strlen($sDay) == 2) {
		$sDay = $sDay;
	} else {
		return FALSE;
	}

	$sScanString = $sYear . "-" . $sMonth . "-" . $sDay;
	list($iYear, $iMonth, $iDay) = sscanf($sScanString,"%04d-%02d-%02d");

	if ( checkdate($iMonth,$iDay,$iYear) )	{
		return $sScanString;
	} else {
		return FALSE;
	}

}

function parseAndValidateDate($data, $locale = "US", $pasfut = "future") {
// This function was written because I had no luck finding a PHP
// function that would reliably parse a human entered date string for
// dates before 1/1/1970 or after 1/19/2038 on any Operating System.
//
// This function has hooks for US English M/D/Y format as well as D/M/Y.  The
// default is M/D/Y for date.  To change to D/M/Y use anything but "US" for
// $locale.
//
// Y-M-D is allowed if the delimiter is "-" instead of "/"
//
// In order to help this function guess a two digit year a "past" or "future" flag is
// passed to this function.  If no flag is passed the function assumes that two digit
// years are in the future (or the current year).
//
// Month and day may be either 1 character or two characters (leading zeroes are not
// necessary)


	// Determine if the delimiter is "-" or "/".  The delimiter must appear
	// twice or a FALSE will be returned.

	if (substr_count($data,'-') == 2) {
		// Assume format is Y-M-D
		$iFirstDelimiter = strpos($data,'-');
		$iSecondDelimiter = strpos($data,'-',$iFirstDelimiter+1);

		// Parse the year.
		$sYear = substr($data, 0, $iFirstDelimiter);

		// Parse the month
		$sMonth = substr($data, $iFirstDelimiter+1, $iSecondDelimiter-$iFirstDelimiter-1);

		// Parse the day
		$sDay = substr($data, $iSecondDelimiter+1);

		// Put into YYYY-MM-DD form
		return assembleYearMonthDay($sYear, $sMonth, $sDay, $pasfut);

	} elseif ((substr_count($data,'/') == 2) && ($locale == "US")) {
		// Assume format is M/D/Y
		$iFirstDelimiter = strpos($data,'/');
		$iSecondDelimiter = strpos($data,'/',$iFirstDelimiter+1);

		// Parse the month
		$sMonth = substr($data, 0, $iFirstDelimiter);

		// Parse the day
		$sDay = substr($data, $iFirstDelimiter+1, $iSecondDelimiter-$iFirstDelimiter-1);

		// Parse the year
		$sYear = substr($data, $iSecondDelimiter+1);

		// Put into YYYY-MM-DD form
		return assembleYearMonthDay($sYear, $sMonth, $sDay, $pasfut);

	} elseif (substr_count($data,'/') == 2) {
		// Assume format is D/M/Y
		$iFirstDelimiter = strpos($data,'/');
		$iSecondDelimiter = strpos($data,'/',$iFirstDelimiter+1);

		// Parse the day
		$sDay = substr($data, 0, $iFirstDelimiter);

		// Parse the month
		$sMonth = substr($data, $iFirstDelimiter+1, $iSecondDelimiter-$iFirstDelimiter-1);

		// Parse the year
		$sYear = substr($data, $iSecondDelimiter+1);

		// Put into YYYY-MM-DD form
		return assembleYearMonthDay($sYear, $sMonth, $sDay, $pasfut);

	}

	// If we made it this far it means the above logic was unable to parse the date.
	// Now try to parse using the function strtotime().  The strtotime() function does
	// not gracefully handle dates outside the range 1/1/1970 to 1/19/2038.  For this
	// reason consider strtotime() as a function of last resort.
	$timeStamp = strtotime($data);
	if ($timeStamp == FALSE || $timeStamp <= 0) {
		// Some Operating Sytems and older versions of PHP do not gracefully handle
		// negative timestamps.  Bail if the timestamp is negative.
		return FALSE;
	}

	// Now use the date() function to convert timestamp into YYYY-MM-DD
	$dateString = date("Y-m-d", $timeStamp);

	if (strlen($dateString) != 10) {
		// Common sense says we have a 10 charater string.  If not, something is wrong
		// and it's time to bail.
		return FALSE;
	}

	if ($dateString > "1970-01-01" && $dateString < "2038-01-19") {
		// Success!
		return $dateString;
	}

	// Should not have made it this far.  Something is wrong so bail.
	return FALSE;
}

// Processes and Validates custom field data based on its type.
//
// Returns false if the data is not valid, true otherwise.
//
function validateCustomField($type, &$data, $col_Name, &$aErrors)
{
	global $aLocaleInfo;
	$bErrorFlag = false;

	switch ($type)
	{
		// Validate a date field
		case 2:
			if (strlen($data) > 0)
			{
				$dateString = parseAndValidateDate($data);
				if ( $dateString === FALSE ) {
					$aErrors[$col_Name] = gettext("Tanggal SALAH");
					$bErrorFlag = true;
				} else {
					$data = $dateString;
				}
			}
			break;

		// Handler for 4-digit year
		case 6:
			if (strlen($data) != 0)
			{
				if (!is_numeric($data) || strlen($data) != 4)
				{
					$aErrors[$col_Name] = gettext("Tahun SALAH");
					$bErrorFlag = True;
				}
				elseif ($data > 2155 || $data < 1901)
				{
					$aErrors[$col_Name] = gettext("SALAH: Silahkan pilih dari 1901 samapai 2155");
					$bErrorFlag = True;
				}
			}
			break;

		// Handler for integer numbers
		case 8:
			if (strlen($data) != 0)
			{
				$data = eregi_replace($aLocaleInfo["thousands_sep"], "", $data);  // remove any thousands separators
				if (!is_numeric($data))
				{
					$aErrors[$col_Name] = gettext("Nilai SALAH");
					$bErrorFlag = True;
				}
				elseif ($data < -2147483648 || $data > 2147483647)
				{
					$aErrors[$col_Name] = gettext("Nilai Terlalu besar. Harus diantara -2147483648 sampai 2147483647");
					$bErrorFlag = True;
				}
			}
			break;

		// Handler for money amounts
		case 10:
			if (strlen($data) != 0)
			{
				$data = eregi_replace($aLocaleInfo["mon_thousands_sep"], "", $data);
				if (!is_numeric($data))
				{
					$aErrors[$col_Name] = gettext("Nilai SALAH");
					$bErrorFlag = True;
				}
				elseif ($data > 999999999.99)
				{
					$aErrors[$col_Name] = gettext("Uang Terlalu Besar . Maksimum nilai Rp999999999.99");
					$bErrorFlag = True;
				}
			}
			break;

		// Otherwise ignore.. some types do not need validation or filtering
		default:
			break;
	}
	return !$bErrorFlag;
}

// Generates SQL for custom field update
//
// $special is currently only used for the phone country
//
function sqlCustomField(&$sSQL, $type, $data, $col_Name, $special)
{
	switch($type)
	{
		// boolean
		case 1:
			switch ($data) {
				case "false":
					$data = "'false'";
					break;
				case "true":
					$data = "'true'";
					break;
				default:
					$data = "NULL";
					break;
			}

			$sSQL .= $col_Name . " = " . $data . ", ";
			break;

		// date
		case 2:
			if (strlen($data) > 0) {
				$sSQL .= $col_Name . " = \"" . $data . "\", ";
			}
			else {
				$sSQL .= $col_Name . " = NULL, ";
			}
			break;

		// year
		case 6:
			if (strlen($data) > 0) {
				$sSQL .= $col_Name . " = '" . $data . "', ";
			}
			else {
				$sSQL .= $col_Name . " = NULL, ";
			}
			break;

		// season
		case 7:
			if ($data != 'none') {
				$sSQL .= $col_Name . " = '" . $data . "', ";
			}
			else {
				$sSQL .= $col_Name . " = NULL, ";
			}
			break;

		// integer, money
		case 8:
		case 10:
			if (strlen($data) > 0) {
				$sSQL .= $col_Name . " = '" . $data . "', ";
			}
			else {
				$sSQL .= $col_Name . " = NULL, ";
			}
			break;

		// list selects
		case 9:
		case 12:
			if ($data != 0) {
				$sSQL .= $col_Name . " = '" . $data . "', ";
			}
			else {
				$sSQL .= $col_Name . " = NULL, ";
			}
			break;

		// strings
		case 3:
		case 4:
		case 5:
			if (strlen($data) > 0) {
				$sSQL .= $col_Name . " = '" . $data . "', ";
			}
			else {
				$sSQL .= $col_Name . " = NULL, ";
			}
			break;

		// phone
		case 11:
			if (strlen($data) > 0) {
				if (!isset($_POST[$col_Name . "noformat"]))
					$sSQL .= $col_Name . " = '" . CollapsePhoneNumber($data,$special) . "', ";
				else
					$sSQL .= $col_Name . " = '" . $data . "', ";
			}
			else {
				$sSQL .= $col_Name . " = NULL, ";
			}
			break;

		default:
			$sSQL .= $col_Name . " = '" . $data . "', ";
			break;
	}
}

// Runs the ToolTips
// By default ToolTips are diplayed, unless turned off in the user settings.
function addToolTip($ToolTip)
{
	global $bToolTipsOn;
	if ($bToolTipsOn)
	{
		$ToolTipText = "onmouseover=\"domTT_activate(this, event, 'content', '" . $ToolTip . "');\"";
		echo $ToolTipText;
	}
}

// Wrapper for number_format that uses the locale information
// There are three modes: money, integer, and intmoney (whole number money)
function formatNumber($iNumber,$sMode = 'integer')
{
	global $aLocaleInfo;

	switch ($sMode) {
		case 'money':
			return $aLocaleInfo["currency_symbol"] . ' ' . number_format($iNumber,$aLocaleInfo["frac_digits"],$aLocaleInfo["mon_decimal_point"],$aLocaleInfo["mon_thousands_sep"]);
			break;

		case 'intmoney':
			return $aLocaleInfo["currency_symbol"] . ' ' . number_format($iNumber,0,'',$aLocaleInfo["mon_thousands_sep"]);
			break;

		case 'float':
			$iDecimals = 2; // need to calculate # decimals in original number
			return number_format($iNumber,$iDecimals,$aLocaleInfo["mon_decimal_point"],$aLocaleInfo["mon_thousands_sep"]);
			break;

		case 'integer':
		default:
			return number_format($iNumber,0,'',$aLocaleInfo["mon_thousands_sep"]);
			break;
	}
}

// Format a BirthDate
// Optionally, the separator may be specified.  Default is YEAR-MN-DY
function FormatBirthDate($per_BirthYear, $per_BirthMonth, $per_BirthDay, $sSeparator = "-", $bFlags)
{
	if ($bFlags == 1 || $per_BirthYear == "" )	//Person Would Like their Age Hidden or BirthYear is not known.
	{
		$birthYear = "1000";
	}
	else
	{
		$birthYear = $per_BirthYear;
	}

	if ($per_BirthMonth > 0 && $per_BirthDay > 0)
	{
		if ($per_BirthMonth < 10)
			$dBirthMonth = "0" . $per_BirthMonth;
		else
			$dBirthMonth = $per_BirthMonth;
		if ($per_BirthDay < 10)
			$dBirthDay = "0" . $per_BirthDay;
		else
			$dBirthDay = $per_BirthDay;

		$dBirthDate = $dBirthMonth . $sSeparator . $dBirthDay;
		if (is_numeric($birthYear))
		{
			$dBirthDate = $birthYear . $sSeparator . $dBirthDate;
            if (checkdate($dBirthMonth,$dBirthDay,$birthYear))
            {
			   $dBirthDate = FormatDate($dBirthDate);
					if (substr($dBirthDate, -6, 6) == ", 1000")
					{
						$dBirthDate = str_replace(", 1000", "", $dBirthDate);
					}
            }
		}
	}
	elseif (is_numeric($birthYear) && $birthYear != 1000 )	//Person Would Like Their Age Hidden
	{
		$dBirthDate = $birthYear;
	}
	else
	{
		$dBirthDate = "";
	}

	return $dBirthDate;
}

function FilenameToFontname($filename, $family)
{
    if($filename==$family)
    {
        return ucfirst($family);
    }
    else
    {
        if(strlen($filename) - strlen($family) == 2)
        {
            return ucfirst($family).gettext(" Bold Italic");
        }
        else
        {
            if(substr($filename, strlen($filename) - 1) == "i")
                return ucfirst($family).gettext(" Italic");
            else
                return ucfirst($family).gettext(" Bold");
        }
    }
}

function FontFromName($fontname)
{
    $fontinfo = split(" ", $fontname);
    switch (count($fontinfo)) {
        case 1:
            return array($fontinfo[0], '');
        case 2:
            return array($fontinfo[0], substr($fontinfo[1], 0, 1));
        case 3:
            return array($fontinfo[0], substr($fontinfo[1], 0, 1).substr($fontinfo[2], 0, 1));
    }
}

// Added for AddEvent.php
function createTimeDropdown($start,$stop,$mininc,$hoursel,$minsel)
{
    for ($hour = $start; $hour <= $stop; $hour++)
    {
        if ($hour == '0')
        {
            $disphour = '12';
            $ampm = 'AM';
        }
        elseif ($hour == '12')
        {
            $disphour = '12';
            $ampm = 'PM';
        }
        else if ($hour >= '13' && $hour <= '21')
        {
            $test = $hour - 12;
            $disphour = ' ' . $test;
            $ampm = 'PM';
        }
        else if ($hour >= '22' && $hour <= '23')
        {
            $disphour = $hour - 12;
            $ampm = 'PM';
        }
        else
        {
            $disphour = $hour;
            $ampm = 'AM';
        }

        for ($min = 0; $min <= 59; $min += $mininc)
        {
            if ($hour >= '1' && $hour <= '9')
            {
                if($min >= '0' && $min <= '9')
                {
                    if ($hour == $hoursel && $min == $minsel)
                    {
                        echo '<option value="0'.$hour.':0'.$min.':00" selected> '.$disphour.':0'.$min.' '.$ampm.'</option>'."\n";
                    }
                    else
                    {
                        echo '<option value="0'.$hour.':0'.$min.':00"> '.$disphour.':0'.$min.' '.$ampm.'</option>'."\n";
                    }
                }
                else
                {
                    if ($hour == $hoursel && $min == $minsel)
                    {
                        echo '<option value="0'.$hour.":".$min.':00" selected> '.$disphour.':'.$min.' '.$ampm.'</option>'."\n";
                    }
                    else
                    {
                        echo '<option value="0'.$hour.":".$min.':00"> '.$disphour.':'.$min.' '.$ampm.'</option>'."\n";
                    }
                }
            }
            else
            {
                if ($min >= '0' && $min <= '9')
                {
                    if ($hour == $hoursel && $min == $minsel)
                    {
                        echo '<option value="'.$hour.':0'.$min.':00" selected>'.$disphour.':0'.$min.' '.$ampm.'</option>'."\n";
                    }
                    else
                    {
                        echo '<option value="'.$hour.':0'.$min.':00">'.$disphour.':0'.$min.' '.$ampm.'</option>'."\n";
                    }
                }
                else
                {
                    if ($hour == $hoursel && $min == $minsel)
                    {
                        echo '<option value="'.$hour.":".$min.':00" selected>'.$disphour.':'.$min.' '.$ampm.'</option>'."\n";
                    }
                    else
                    {
                        echo '<option value="'.$hour.":".$min.':00">'.$disphour.':'.$min.' '.$ampm.'</option>'."\n";
                    }
                }
            }
        }
    }
}

// Figure out the class ID for "Member", should be one (1) unless they have been playing with the
// classification manager.
function FindMemberClassID ()
{
	//Get Classifications
	$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 1 ORDER BY lst_OptionSequence";
	$rsClassifications = RunQuery($sSQL);

	while ($aRow = mysql_fetch_array($rsClassifications))
	{
		extract($aRow);
		if ($lst_OptionName == gettext ("Anggota"))
			return ($lst_OptionID);
	}
	return (1); // Should not get here, but if we do get here use the default value.
}

// Prepare data for entry into MySQL database.
// This function solves the problem of inserting a NULL value into MySQL since
// MySQL will not accept 'NULL'.  One drawback is that it is not possible
// to insert the character string "NULL" because it will be inserted as a MySQL NULL!
// This will produce a database error if NULL's are not allowed!  Do not use this
// function if you intend to insert the character string "NULL" into a field.
function MySQLquote ($sfield)
{
	$sfield = trim($sfield);

	if ($sfield == "NULL")
		return "NULL";
	elseif ($sfield == "'NULL'")
		return "NULL";
	elseif ($sfield == "")
		return "NULL";
	elseif ($sfield == "''")
		return "NULL";
	else {
		if ((substr($sfield, 0, 1) == "'") && (substr($sfield, strlen($sfield)-1, 1)) == "'")
			return $sfield;
		else
			return "'" . $sfield . "'";
	}
}

//Function to check email
//From http://www.tienhuis.nl/php-email-address-validation-with-verify-probe
//Functions checkndsrr and getmxrr are not enabled on windows platforms & therefore are disabled
//Future use may be to enable a Admin option to enable these options
//domainCheck verifies domain is valid using dns, verify uses SMTP to verify actual account exists on server

function checkEmail($email, $domainCheck = false, $verify = false, $return_errors=false) {
    global $checkEmailDebug;
    if($checkEmailDebug) {echo "<pre>";}
    # Check syntax with regex
    if (preg_match('/^([a-zA-Z0-9\._\+-]+)\@((\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,7}|[0-9]{1,3})(\]?))$/', $email, $matches)) {
        $user = $matches[1];
        $domain = $matches[2];
        # Check availability of DNS MX records
        if ($domainCheck && function_exists('checkdnsrr')) {
            # Construct array of available mailservers
            if(getmxrr($domain, $mxhosts, $mxweight)) {
                for($i=0;$i<count($mxhosts);$i++){
                    $mxs[$mxhosts[$i]] = $mxweight[$i];
                }
                asort($mxs);
                $mailers = array_keys($mxs);
            } elseif(checkdnsrr($domain, 'A')) {
                $mailers[0] = gethostbyname($domain);
            } else {
                $mailers=array();
            }
            $total = count($mailers);
            # Query each mailserver
            if($total > 0 && $verify) {
                # Check if mailers accept mail
                for($n=0; $n < $total; $n++) {
                    # Check if socket can be opened
                    if($checkEmailDebug) { echo "Checking server $mailers[$n]...\n";}
                    $connect_timeout = 2;
                    $errno = 0;
                    $errstr = 0;
                    $probe_address = $sToEmailAddress;
                    # Try to open up socket
                    if($sock = @fsockopen($mailers[$n], 25, $errno , $errstr, $connect_timeout)) {
                        $response = fgets($sock);
                        if($checkEmailDebug) {echo "Opening up socket to $mailers[$n]... Succes!\n";}
                        stream_set_timeout($sock, 5);
                        $meta = stream_get_meta_data($sock);
                        if($checkEmailDebug) { echo "$mailers[$n] replied: $response\n";}
                        $cmds = array(
                            "HELO $sSMTPHost",  # Be sure to set this correctly!
                            "MAIL FROM: <$probe_address>",
                            "RCPT TO: <$email>",
                            "QUIT",
                        );
                        # Hard error on connect -> break out
                        if(!$meta['timed_out'] && !preg_match('/^2\d\d[ -]/', $response)) {
                            $error = "Error: $mailers[$n] said: $response\n";
                            break;
                        }
                        foreach($cmds as $cmd) {
                            $before = microtime(true);
                            fputs($sock, "$cmd\r\n");
                            $response = fgets($sock, 4096);
                            $t = 1000*(microtime(true)-$before);
                            if($checkEmailDebug) {echo htmlentities("$cmd\n$response") . "(" . sprintf('%.2f', $t) . " ms)\n";}
                            if(!$meta['timed_out'] && preg_match('/^5\d\d[ -]/', $response)) {
                                $error = "Unverified address: $mailers[$n] said: $response";
                                break 2;
                            }
                        }
                        fclose($sock);
                        if($checkEmailDebug) { echo "Succesful communication with $mailers[$n], no hard errors, assuming OK";}
                        break;
                    } elseif($n == $total-1) {
                        $error = "None of the mailservers listed for $domain could be contacted";
                    }
                }
            } elseif($total <= 0) {
                $error = "No usable DNS records found for domain '$domain'";
            }
        }
    } else {
        $error = 'Address syntax not correct';
    }
    if($checkEmailDebug) { echo "</pre>";}
    #echo "</pre>";
    if($return_errors) {
        # Give back details about the error(s).
        # Return FALSE if there are no errors.
        # Keep this in mind when using it like:
        # if(checkEmail($addr)) {
        # Because of this strange behaviour this
        # is not default ;-)
        if(isset($error)) return htmlentities($error); else return false;
    } else {
        # 'Old' behaviour, simple to understand
        if(isset($error)) return false; else return true;
    }
}

//The function would define us how to separate the value into three digits and give the separator after it, 
//the universal use the comma (,) and several country like Indonesia use the dot (.) as the value separator.
//echo currency('Rp',1234567890,'.',',00');
// it could be Rp1.234.567.890,00
//echo currency('$',1234567890,',','.00');
// it could be $1.234.567.890,00
//echo currency('�',1234567890,',','.00');
// it could be �1.234.567.890,00 


function currency($symbol, $value, $sep, $close)
{
$curr = "";
$p = strlen($value);
while($p > 3){
$curr = $sep . substr($value,-3) . $curr;
$l = strlen($value) - 3;
$value = substr($value,0,$l);
$p = strlen($value);
}
$currency = $symbol . $value . $curr . $close;
return $currency;
}

function tanggalsekarang()
{

$hari=date('w');
$tanggal=date("d");
$bulan=date("m");
$tahun=date("Y");
Switch ($bulan){
case 1 : $bulan="Januari";
Break;
case 2 : $bulan="Februari";
Break;
case 3 : $bulan="Maret";
Break;
case 4 : $bulan="April";
Break;
case 5 : $bulan="Mei";
Break;
case 6 : $bulan="Juni";
Break;
case 7 : $bulan="Juli";
Break;
case 8 : $bulan="Agustus";
Break;
case 9 : $bulan="September";
Break;
case 10 : $bulan="Oktober";
Break;
case 11 : $bulan="November";
Break;
case 12 : $bulan="Desember";
Break;
}
$sekarang="".$tanggal." ".$bulan." ".$tahun;
return $sekarang;
}

function bulanindo($bulan)
{
Switch ($bulan){
case 1 : $bulan="Januari";
Break;
case 2 : $bulan="Februari";
Break;
case 3 : $bulan="Maret";
Break;
case 4 : $bulan="April";
Break;
case 5 : $bulan="Mei";
Break;
case 6 : $bulan="Juni";
Break;
case 7 : $bulan="Juli";
Break;
case 8 : $bulan="Agustus";
Break;
case 9 : $bulan="September";
Break;
case 10 : $bulan="Oktober";
Break;
case 11 : $bulan="November";
Break;
case 12 : $bulan="Desember";
Break;
}
echo $bulan;
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
elseif ($day==5)
{$date = strftime( "%B %Y", strtotime($str));}
elseif ($day==6)
{$date = strftime( "%B", strtotime($str));}
elseif ($day==7)
{$date = strftime( "%A", strtotime($str));}
elseif ($day==11)
{$date = strftime( "%Y-%m", strtotime($str));}
elseif ($day==12)
{$date = strftime( "%A, %d %B", strtotime($str));}
else
{$date = strftime( "%d %b %Y", strtotime($str));}

if ($str==''||$str=='0000-00-00')
{$date='';}else{$date=$date;}
return $date;
} 

function Terbilang($x)
{
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
    return " " . $abil[$x];
  elseif ($x < 20)
    return Terbilang($x - 10) . " belas";
  elseif ($x < 100)
    return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
  elseif ($x < 200)
    return " seratus" . Terbilang($x - 100);
  elseif ($x < 1000)
    return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
  elseif ($x < 2000)
    return " seribu" . Terbilang($x - 1000);
  elseif ($x < 1000000)
    return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
  elseif ($x < 1000000000)
    return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}


function jabatanpengurus($posisi) {

			$sSQL = "select a.per_ID as PersonID, CONCAT('',a.per_FirstName,'') AS 'Nama',vol_id, 
			fam_homephone as TelpRumah, c13 as TelpKantor, per_cellphone as Handphone, per_email as Email, vol_name as Jabatan, per_workphone as Kelompok
			from person_per a, person_custom b, family_fam, person2volunteeropp_p2vo, volunteeropportunity_vol
			where a.per_id = b.per_id AND
			a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id AND per_fam_id = fam_id
			AND vol_id = " .$posisi . "
			ORDER by per_workphone, vol_id, per_firstname";
			
			
			$perintah = mysql_query($sSQL);
				$i = 0;
				$kelpk = " ";
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
						$iPersonID=$hasilGD[PersonID]; 

						//echo $hasilGD[Jabatan];
				
						$sSQL2 = "SELECT vol_id as jabid, vol_name as Jabatan
									from person_per , person2volunteeropp_p2vo, volunteeropportunity_vol
									where vol_id < 4 AND per_id = " . $hasilGD[PersonID] . " AND per_id = p2vo_per_id AND p2vo_vol_id = vol_id 
								";
						$perintah2 = mysql_query($sSQL2);
							while ($hasilGD2=mysql_fetch_array($perintah2))
							{
							//echo $sSQL;

							extract($hasilGD2);
							$jab=$hasilGD2[jabid] ;
							
							if ($jab==1)
								$namajab = "Pdt.";
							elseif ($jab==2)
								$namajab = "Pnt.";
							elseif ($jab==3)
								$namajab = "Dkn.";								
							else
							$namajab = "";		   
							}
				$namajab .=$hasilGD[Nama];
				return $namajab;
				//return $hasilGD[Nama];

				}
				if(0 == $i)
				{
			//	echo $posisi;
				$sSQL = "select * from volunteeropportunity_vol
							where  vol_id = " .$posisi ;
			
				$perintah = mysql_query($sSQL);


					while ($hasilGD=mysql_fetch_array($perintah))
						{

						extract($hasilGD);
						return $hasilGD[vol_Name];
						}
				}


}


function emailpengurus($posisi,$pos) {

			if ($pos==''){$pos1=0;}else{$pos1=$pos-1;}

			$sSQL = "select  per_email as Email
			from person_per a, person2volunteeropp_p2vo, volunteeropportunity_vol
			where a.per_id = p2vo_per_id AND p2vo_vol_id = vol_id 
			AND vol_id = " .$posisi." LIMIT $pos1,1";
			
			
			$perintah = mysql_query($sSQL);
				$i = 0;
				$kelpk = " ";
					while ($hasilGD=mysql_fetch_array($perintah))
						{
						$i++;
						extract($hasilGD);
						$iPersonID=$hasilGD[PersonID]; 

						return $hasilGD[Email];

				}
}

function dec2roman($f)
{
// Return false if either $f is not a real number, $f is bigger than 3999 or $f is lower or equal to 0:
if(!is_numeric($f) || $f > 3999 || $f <= 0) return false;
// Define the roman figures:
$roman = array('M' => 1000, 'D' => 500, 'C' => 100, 'L' => 50, 'X' => 10, 'V' => 5, 'I' => 1);
// Calculate the needed roman figures:
foreach($roman as $k => $v) if(($amount[$k] = floor($f / $v)) > 0) $f -= $amount[$k] * $v;
// Build the string:
$return = '';
foreach($amount as $k => $v)
{
$return .= $v <= 3 ? str_repeat($k, $v) : $k . $old_k;
$old_k = $k;
}
// Replace some spacial cases and return the string:
return str_replace(array('VIV','LXL','DCD'), array('IX','XC','CM'), $return);
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

function sendIcalEvent($from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description, $location)
{
$domain = 'exchangecore.com';
//Create Email Headers
$mime_boundary = "----Meeting Booking----".MD5(TIME());
$headers = "From: ".$from_name." <".$from_address.">\n";
$headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
$headers .= "Content-class: urn:content-classes:calendarmessage\n";
//Create Email Body (HTML)
$message = "--$mime_boundary\r\n";
$message .= "Content-Type: text/html; charset=UTF-8\n";
$message .= "Content-Transfer-Encoding: 8bit\n\n";
$message .= "<html>\n";
$message .= "<body>\n";
$message .= '<p>Selamat Pagi '.$to_name.',</p>';
$message .= '<p>'.$description.'</p>';
$message .= "</body>\n";
$message .= "</html>\n";
$message .= "--$mime_boundary\r\n";

//status / url / recurid /

$ical = 'BEGIN:VCALENDAR' . "\r\n" .
'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
'VERSION:2.0' . "\r\n" .
'METHOD:REQUEST' . "\r\n" .
'BEGIN:VTIMEZONE' . "\r\n" .
'TZID:Asia/Jakarta' . "\r\n" .
'BEGIN:STANDARD' . "\r\n" .
'DTSTART:20091101T020000' . "\r\n" .
'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
'TZOFFSETFROM:+0700' . "\r\n" .
'TZOFFSETTO:+0700' . "\r\n" .
'TZNAME:JMT' . "\r\n" .
'END:STANDARD' . "\r\n" .
'BEGIN:DAYLIGHT' . "\r\n" .
'DTSTART:20090301T020000' . "\r\n" .
'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
'TZOFFSETFROM:+0700' . "\r\n" .
'TZOFFSETTO:+0700' . "\r\n" .
'TZNAME:JMT' . "\r\n" .
'END:DAYLIGHT' . "\r\n" .
'END:VTIMEZONE' . "\r\n" .	
'BEGIN:VEVENT' . "\r\n" .
'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
'ATTENDEE;CN="'.$to_name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
'UID:'.date("Ymd\TGis", strtotime($startTime)).rand()."@".$domain."\r\n" .
'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
'DTSTART;TZID="Asia/Jakarta":'.date("Ymd\THis", strtotime($startTime)). "\r\n" .
'DTEND;TZID="Asia/Jakarta":'.date("Ymd\THis", strtotime($endTime)). "\r\n" .
'TRANSP:OPAQUE'. "\r\n" .
'SEQUENCE:1'. "\r\n" .
'SUMMARY:' . $subject . "\r\n" .
'LOCATION:' . $location . "\r\n" .
'CLASS:PUBLIC'. "\r\n" .
'PRIORITY:5'. "\r\n" .
'BEGIN:VALARM' . "\r\n" .
'TRIGGER:-P2D' . "\r\n" .
'ACTION:DISPLAY' . "\r\n" .
'DESCRIPTION:'  . $description .  "\r\n" .
'END:VALARM' . "\r\n" .
'END:VEVENT'. "\r\n" .
'END:VCALENDAR'. "\r\n";
$messageIcal .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST\n';
$messageIcal .= "Content-Transfer-Encoding: 8bit\n\n";
$messageIcal .= $ical;

//$mailsent = mail($to_address, $subject, $message, $headers);
//if($mailsent){
//return true;
//} else {
//return false;
//}

//smtp_mail($to, $subject, $message, $from_name, $from, $cc, $bcc, $debug=false) 
//require_once('/library-email/function.php');
//smtp_mail($to, $subject, $message, '', '', 0, 0, true);
//if($smtp_mail){
//return true;
//} else {
//return false;
//}

//dummy
//$to_address = 'e_pratama@yahoo.com';
 //   smtp_mail($to, $subject, $message, ''       , ''    , $cc, 0, $messageIcal, true);
 	require_once('library-email/function.php');
//smtp_mail($to, $subject, $message, $from_name, $from, $cc, $bcc, $debug=false) 
//$mail_sent = @smtp_mail($to, $subject, $message, ''	, ''	, $cc, 0, false);
$mail_sent = @smtp_mail($to_address, $subject, $message, ''       , ''    , 0, 0, $ical, false);
//echo $cc;
return $mail_sent ? "Email Anda sudah terkirim" : "Mail failed";	   

}

function shorten_string($string, $wordsreturned)
/*  Returns the first $wordsreturned out of $string.  If string
    contains more words than $wordsreturned, the entire string
    is returned.
    */
    {
    $retval = $string;  //    Just in case of a problem
    $array = explode(" ", $string);
    if (count($array)<=$wordsreturned)
    /*  Already short enough, return the whole thing
        */
        {
        $retval = $string;
        }
    else
    /*  Need to chop of some words
        */
        {
        array_splice($array, $wordsreturned);
        $retval = implode(" ", $array)."";
        }
    return $retval;
    }
	
function integerToRoman($integer)
{
 // Convert the integer into an integer (just to make sure)
 $integer = intval($integer);
 $result = '';
 
 // Create a lookup array that contains all of the Roman numerals.
 $lookup = array('M' => 1000,
 'CM' => 900,
 'D' => 500,
 'CD' => 400,
 'C' => 100,
 'XC' => 90,
 'L' => 50,
 'XL' => 40,
 'X' => 10,
 'IX' => 9,
 'V' => 5,
 'IV' => 4,
 'I' => 1);
 
 foreach($lookup as $roman => $value){
  // Determine the number of matches
  $matches = intval($integer/$value);
 
  // Add the same number of characters to the string
  $result .= str_repeat($roman,$matches);
 
  // Set the integer to be the remainder of the integer and the value
  $integer = $integer % $value;
 }
 
 // The Roman numeral should be built, return it
 return $result;
}

function lastWeekDate($s)
{
//$s=date('Y-m-d');
//echo $s;
$time = strtotime($s);
$start = strtotime('last sunday', $time);
$end = strtotime('next saturday', $time);
$format = 'Y-m-d';
//last week date
$start_day = date($format, $start);

return $start_day;

}

function num2alpha($n) {
    $r = '';
    for ($i = 1; $n >= 0 && $i < 10; $i++) {
        $r = chr(0x41 + ($n % pow(26, $i) / pow(26, $i - 1))) . $r;
        $n -= pow(26, $i);
    }
    return $r;
}

function alpha2num($a) {
    $r = 0;
    $l = strlen($a);
    for ($i = 0; $i < $l; $i++) {
        $r += pow(26, $i) * (ord($a[$l - $i - 1]) - 0x40);
    }
    return $r - 1;
} 

/**
* Takes an array and returns an array of duplicate items
*/
function get_duplicates( $array ) {
    return array_unique( array_diff_assoc( $array, array_unique( $array ) ) );
}

 function rgb_to_str($r, $g, $b)
 {
      return str_pad($r, 2, '0', STR_PAD_LEFT)
          .str_pad($g, 2, '0', STR_PAD_LEFT)
          .str_pad($b, 2, '0', STR_PAD_LEFT);
 }
 function str_to_rgb($str)
 {
    return array (
      'r'=>hexdec(substr($str, 0, 2)),
      'g'=>hexdec(substr($str, 3, 2)),
      'b'=>hexdec(substr($str, 5, 2))
    );
 }
 function rainbow($start, $end, $steps)
 {
    $s=str_to_rgb($start);
    $e=str_to_rgb($end);
    $out=array();
    $r=(integer)($e['r'] - $s['r'])/$steps;
    $g=(integer)($e['g'] - $s['g'])/$steps;
    $b=(integer)($e['b'] - $s['b'])/$steps;
    for ($x=0; $x<$steps; $x++) {
       $out[]=rgb_to_str(
          $s['r']+(integer)($r * $x),
          $s['g']+(integer)($g * $x),
          $s['b']+(integer)($b * $x));
    }
    return $out;
 }
 
 function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

 function randint() {
	$refresh = microtime(true) ;
	$refresh = $refresh + abs(1-mt_rand()/mt_rand()) ; 
    return $refresh;
}

 function enkrip($TYP, $THB , $XXX , $YYY , $PID) {
	$plaintext = $TYP."".$THB."".$XXX."".$YYY."".$PID;
    # --- ENCRYPTION ---
    $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
    $key_size =  strlen($key);
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
    $ciphertext = $iv . $ciphertext;
    $ciphertext_base64 = base64_encode($ciphertext);
    return $ciphertext_base64;
}

function KodeNamaWarga($NamaWarga){
	//KodeNamaWarga
$string = $NamaWarga;
$words = explode(' ', $string);
//var_dump($words);

$to_keep = array_slice($words, 0, 3);
//var_dump($to_keep);

$final_string = implode(' ', $to_keep);

//Kode Nama
if ($to_keep[0]=="I"){
$KodeNama = strtoupper(substr($to_keep[1], 0, 2));
$KodeNama2 =   strtoupper(substr($to_keep[2], 0, 2));

}else{
		$KodeNama = strtoupper(substr($to_keep[0], 0, 2));

		if ($to_keep[1]<>""){
			$KodeNama2 = strtoupper(substr($to_keep[1], 0, 2));
			} else {
			$KodeNama2 =   strtoupper(substr($to_keep[0], -2));
			}
	}
	 return $KodeNama."".$KodeNama2;

}

 
?>
