<?php
/*******************************************************************************
*
*  filename    : Include/LoadConfigs.php
*  website     : http://www.churchdb.org
*  description : global configuration 
*                   The code in this file used to be part of part of Config.php
*
*  Copyright 2001-2005 Phillip Hullquist, Deane Barker, Chris Gebhardt, 
*                      Michael Wilt, Timothy Dearborn
*
*
*  Additional Contributors:
*  2006 Ed Davis
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur
*
*
*  Copyright Contributors
*
*  ChurchInfo is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  This file best viewed in a text editor with tabs stops set to 4 characters.
*  Please configure your editor to use soft tabs (4 spaces for a tab) instead
*  of hard tab characters.
*
******************************************************************************/

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

// Read values from config table into local variables
// **************************************************
$sSQL = "SELECT cfg_name, IFNULL(cfg_value, cfg_default) AS value "
      . "FROM config_cfg WHERE cfg_section='General'";
$rsConfig = mysql_query($sSQL);			// Can't use RunQuery -- not defined yet
if ($rsConfig) {
    while (list($cfg_name, $value) = mysql_fetch_row($rsConfig)) {
        $$cfg_name = $value;
    }
}


// Read values from config table into local variables
// **************************************************
$sSQL = "SELECT cfg_name, IFNULL(cfg_value, cfg_default) AS value "
      . "FROM config_cfg WHERE cfg_section='ChurchInfoReport'";
$rsConfig = mysql_query($sSQL);			// Can't use RunQuery -- not defined yet
if ($rsConfig) {
    while (list($cfg_name, $value) = mysql_fetch_row($rsConfig)) {
        $$cfg_name = $value;
    }
}

if (isset($_SESSION['iUserID'])) {      // Not set on Default.php
    // Load user variables from user config table.
    // **************************************************
    $sSQL = "SELECT ucfg_name, ucfg_value AS value "
          . "FROM userconfig_ucfg WHERE ucfg_per_ID='".$_SESSION['iUserID']."'";
    $rsConfig = mysql_query($sSQL);     // Can't use RunQuery -- not defined yet
    if ($rsConfig) {
        while (list($ucfg_name, $value) = mysql_fetch_row($rsConfig)) {
            $$ucfg_name = $value;
				$_SESSION[$ucfg_name] = $value;
//				echo "<br>".$ucfg_name." ".$_SESSION[$ucfg_name];
        }
    }
}

$sMetaRefresh = '';  // Initialize to empty

require_once("winlocalelist.php");

if (!function_exists("stripos")) {
  function stripos($str,$needle) {
   return strpos(strtolower($str),strtolower($needle));
  }
}

//if (!(stripos(php_uname('s'), "windows") === false)) {
//	$sLanguage = $lang_map_windows[strtolower($sLanguage)];
//	$sLang_Code = $lang_map_windows[strtolower($sLanguage)];
//} else {
//	$sLang_Code = $sLanguage;
//}
$sLang_Code="en_EN";

putenv("LANG=$sLang_Code");
setlocale(LC_ALL, $sLang_Code);

// Get numeric and monetary locale settings.
$aLocaleInfo = localeconv();

// This is needed to avoid some bugs in various libraries like fpdf.
setlocale(LC_NUMERIC, 'C');

// patch some missing data for Italian.  This shouldn't be necessary!
if ($sLanguage == 'it_IT')
{
    $aLocaleInfo['thousands_sep'] = '.';
    $aLocaleInfo['frac_digits'] = '2';
}

if (function_exists('bindtextdomain'))
{
    $domain = 'messages';

    $sLocaleDir = 'locale';
    if (!is_dir($sLocaleDir))
        $sLocaleDir = '../' . $sLocaleDir;

    bind_textdomain_codeset ($domain, 'UTF-8' );
    bindtextdomain($domain, $sLocaleDir);
    textdomain($domain);
}
else
{
    if ($sLanguage != 'en_US')
    {
        // PHP array version of the l18n strings
        $sLocaleMessages = "locale/$sLanguage/LC_MESSAGES/messages.php";

        if (!is_readable($sLocaleMessages))
            $sLocaleMessages = "../$sLocaleMessages";

        require ($sLocaleMessages);

        // replacement implementation of gettext for broken installs
        function gettext($text)
        {
            global $locale;

            if (!empty($locale[$text]))
                return $locale[$text];
            else
                return $text;
        }
    }
    else
    {
        // dummy gettext function
        function gettext($text)
        {
            return $text;
        }
    }

    function _($text) { return gettext($text); }
}
?>
