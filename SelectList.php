<?php
/*******************************************************************************
*
*  filename    : SelectList.php
*  website     : http://www.churchdb.org
*  copyright   : Copyright 2001-2003 Deane Barker and Chris Gebhardt
*
*  Additional Contributors:
*  2006 Ed Davis
*  2008 Erwin Pratama for GKJ Bekasi WIl Timur ( http://www.gkjbekasi-wiltimur.net )
*  2009 Erwin Pratama for GKJ Bekasi Timur ( http://www.gkjbekasitimur.org )
*  2010 Erwin Pratama for GKPB Bali ( http://www.balichurchsynod.org/ )
*  2013 Erwin Pratama for GKJ Tanjung Priok ( http://www.gkjtp.com )
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

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";
$Kategori = $_GET["Kategori"];
$iTenThousand = 10000;  // Constant used to offset negative choices in drop down lists

$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;



// Create array with GolDarah Information (lst_ID = 20)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=20 ORDER BY lst_OptionSequence";
$rsPersonGolDarah = RunQuery($sClassSQL);
unset($aPersonGolDarahName);
$aPersonGolDarahName[0] = " - ";
while ($aRow = mysql_fetch_array($rsPersonGolDarah))
{
	extract($aRow);
	$aPersonGolDarahName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with Pendidikan Information (lst_ID = 18)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=18 ORDER BY lst_OptionSequence";
$rsPersonPendidikan = RunQuery($sClassSQL);
unset($aPersonPendidikanName);
$aPersonPendidikanName[0] = " - ";
while ($aRow = mysql_fetch_array($rsPersonPendidikan))
{
	extract($aRow);
	$aPersonPendidikanName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with Pekerjaan Information (lst_ID = 17)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=17 ORDER BY lst_OptionSequence";
$rsPersonPekerjaan = RunQuery($sClassSQL);
unset($aPersonPekerjaanName);
$aPersonPekerjaanName[0] = " - ";
while ($aRow = mysql_fetch_array($rsPersonPekerjaan))
{
	extract($aRow);
	$aPersonPekerjaanName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with Jabatan Information (lst_ID = 19)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=19 ORDER BY lst_OptionSequence";
$rsPersonJabatan = RunQuery($sClassSQL);
unset($aPersonJabatanName);
$aPersonJabatanName[0] = " - ";
while ($aRow = mysql_fetch_array($rsPersonJabatan))
{
	extract($aRow);
	$aPersonJabatanName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with Profesi Information (lst_ID = 24)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=24 ORDER BY lst_OptionSequence";
$rsPersonProfesi = RunQuery($sClassSQL);
unset($aPersonProfesiName);
$aPersonProfesiName[0] = " - ";
while ($aRow = mysql_fetch_array($rsPersonProfesi))
{
	extract($aRow);
	$aPersonProfesiName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with Hobi Information (lst_ID = 22)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=22 ORDER BY lst_OptionSequence";
$rsPersonHobi = RunQuery($sClassSQL);
unset($aPersonHobiName);
$aPersonHobiName[0] = " - ";
while ($aRow = mysql_fetch_array($rsPersonHobi))
{
	extract($aRow);
	$aPersonHobiName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with Minat Information (lst_ID = 25)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=25 ORDER BY lst_OptionSequence";
$rsPersonMinat = RunQuery($sClassSQL);
unset($aPersonMinatName);
$aPersonMinatName[0] = " - ";
while ($aRow = mysql_fetch_array($rsPersonMinat))
{
	extract($aRow);
	$aPersonMinatName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with MinatPly Information (lst_ID = 26)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=26 ORDER BY lst_OptionSequence";
$rsPersonMinatPly = RunQuery($sClassSQL);
unset($aPersonMinatPlyName);
$aPersonMinatPlyName[0] = " - ";
while ($aRow = mysql_fetch_array($rsPersonMinatPly))
{
	extract($aRow);
	$aPersonMinatPlyName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with StatusKawin Information (lst_ID = 23)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=23 ORDER BY lst_OptionSequence";
$rsPersonStatusKawin = RunQuery($sClassSQL);
unset($aPersonStatusKawinName);
$aPersonStatusKawinName[0] = " - ";
while ($aRow = mysql_fetch_array($rsPersonStatusKawin))
{
	extract($aRow);
	$aPersonStatusKawinName[intval($lst_OptionID)]=$lst_OptionName;
}


// Create array with Classification Information (lst_ID = 1)
$sClassSQL  = "SELECT * FROM list_lst WHERE lst_ID=1 ORDER BY lst_OptionSequence";
$rsClassification = RunQuery($sClassSQL);
unset($aClassificationName);
$aClassificationName[0] = " - ";
while ($aRow = mysql_fetch_array($rsClassification))
{
	extract($aRow);
	$aClassificationName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with Family Role Information (lst_ID = 2)
$sFamRoleSQL  = "SELECT * FROM list_lst WHERE lst_ID=2 ORDER BY lst_OptionSequence";
$rsFamilyRole = RunQuery($sFamRoleSQL);
unset($aFamilyRoleName);
$aFamilyRoleName[0] = " - ";
while ($aRow = mysql_fetch_array($rsFamilyRole))
{
	extract($aRow);
	$aFamilyRoleName[intval($lst_OptionID)]=$lst_OptionName;
}

// Create array with Person Property

 // Get the total number of Person Properties (p) in table Property_pro
$sSQL = "SELECT * FROM property_pro WHERE pro_Class=\"p\"";
$rsPro = RunQuery($sSQL);
$ProRows = mysql_num_rows($rsPro);
// Set a count variable
$i = 1;

$sPersonPropertySQL  = "SELECT * FROM property_pro WHERE pro_Class=\"p\" ORDER BY pro_Name";
$rsPersonProperty = RunQuery($sPersonPropertySQL);
unset($aPersonPropertyName);
$aPersonPropertyName[0] = " - ";
while ( $i <= $ProRows ) {
	$aRow = mysql_fetch_array($rsPersonProperty);
	extract($aRow);
	$aPersonPropertyName[intval($pro_ID)]=$pro_Name;
	$i++;
}

// Create array with Group Type Information (lst_ID = 3)
$sGroupTypeSQL  = "SELECT * FROM list_lst WHERE lst_ID=3 ORDER BY lst_OptionSequence";
$rsGroupTypes = RunQuery($sGroupTypeSQL);
unset($aGroupTypes);
while ($aRow = mysql_fetch_array($rsGroupTypes))
{
	extract($aRow);
	$aGroupTypes[intval($lst_OptionID)]=$lst_OptionName;
}

// Filter received user input as needed
if (strlen($_GET["Sort"]))
        $sSort = FilterInput($_GET["Sort"]);
else
        $sSort = "name";

if (isset($_GET["PrintView"]))
	$bPrintView = true;
if (strlen($_GET["Filter"]))
	$sFilter = FilterInput($_GET["Filter"]);
if (strlen($_GET["Letter"]))
	$sLetter = mb_strtoupper(FilterInput($_GET["Letter"]));


if (strlen($_GET["mode"]))
	$sMode = FilterInput($_GET["mode"]);
elseif (isset($_SESSION['SelectListMode']))
	$sMode = $_SESSION['SelectListMode'];

switch ($sMode)
	{
	case ('groupassign'):
		$_SESSION['SelectListMode'] = $sMode;
		break;
	case ('family'):
		$_SESSION['SelectListMode'] = $sMode;
		break;
	case ('mail'):
		$_SESSION['SelectListMode'] = $sMode;
		break;	
	default:
		$_SESSION['SelectListMode'] = 'person';
		break;
	}

// Save default search mode
$_SESSION['bSearchFamily'] = ($sMode != 'person');



if (isset($_GET["Number"]))
{
    $_SESSION['SearchLimit'] = FilterInput($_GET["Number"],'int');
    $uSQL =	" UPDATE user_usr SET usr_SearchLimit = " . $_SESSION['SearchLimit'] .
			" WHERE usr_per_ID = " . $_SESSION['iUserID'];
    $rsUser = RunQuery($uSQL);
}

if (isset($_GET["PersonColumn3"])){
	$_SESSION['sPersonColumn3'] = FilterInput($_GET["PersonColumn3"]);
}

if (isset($_GET["PersonColumn5"])){
	$_SESSION['sPersonColumn5'] = FilterInput($_GET["PersonColumn5"]);
}


// Set the page title
if ($sMode == 'person')
{
    $sPageTitle = gettext("Daftar Jemaat");

	$logvar1 = "Listing";
	$logvar2 = "Person";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

    $iMode = 1;

	if (strlen($_GET["PersonGolDarah"]))
		$iPersonGolDarah = FilterInput($_GET["PersonGolDarah"],'int');
	if (strlen($_GET["PersonPendidikan"]))
		$iPersonPendidikan = FilterInput($_GET["PersonPendidikan"],'int');
	if (strlen($_GET["PersonPekerjaan"]))
		$iPersonPekerjaan = FilterInput($_GET["PersonPekerjaan"],'int');
	if (strlen($_GET["PersonJabatan"]))
		$iPersonJabatan = FilterInput($_GET["PersonJabatan"],'int');
	if (strlen($_GET["PersonProfesi"]))
		$iPersonProfesi = FilterInput($_GET["PersonProfesi"],'int');
	if (strlen($_GET["PersonHobi"]))
		$iPersonHobi = FilterInput($_GET["PersonHobi"],'int');
	if (strlen($_GET["PersonMinat"]))
		$iPersonMinat = FilterInput($_GET["PersonMinat"],'int');
	if (strlen($_GET["PersonMinatPly"]))
		$iPersonMinatPly = FilterInput($_GET["PersonMinatPly"],'int');
	if (strlen($_GET["PersonStatusKawin"]))
		$iPersonStatusKawin = FilterInput($_GET["PersonStatusKawin"],'int');
	if (strlen($_GET["Classification"]))
		$iClassification = FilterInput($_GET["Classification"],'int');
	if (strlen($_GET["FamilyRole"]))
		$iFamilyRole = FilterInput($_GET["FamilyRole"],'int');
    if (strlen($_GET["Gender"]))
		$iGender = FilterInput($_GET["Gender"],'int');
	if (strlen($_GET["PersonProperties"]))
		$iPersonProperty = FilterInput($_GET["PersonProperties"],'int');
    if (strlen($_GET["grouptype"]))
    {
        $iGroupType = FilterInput($_GET["grouptype"],'int');
        if (strlen($_GET["groupid"]))
        {
            $iGroupID = FilterInput($_GET["groupid"],'int');
            if ($iGroupID == 0) unset($iGroupID);
        }
        if (strlen($_GET["grouproleid"]))
        {
            $iRoleID = FilterInput($_GET["grouproleid"],'int');
            if ($iRoleID == 0) unset($iRoleID);
        }
    }
}
elseif ($sMode == 'groupassign')
{
    $sPageTitle = gettext("Group Assignment Helper");
    $iMode = 2;

	if (strlen($_GET["PersonGolDarah"]))
		$iPersonGolDarah = FilterInput($_GET["PersonGolDarah"],'int');
	if (strlen($_GET["PersonPendidikan"]))
		$iPersonPendidikan = FilterInput($_GET["PersonPendidikan"],'int');
	if (strlen($_GET["PersonPekerjaan"]))
		$iPersonPekerjaan = FilterInput($_GET["PersonPekerjaan"],'int');
	if (strlen($_GET["PersonJabatan"]))
		$iPersonJabatan = FilterInput($_GET["PersonJabatan"],'int');
	if (strlen($_GET["PersonProfesi"]))
		$iPersonProfesi = FilterInput($_GET["PersonProfesi"],'int');
	if (strlen($_GET["PersonHobi"]))
		$iPersonHobi = FilterInput($_GET["PersonHobi"],'int');
	if (strlen($_GET["PersonMinat"]))
		$iPersonMinat = FilterInput($_GET["PersonMinat"],'int');
	if (strlen($_GET["PersonMinatPly"]))
		$iPersonMinatPly = FilterInput($_GET["PersonMinatPly"],'int');
	if (strlen($_GET["PersonStatusKawin"]))
		$iPersonStatusKawin = FilterInput($_GET["PersonStatusKawin"],'int');
	if (strlen($_GET["Classification"]))
		$iClassification = FilterInput($_GET["Classification"],'int');
	if (strlen($_GET["FamilyRole"]))
		$iFamilyRole = FilterInput($_GET["FamilyRole"],'int');
    if (strlen($_GET["Gender"]))
		$iGender = FilterInput($_GET["Gender"],'int');
    if (isset($_GET["type"]))
        $iGroupTypeMissing = FilterInput($_GET["type"],'int');
    else
        $iGroupTypeMissing = 1;
}
elseif ($sMode == 'mail')
{
    $sPageTitle = gettext("Daftar Surat Masuk");
	$logvar1 = "Listing";
	$logvar2 = "Incoming Mail";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 4;
}
elseif ($sMode == 'pak')
{
    $sPageTitle = gettext("Daftar Nilai Pendidikan Agama Kristen");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Nilai PAK";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 5;
}
elseif ($sMode == 'aset')
{
    $sPageTitle = gettext("Daftar Aset Gereja");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Aset Gereja";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 6;
}
elseif ($sMode == 'Persembahan')
{
    $sPageTitle = gettext("Daftar Persembahan Gereja");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Persembahan Gereja";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 7;
}
elseif ($sMode == 'PersembahanAnak')
{
    $sPageTitle = gettext("Daftar Persembahan " . $Kategori . " Gereja");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Persembahan " . $Kategori . " Gereja";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 8;
}
elseif ($sMode == 'FormulirUmum')
{
    $sPageTitle = gettext("Daftar " . $Kategori . " Gereja");
	$logvar1 = "Listing";
	$logvar2 = "Daftar " . $Kategori . " Gereja";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 9;
}
elseif ($sMode == 'soalpak')
{
    $sPageTitle = gettext("Daftar Soal Pendidikan Agama Kristen");
	$logvar1 = "Listing";
	$logvar2 = "Daftar Soal PAK";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 10;
}
else
{
    $sPageTitle = gettext("Daftar Keluarga");
	$logvar1 = "Listing";
	$logvar2 = "Family";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $sFilter . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);
    $iMode = 3;
}

$iPerPage = $_SESSION['SearchLimit'];

if (!$PrintView)
		$sHeaderFile = "Include/Header.php";
else
		$sHeaderFile = "Include/Header-Short.php";

if ($iMode == 1 || $iMode == 2)
{
    // SQL for group-assignment helper
    if ($iMode == 2)
    {
    	//$sBaseSQL = "SELECT *, IF(LENGTH(per_Zip) > 0,per_Zip,fam_Zip) AS zip " .
    	$sBaseSQL = "SELECT * " .
					"FROM person_per NATURAL JOIN person_custom LEFT JOIN family_fam " .
					"ON person_per.per_fam_ID = family_fam.fam_ID  ";

        // Find people who are part of a group of the specified type.
        // MySQL doesn't have subqueries until version 4.1.. for now, do it the hard way
        $sSQLsub =	"SELECT person_per.per_ID FROM NATURAL JOIN person_custom person_per LEFT JOIN person2group2role_p2g2r " .
					"ON p2g2r_per_ID = person_per.per_ID LEFT JOIN group_grp " .
					"ON grp_ID = p2g2r_grp_ID " .
					"WHERE grp_Type = $iGroupTypeMissing GROUP BY person_per.per_ID";
        $rsSub = RunQuery($sSQLsub);

        if (mysql_num_rows($rsSub) > 0)
        {
            $sExcludedIDs = "";
            while ($aTemp = mysql_fetch_row($rsSub))
            {
                $sExcludedIDs .= $aTemp[0] . ",";
            }
            $sExcludedIDs = mb_substr($sExcludedIDs,0,-1);
            $sGroupWhereExt = " AND person_per.per_ID NOT IN (" . $sExcludedIDs . ")";
        }
 	}

    // SQL for standard Person List
    if ($iMode == 1)
    {
        // Set the base SQL


       $sBaseSQL =	"SELECT *  " .
       // $sBaseSQL =	"SELECT *, IF(LENGTH(per_Zip) > 0,per_Zip,fam_Zip) AS zip  " .
					"FROM person_per NATURAL JOIN person_custom LEFT JOIN family_fam " .
					"ON per_fam_ID = family_fam.fam_ID ";


		$sGroupWhereExt = ""; // Group Filtering Logic
		$sJoinExt = "";
		if (isset($iGroupType))
		{
			if ($iGroupType >= 0)
			{
				$sJoinExt = " LEFT JOIN person2group2role_p2g2r ON person_per.per_ID = p2g2r_per_ID ".
							" LEFT JOIN group_grp ON grp_ID = p2g2r_grp_ID ";
				$sGroupWhereExt =	" AND grp_type = ".$iGroupType." ";

				if (isset($iGroupID))
					if ($iGroupID >= 0)
					{
                        if (isset($iRoleID))
                        {
                            $sJoinExt = " LEFT JOIN person2group2role_p2g2r ".
                                        " ON person_per.per_ID = p2g2r_per_ID ".
                                        " LEFT JOIN list_lst ".
                                        " ON p2g2r_grp_ID = lst_ID ";
                            $sGroupWhereExt =   " AND p2g2r_grp_ID=".$iGroupID." " .
                                                " AND p2g2r_per_ID= person_per.per_ID " .
                                                " AND p2g2r_rle_ID=".$iRoleID." ";
                        }
                        else
                        {
                            $sJoinExt =	" LEFT JOIN person2group2role_p2g2r ".
                                        " ON person_per.per_ID = p2g2r_per_ID ";
                            $sGroupWhereExt =	" AND p2g2r_grp_ID=".$iGroupID." ".
                                                " AND p2g2r_per_ID = person_per.per_ID ";
                        }
					}
					else
					{
						$sJoinExt =	" LEFT JOIN person2group2role_p2g2r ".
									" ON person_per.per_ID = p2g2r_per_ID " .
									" LEFT JOIN group_grp ON grp_ID = p2g2r_grp_ID ";
						$sGroupWhereExt =	" AND grp_type=".$iGroupType." ".
											" AND person_per.per_ID NOT IN ".
                                            " (SELECT p2g2r_per_ID FROM person2group2role_p2g2r ".
                                            "  WHERE p2g2r_grp_ID=".($iGroupID+$iTenThousand).") ";
					}
 			}
			else
			{
				$sJoinExt = " ";
				$sGroupWhereExt =	" AND person_per.per_ID NOT IN (SELECT p2g2r_per_ID ".
                                    " FROM person2group2role_p2g2r ".
                                    " LEFT JOIN group_grp ON grp_ID = p2g2r_grp_ID ".
                                    " WHERE grp_type = ".($iGroupType+$iTenThousand).")";
			}
		}
    }

			$sPersonPropertyWhereExt = ""; // Person Property Filtering Logic
		$sJoinExt2 = "";
		if (isset($iPersonProperty)) {
			if ($iPersonProperty >= 0)
			{
				$sJoinExt2 = " LEFT JOIN record2property_r2p ON person_per.per_ID = r2p_record_ID "; // per_ID should match the r2p_record_ID
				$sPersonPropertyWhereExt =	" AND r2p_pro_ID = ".$iPersonProperty." ";
			}
			else // >>>> THE SQL CODE BELOW IS NOT TESTED PROPERLY <<<<<
			{
				$sJoinExt2 = " ";
				$sPersonPropertyWhereExt =	" AND person_per.per_ID NOT IN (SELECT r2p_record_ID ".
									" FROM record2property_r2p ".
									" WHERE r2p_pro_ID = ".($iPersonProperty+$iTenThousand).")";
			}
		$sJoinExt .= $sJoinExt2; // We add our new SQL statement to the JoinExt variable from the group type.
		}




    if (isset($sFilter))
    {
		if  (is_numeric($sFilter)) {
              $sFilterWhereExt =	" AND (per_ID LIKE '%" . $sFilter . "%' " .
								" )";     

		}
		// Check if there's a space
		
		else if (strstr($sFilter," "))
		
		
        {
            // break on the space...
            $aFilter = explode(" ",$sFilter);

            // use the results to check the first and last names
            $sFilterWhereExt =	" AND per_FirstName LIKE '%" . $aFilter[0] . "%' " .
								" ";
					//			" AND per_LastName LIKE '%" . $aFilter[1] . "%' ";
        } else
		{
            $sFilterWhereExt =	" AND (per_FirstName LIKE '%" . $sFilter . "%' " .
								" )";
					//			" OR per_LastName LIKE '%" . $sFilter . "%') ";
		}
    }

	$sClassificationWhereExt = "";
	if (isset($iClassification))
		if ($iClassification >= 0)
			$sClassificationWhereExt = " AND per_cls_ID=".$iClassification." ";
		else
			$sClassificationWhereExt = " AND per_cls_ID!=".
											($iClassification+$iTenThousand)." ";

	$sPersonGolDarahWhereExt = "";
		if (isset($iPersonGolDarah))
			if ($iPersonGolDarah >= 0)
				$sPersonGolDarahWhereExt = " AND c6 =".$iPersonGolDarah." ";
			else
				$sPersonGolDarahWhereExt = " AND c6 !=".
												($iPersonGolDarah+$iTenThousand)." ";
	$sPersonPendidikanWhereExt = "";
		if (isset($iPersonPendidikan))
			if ($iPersonPendidikan >= 0)
				$sPersonPendidikanWhereExt = " AND c4 =".$iPersonPendidikan." ";
			else
				$sPersonPendidikanWhereExt = " AND c4 !=".

	$sPersonPekerjaanWhereExt = "";
		if (isset($iPersonPekerjaan))
			if ($iPersonPekerjaan >= 0)
				$sPersonPekerjaanWhereExt = " AND c3 =".$iPersonPekerjaan." ";
			else
				$sPersonPekerjaanWhereExt = " AND c3 !=".
												($iPersonPekerjaan+$iTenThousand)." ";
												($iPersonPekerjaan+$iTenThousand)." ";
	$sPersonJabatanWhereExt = "";
		if (isset($iPersonJabatan))
			if ($iPersonJabatan >= 0)
				$sPersonJabatanWhereExt = " AND c5 =".$iPersonJabatan." ";
			else
				$sPersonJabatanWhereExt = " AND c5 !=".
												($iPersonJabatan+$iTenThousand)." ";
												($iPersonJabatan+$iTenThousand)." ";
	$sPersonProfesiWhereExt = "";
		if (isset($iPersonProfesi))
			if ($iPersonProfesi >= 0)
				$sPersonProfesiWhereExt = " AND c19 =".$iPersonProfesi." ";
			else
				$sPersonProfesiWhereExt = " AND c19 !=".
												($iPersonProfesi+$iTenThousand)." ";
												($iPersonProfesi+$iTenThousand)." ";

	$sPersonHobiWhereExt = "";
		if (isset($iPersonHobi))
			if ($iPersonHobi >= 0)
				$sPersonHobiWhereExt = " AND c14 =".$iPersonHobi." ";
			else
				$sPersonHobiWhereExt = " AND c14 !=".
												($iPersonHobi+$iTenThousand)." ";
												($iPersonHobi+$iTenThousand)." ";

	$sPersonMinatWhereExt = "";
		if (isset($iPersonMinat))
			if ($iPersonMinat >= 0)
				$sPersonMinatWhereExt = " AND c20 =".$iPersonMinat." ";
			else
				$sPersonMinatWhereExt = " AND c20 !=".
												($iPersonMinat+$iTenThousand)." ";
												($iPersonMinat+$iTenThousand)." ";

	$sPersonMinatPlyWhereExt = "";
		if (isset($iPersonMinatPly))
			if ($iPersonMinatPly >= 0)
				$sPersonMinatPlyWhereExt = " AND c35 =".$iPersonMinatPly." ";
			else
				$sPersonMinatPlyWhereExt = " AND c35 !=".
												($iPersonMinatPly+$iTenThousand)." ";
												($iPersonMinatPly+$iTenThousand)." ";

	$sPersonStatusKawinWhereExt = "";
		if (isset($iPersonStatusKawin))
			if ($iPersonStatusKawin >= 0)
				$sPersonStatusKawinWhereExt = " AND c15 =".$iPersonStatusKawin." ";
			else
				$sPersonStatusKawinWhereExt = " AND c15 !=".
												($iPersonStatusKawin+$iTenThousand)." ";





	$sFamilyRoleWhereExt = "";
	if (isset($iFamilyRole))
		if ($iFamilyRole >= 0)
			$sFamilyRoleWhereExt = " AND per_fmr_ID=".$iFamilyRole." ";
		else
			$sFamilyRoleWhereExt = " AND per_fmr_ID!=".($iFamilyRole+$iTenThousand)." ";

    if (isset($iGender))
        $sGenderWhereExt = " AND per_Gender = " . $iGender;
	else
		$sGenderWhereExt = "";
    if (isset($sLetter))
        $sLetterWhereExt = " AND per_FirstName LIKE '" . $sLetter . "%'";
//code asli         $sLetterWhereExt = " AND per_LastName LIKE '" . $sLetter . "%'";

	else
		$sLetterWhereExt = "";

	$sGroupBySQL = " GROUP BY person_per.per_ID";

     $sWhereExt =	$sGroupWhereExt . $sFilterWhereExt . $sClassificationWhereExt .
					$sFamilyRoleWhereExt . $sGenderWhereExt . $sLetterWhereExt . $sPersonPropertyWhereExt .
					$sPersonGolDarahWhereExt . $sPersonPendidikanWhereExt . $sPersonPekerjaanWhereExt .
					$sPersonJabatanWhereExt .$sPersonProfesiWhereExt .$sPersonHobiWhereExt .
					$sPersonMinatWhereExt .$sPersonMinatPlyWhereExt .
					$sPersonStatusKawinWhereExt  ;

	$sSQL = $sBaseSQL . $sJoinExt . " WHERE 1" . $sWhereExt . $sGroupBySQL;

//echo $sSQL; 

// URL to redirect back to this same page
	$sRedirect = "SelectList.php?";
	if (isset($_GET["mode"])) $sRedirect .= "mode=" . $_GET["mode"] . "&amp;";
	if (isset($_GET["type"])) $sRedirect .= "type=" . $_GET["type"] . "&amp;";
	if (isset($_GET["Filter"])) $sRedirect .= "Filter=" . $_GET["Filter"] . "&amp;";
	if (isset($_GET["Sort"])) $sRedirect .= "Sort=" . $_GET["Sort"] . "&amp;";
	if (isset($_GET["Letter"])) $sRedirect .= "Letter=" . $_GET["Letter"] . "&amp;";
	if (isset($_GET["Classification"])) $sRedirect .= "Classification=" . $_GET["Classification"] . "&amp;";
	if (isset($_GET["FamilyRole"])) $sRedirect .= "FamilyRole=" . $_GET["FamilyRole"] . "&amp;";
	if (isset($_GET["Gender"])) $sRedirect .= "Gender=" . $_GET["Gender"] . "&amp;";
	if (isset($_GET["grouptype"])) $sRedirect .= "grouptype=" . $_GET["grouptype"] . "&amp;";
	if (isset($_GET["groupid"])) $sRedirect .= "groupid=" . $_GET["groupid"] . "&amp;";
	if (isset($_GET["grouproleid"])) $sRedirect .= "grouproleid=" . $_GET["grouproleid"] . "&amp;";
	if (isset($_GET["Number"])) $sRedirect .= "Number=" . $_GET["Number"] . "&amp;";
	if (isset($_GET["Result_Set"])) $sRedirect .= "Result_Set=" . $_GET["Result_Set"] . "&amp;";
	if (isset($_GET["PersonGolDarah"])) $sRedirect .= "PersonGolDarah=" . $_GET["PersonGolDarah"] . "&amp;";
	if (isset($_GET["PersonPendidikan"])) $sRedirect .= "PersonPendidikan=" . $_GET["PersonPendidikan"] . "&amp;";
	if (isset($_GET["PersonPekerjaan"])) $sRedirect .= "PersonPekerjaan=" . $_GET["PersonPekerjaan"] . "&amp;";
	if (isset($_GET["PersonJabatan"])) $sRedirect .= "PersonJabatan=" . $_GET["PersonJabatan"] . "&amp;";
	if (isset($_GET["PersonProfesi"])) $sRedirect .= "PersonProfesi=" . $_GET["PersonProfesi"] . "&amp;";
	if (isset($_GET["PersonHobi"])) $sRedirect .= "PersonHobi=" . $_GET["PersonHobi"] . "&amp;";
	if (isset($_GET["PersonMinat"])) $sRedirect .= "PersonMinat=" . $_GET["PersonMinat"] . "&amp;";
	if (isset($_GET["PersonMinatPly"])) $sRedirect .= "PersonMinatPly=" . $_GET["PersonMinatPly"] . "&amp;";
	if (isset($_GET["PersonStatusKawin"])) $sRedirect .= "PersonStatusKawin=" . $_GET["PersonStatusKawin"] . "&amp;";

	$random1=rand(100000000,999999999);
	$random2=microtime(TRUE);
	$refresh=$random1+$random2;
	$sRedirect = $sRedirect."&amp".$refresh. "&amp;";
	
	$sRedirect = mb_substr($sRedirect,0,-5); // Chop off last &amp;

    // If AddToCart submit button was used, run the query, add people to cart, and view cart
    if (isset($_GET["AddAllToCart"]))
    {
        $rsPersons = RunQuery($sSQL);
        while ($aRow = mysql_fetch_row($rsPersons))
        {
            AddToPeopleCart($aRow[0]);
        }

    } elseif (isset($_GET["IntersectCart"]))
    {
        $rsPersons = RunQuery($sSQL);
        while ($aRow = mysql_fetch_row($rsPersons))
	        $aItemsToProcess[] = $aRow[0];

        if (isset($_SESSION['aPeopleCart']))
            $_SESSION['aPeopleCart'] = array_intersect($_SESSION['aPeopleCart'],$aItemsToProcess);

    } elseif (isset($_GET["RemoveFromCart"]))
    {
		$rsPersons = RunQuery($sSQL);
        while ($aRow = mysql_fetch_row($rsPersons))
        	$aItemsToProcess[] = $aRow[0];

        if (isset($_SESSION['aPeopleCart']))
        	$_SESSION['aPeopleCart'] = array_diff($_SESSION['aPeopleCart'],$aItemsToProcess);

	}

    // Get the total number of persons
    $rsPer = RunQuery($sSQL);
    $Total = mysql_num_rows($rsPer);

    // Select the proper sort SQL
    switch($sSort)
    {
        case "family":
                $sOrderSQL = " ORDER BY fam_Name";
                break;
        case "zip":
                $sOrderSQL = " ORDER BY zip, per_FirstName";
		//$sOrderSQL = " ORDER BY zip, per_LastName, per_FirstName";
                break;
        case "entered":
                $sOrderSQL = " ORDER BY per_DateEntered DESC";
                break;
        case "edited";
                $sOrderSQL = " ORDER BY per_DateLastEdited DESC";
                break;
        default:
                $sOrderSQL = " ORDER BY per_FirstName";
      		//$sOrderSQL = " ORDER BY per_LastName, per_FirstName";
                break;
    }

    // Regular PersonList display
    if (!$bPrintView)
    {
        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
            $Result_Set = 0;
        else
            $Result_Set = FilterInput($_GET['Result_Set'],'int');

        $sLimitSQL .= " LIMIT $Result_Set, $iPerPage";

        // Run the query with order and limit to get the final result for this list page
        $finalSQL = $sSQL . $sOrderSQL . $sLimitSQL;
        $rsPersons = RunQuery($finalSQL);

        // Run query to get first letters of last name.
		$sSQL = "SELECT DISTINCT LEFT(per_FirstName,1) AS letter FROM person_per NATURAL JOIN person_custom ".
//code asli $sSQL = "SELECT DISTINCT LEFT(per_LastName,1) AS letter FROM person_per NATURAL JOIN person_custom ".
//		$sSQL = "SELECT DISTINCT LEFT(per_FirstName,1) AS letter FROM person_per ".

				$sJoinExt . " WHERE 1 $sWhereExt ORDER BY letter";
		$rsLetters = RunQuery($sSQL);

		require "$sHeaderFile";

        echo "<form method=\"get\" action=\"SelectList.php\" name=\"PersonList\">";

        if ($iMode == 1)
        {
            echo "<p align=\"center\">";
            if ($_SESSION['bAddRecords'])
			{
            	echo "<a href=\"PersonEditor.php?GID=$refresh&\">";
				echo gettext("Tambah Data Jemaat Baru") . "</a><BR>";
			}

            echo "<a target=_blank href=\"SelectList.php?mode=$sMode&amp;type=$iGroupTypeMissing&amp;Filter=$sFilter&amp;
            Classification=$iClassification&amp;FamilyRole=$iFamilyRole&amp;Gender=$iGender&amp;
            grouptype=$iGroupType&amp;groupid=$iGroupID&amp;grouproleid=$iRoleID&amp;
            PersonGolDarah=$iPersonGolDarah&amp;PersonPendidikan=$iPersonPendidikan&amp;PersonPekerjaan=$iPersonPekerjaan&amp;
            PersonJabatan=$iPersonJabatan&amp;PersonProfesi=$iPersonProfesi&amp;PersonHobi=$iPersonHobi&amp;
            PersonMinat=$iPersonMinat&amp;PersonMinatPly=$iPersonMinatPly&amp;
            PersonStatusKawin=$iPersonStatusKawin&amp;";
            if($sSort)
				echo "&amp;Sort=$sSort";

            echo "&amp;Letter=$sLetter&amp;PrintView=1\">" . gettext("Lihat Daftar ini di Halaman yang bisa di Cetak") . "</a>";
        } else
		{
        	$sSQLtemp = "SELECT * FROM list_lst WHERE lst_ID = 3";
            $rsGroupTypes = RunQuery($sSQLtemp);
            echo '<p align="center" class="MediumText">' . gettext("Lihat Jemaat yang <b>bukan</b> dari jenis group ini:");
            echo '<select name="type" onchange="this.form.submit()">';
            while ($aRow = mysql_fetch_array($rsGroupTypes))
            {
                extract($aRow);
                echo '<option value="' . $lst_OptionID . '"';
                if ($iGroupTypeMissing == $lst_OptionID) { echo ' terpilih'; }
                echo '>' . $lst_OptionName . '&nbsp;';
            }
            echo "</select></p>";
        }

		?>

                <table align="center"><tr><td align="center">
                <?php echo gettext("Diurutkan berdasarkan :"); ?>
                <select name="Sort" onchange="this.form.submit()">
                        <option value="name" <?php if ($sSort == "name" || empty($sSort)) echo "selected";?>><?php echo gettext("Nama"); ?></option>
                        <option value="family" <?php if ($sSort == "family") echo "selected";?>><?php echo gettext("Keluarga"); ?></option>
                        <option value="zip" <?php if ($sSort == "zip") echo "selected";?>><?php echo gettext("KodePos"); ?></option>
                        <option value="entered" <?php if ($sSort == "entered") echo "selected";?>><?php echo gettext("Yang paling baru di Isi"); ?></option>
                        <option value="edited" <?php if ($sSort == "edited") echo "selected";?>><?php echo gettext("Yang paling baru di Edit"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter" value="<?php echo $sFilter;?>">
                <input type="hidden" name="mode" value="<?php echo $sMode;?>">
                <input type="hidden" name="Letter" value="<?php echo $sLetter;?>">
                <input type="submit" class="icButton" <?php echo 'value="' . gettext("Gunakan Filter") . '"'; ?>>

                </td></tr>
				<?php

				echo '	<tr><td align="center">
						<select name="Gender" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iGender))
					echo " selected ";
				echo '> ' . gettext ("Semua Jenis Kelamin") . '</option>';

				echo '<option value="1"';
				if ($iGender == 1)
					echo ' selected ';
				echo '> ' . gettext ("Laki-laki") . '</option>';

				echo '<option value="2"';
				if ($iGender == 2)
					echo ' selected ';
				echo '> ' . gettext ("Perempuan") . '</option></select>';

				// **********
				// Classification drop down list
				echo '	<select name="Classification" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iClassification))
					echo ' selected ';
				echo '>' . gettext("Semua Klasifikasi") . '</option>';

				foreach ($aClassificationName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iClassification))
						if ($iClassification == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}
				foreach ($aClassificationName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iClassification))
						if ($iClassification == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';

				// **********
				// PersonGolDarah drop down list
				echo '	<select name="PersonGolDarah" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iPersonGolDarah))
					echo ' selected ';
				echo '>' . gettext("Semua Gol Darah") . '</option>';

				foreach ($aPersonGolDarahName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonGolDarah))
						if ($iPersonGolDarah == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonGolDarahName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonGolDarah))
						if ($iPersonGolDarah == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';
				// **********
				// PersonPendidikan drop down list
				echo '	<select name="PersonPendidikan" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iPersonPendidikan))
					echo ' selected ';
				echo '>' . gettext("Semua Pendidikan") . '</option>';

				foreach ($aPersonPendidikanName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonPendidikan))
						if ($iPersonPendidikan == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonPendidikanName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonPendidikan))
						if ($iPersonPendidikan == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';

				// **********
				// PersonPekerjaan drop down list
				echo '	<select name="PersonPekerjaan" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iPersonPekerjaan))
					echo ' selected ';
				echo '>' . gettext("Semua Pekerjaan") . '</option>';

				foreach ($aPersonPekerjaanName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonPekerjaan))
						if ($iPersonPekerjaan == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonPekerjaanName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonPekerjaan))
						if ($iPersonPekerjaan == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';

				// **********
				// PersonJabatan drop down list
				echo '	<select name="PersonJabatan" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iPersonJabatan))
					echo ' selected ';
				echo '>' . gettext("Semua Jabatan") . '</option>';

				foreach ($aPersonJabatanName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonJabatan))
						if ($iPersonJabatan == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonJabatanName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonJabatan))
						if ($iPersonJabatan == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';

				// **********
				// PersonProfesi drop down list
				echo '	<select name="PersonProfesi" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iPersonProfesi))
					echo ' selected ';
				echo '>' . gettext("Semua Profesi") . '</option>';

				foreach ($aPersonProfesiName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonProfesi))
						if ($iPersonProfesi == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonProfesiName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonProfesi))
						if ($iPersonProfesi == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';

				// **********
				// PersonHobi drop down list
				echo '	<select name="PersonHobi" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iPersonHobi))
					echo ' selected ';
				echo '>' . gettext("Semua Hobi") . '</option>';

				foreach ($aPersonHobiName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonHobi))
						if ($iPersonHobi == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonHobiName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonHobi))
						if ($iPersonHobi == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';
				// **********
				// PersonMinat drop down list
				echo '	<select name="PersonMinat" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iPersonMinat))
					echo ' selected ';
				echo '>' . gettext("Semua Minat") . '</option>';

				foreach ($aPersonMinatName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonMinat))
						if ($iPersonMinat == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonMinatName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonMinat))
						if ($iPersonMinat == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';
				// **********
				// PersonMinatPly drop down list
				echo '	<select name="PersonMinatPly" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iPersonMinatPly))
					echo ' selected ';
				echo '>' . gettext("Semua MinatPelayanan") . '</option>';

				foreach ($aPersonMinatPlyName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonMinatPly))
						if ($iPersonMinatPly == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonMinatPlyName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonMinatPly))
						if ($iPersonMinatPly == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';

				// **********
				// PersonStatusKawin drop down list
				echo '	<select name="PersonStatusKawin" onchange="this.form.submit()">
						<option value="" ';
				if (!isset($iPersonStatusKawin))
					echo ' selected ';
				echo '>' . gettext("Semua Status Kawin") . '</option>';

				foreach ($aPersonStatusKawinName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonStatusKawin))
						if ($iPersonStatusKawin == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonStatusKawinName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonStatusKawin))
						if ($iPersonStatusKawin == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';


				// **********
				// Family Role Drop Down Box
				echo '<select name="FamilyRole" onchange="this.form.submit()">';
				echo '<option value="" ';
				if (!isset($iFamilyRole))
					echo ' selected ';
				echo '>' . gettext("Semua Peran Keluarga") . '</option>';

				foreach ($aFamilyRoleName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iFamilyRole))
						if ($iFamilyRole == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aFamilyRoleName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iFamilyRole))
						if ($iFamilyRole == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}

				echo '</select>';

				// Person Property Drop Down Box
				echo '<select name="PersonProperties" onchange="this.form.submit()">';
				echo '<option value="" ';
				if (!isset($iPersonProperty))
					echo ' selected ';
				echo '>' . gettext("Semua Properti Kontak") . '</option>';

				foreach ($aPersonPropertyName as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iPersonProperty))
						if ($iPersonProperty == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

				foreach ($aPersonPropertyName as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iPersonProperty))
						if ($iPersonProperty == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}

				echo '</select>';


				//grouptype drop down box
                if ($iMode == 1) {

				echo '<select name="grouptype" onchange="this.form.submit()">';

				echo '<option value="" ';
				if (!isset($iGroupType))
					echo ' selected ';
				echo '>' . gettext("Semua Jenis Grup") . '</option>';

 				foreach ($aGroupTypes as $key => $value)
				{
					echo '<option value="'.$key.'"';
					if (isset($iGroupType))
						if ($iGroupType == $key)
							echo ' selected ';
					echo '>'.$value.'</option>';
				}

 				foreach ($aGroupTypes as $key => $value)
				{
					echo '<option value="'.($key-$iTenThousand).'"';
					if (isset($iGroupType))
						if ($iGroupType == ($key-$iTenThousand))
							echo ' selected ';
					echo '>! '.$value.'</option>';
				}
				echo '</select>';


				if (isset($iGroupType) && ($iGroupType > -1))
				{
					// Create array with Group Information
					$sGroupsSQL  =	"SELECT * FROM group_grp WHERE grp_Type = $iGroupType " .
									"ORDER BY grp_Name ";

					$rsGroups = RunQuery($sGroupsSQL);
					unset($aGroupNames);
					while ($aRow = mysql_fetch_array($rsGroups))
					{
						extract($aRow);
						$aGroupNames[intval($grp_ID)]=$grp_Name;
					}

					echo '	<select name="groupid" onchange="this.form.submit()">
							<option value="" ';
					if (!isset($iGroupType)) echo ' selected ';
					echo '>' . gettext("All Groups") . '</option>';

 					foreach ($aGroupNames as $key => $value)
					{
						echo '<option value="'.$key.'"';
						if (isset($iGroupType))
							if ($iGroupID == $key)
								echo ' selected ';
						echo '>'.$value.'</option>';
					}

 					foreach ($aGroupNames as $key => $value)
					{
						echo '<option value="'.($key-$iTenThousand).'"';
						if (isset($iGroupType))
							if ($iGroupID == ($key-$iTenThousand))
								echo ' selected ';
						echo '>! '.$value.'</option>';
					}
					echo '</select>';
				}

                // *********
                // Create Group Role drop down box
				if (isset($iGroupID) && ($iGroupID > -1))
				{

                    // Get the group's role list ID
                    $sSQL = "SELECT grp_RoleListID ".
                            "FROM group_grp WHERE grp_ID =" . $iGroupID;
                    $aTemp = mysql_fetch_array(RunQuery($sSQL));
                    $iRoleListID = $aTemp[0];

                    // Get the roles
                    $sSQL = "SELECT * FROM list_lst WHERE lst_ID = " . $iRoleListID .
                            " ORDER BY lst_OptionSequence";
                    $rsRoles = RunQuery($sSQL);
					unset($aGroupRoles);
					while ($aRow = mysql_fetch_array($rsRoles))
					{
						extract($aRow);
						$aGroupRoles[intval($lst_OptionID)]=$lst_OptionName;
					}

					echo '	<select name="grouproleid" onchange="this.form.submit()">
							<option value="" ';
					if (!isset($iRoleID)) echo ' selected ';
					echo '>' . gettext("Semua Peran") . '</option>';

 					foreach ($aGroupRoles as $key => $value)
					{
						echo '<option value="'.$key.'"';
						if (isset($iRoleID))
							if ($iRoleID == $key)
								echo ' selected ';
						echo '>'.$value.'</option>';
					}
                    /*
 					foreach ($aGroupNames as $key => $value)
					{
						echo '<option value="'.($key-$iTenThousand).'"';
						if (isset($iGroupType))
							if ($iGroupID == ($key-$iTenThousand))
								echo ' selected ';
						echo '>! '.$value.'</option>';
					}*/
					echo '</select>';
				}



			} ?>

                        <input type="button" class="icButton" value="<?php echo gettext("Hilangkan Filter"); ?>" onclick="javascript:document.location='SelectList.php?mode=<?php echo $sMode; ?>&amp;Sort=<?php echo $sSort; ?>&amp;type=<?php echo $iGroupTypeMissing; ?>'"><BR><BR>
<?php
                 /**       <input name="AddAllToCart" type="submit" class="icButton" <?php echo 'value="' . gettext("Tambahkan ke keranjang") . '"'; ?>>&nbsp;
                 **       <input name="IntersectCart" type="submit" class="icButton" <?php echo 'value="' . gettext("Dibagi dengan Keranjang") . '"'; ?>>&nbsp;
                 **       <input name="RemoveFromCart" type="submit" class="icButton" <?php echo 'value="' . gettext("Hilangkan dari keranjang") . '"'; ?>>
**/
?>                        </td></tr>
						</table></form>
                <?php

                // Create Sort Links
                echo '<div align="center">';
                echo "<a href=\"SelectList.php?mode=$sMode&amp;type=$iGroupTypeMissing&amp;Filter=$sFilter&amp;
                Classification=$iClassification&amp;FamilyRole=$iFamilyRole&amp;Gender=$iGender&amp;
                grouptype=$iGroupType&amp;groupid=$iGroupID&amp;grouproleid=$iRoleID&amp;
                PersonGolDarah=$iPersonGolDarah&amp;PersonPendidikan=$iPersonPendidikan&amp;PersonPekerjaan=$iPersonPekerjaan&amp;
                PersonJabatan=$iPersonJabatan&amp;PersonProfesi=$iPersonProfesi&amp;PersonHobi=$iPersonHobi&amp;
                PersonMinat=$iPersonMinat&amp;PersonMinatPly=$iPersonMinatPly&amp;
                PersonStatusKawin=$iPersonStatusKawin&amp";
                if($sSort) echo "&amp;Sort=$sSort";
                echo "\">" . gettext("Lihat Daftar Semua") . "</a>";
                while ($aLetter = mysql_fetch_row($rsLetters))
                {
						$aLetter[0] = mb_strtoupper($aLetter[0]);
                        if ($aLetter[0] == $sLetter) {
                                echo "&nbsp;&nbsp;|&nbsp;&nbsp;" . $aLetter[0];
                        } else {
                                echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"SelectList.php?mode=$sMode&amp;type=$iGroupTypeMissing&amp;Filter=$sFilter&amp;
                                Classification=$iClassification&amp;FamilyRole=$iFamilyRole&amp;Gender=$iGender&amp;
                                grouptype=$iGroupType&amp;groupid=$iGroupID&amp;grouproleid=$iRoleID&amp;
                                PersonGolDarah=$iPersonGolDarah&amp;PersonPendidikan=$iPersonPendidikan&amp;PersonPekerjaan=$iPersonPekerjaan&amp;
                                PersonJabatan=$iPersonJabatan&amp;PersonProfesi=$iPersonProfesi&amp;PersonHobi=$iPersonHobi&amp;
                                PersonMinat=$iPersonMinat&amp;PersonMinatPly=$iPersonMinatPly&amp;
                                PersonStatusKawin=$iPersonStatusKawin&amp";
                                if($sSort) echo "&amp;Sort=$sSort";
                                echo "&amp;Letter=" . $aLetter[0] . "\">" . $aLetter[0] . "</a>";
                        }
                }
                echo "</div><BR>";

                // Create Next / Prev Links and $Result_Set Value
                if ($Total > 0)
                {
                        echo "<div align=\"center\">";
                        echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                        // Show previous-page link unless we're at the first page
                        if ($Result_Set < $Total && $Result_Set > 0)
                        {
                                $thisLinkResult = $Result_Set - $iPerPage;
                                echo "<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=$sMode&amp;type=$iGroupTypeMissing&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter&amp;
                                Classification=$iClassification&amp;FamilyRole=$iFamilyRole&amp;Gender=$iGender&amp;
                                grouptype=$iGroupType&amp;groupid=$iGroupID&amp;grouproleid=$iRoleID&amp;
                                PersonGolDarah=$iPersonGolDarah&amp;PersonPendidikan=$iPersonPendidikan&amp;PersonPekerjaan=$iPersonPekerjaan&amp;
                                PersonJabatan=$iPersonJabatan&amp;PersonProfesi=$iPersonProfesi&amp;PersonHobi=$iPersonHobi&amp;
                                PersonMinat=$iPersonMinat&amp;PersonMinatPly=$iPersonMinatPly&amp;
                                PersonStatusKawin=$iPersonStatusKawin&amp\">". gettext("Halaman Sebelumnya") . "</A>&nbsp;&nbsp;";
                        }

                        // Calculate starting and ending Page-Number Links
                        $Pages = ceil($Total / $iPerPage);
                        $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                        if ($startpage <= 2)
                                $startpage = 1;
                        $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                        if ($endpage >= ($Pages - 1))
                                $endpage = $Pages;

                        // Show Link "1 ..." if startpage does not start at 1
                        if ($startpage != 1)
                                echo "<a href=\"SelectList.php?Result_Set=0&amp;mode=$sMode&amp;type=$iGroupTypeMissing&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter&amp;
                                Classification=$iClassification&amp;FamilyRole=$iFamilyRole&amp;Gender=$iGender&amp;
                                grouptype=$iGroupType&amp;groupid=$iGroupID&amp;grouproleid=$iRoleID&amp;
                                PersonGolDarah=$iPersonGolDarah&amp;PersonPendidikan=$iPersonPendidikan&amp;PersonPekerjaan=$iPersonPekerjaan&amp;
                                PersonJabatan=$iPersonJabatan&amp;PersonProfesi=$iPersonProfesi&amp;PersonHobi=$iPersonHobi&amp;
                                PersonMinat=$iPersonMinat&amp;PersonMinatPly=$iPersonMinatPly&amp;
                                PersonStatusKawin=$iPersonStatusKawin&amp\">1</a> ... \n";

                        // Display page links
                        if ($Pages > 1)
                        {
                                for ($c = $startpage; $c <= $endpage; $c++)
                                {
                                        $b = $c - 1;
                                        $thisLinkResult = $iPerPage * $b;
                                        if ($thisLinkResult != $Result_Set)
                                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=$sMode&amp;type=$iGroupTypeMissing&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter&amp;
                                                Classification=$iClassification&amp;FamilyRole=$iFamilyRole&amp;Gender=$iGender&amp;
                                                grouptype=$iGroupType&amp;groupid=$iGroupID&amp;grouproleid=$iRoleID&amp;
                                                PersonGolDarah=$iPersonGolDarah&amp;PersonPendidikan=$iPersonPendidikan&amp;PersonPekerjaan=$iPersonPekerjaan&amp;
                                                PersonJabatan=$iPersonJabatan&amp;PersonProfesi=$iPersonProfesi&amp;PersonHobi=$iPersonHobi&amp;
                                                PersonMinat=$iPersonMinat&amp;PersonMinatPly=$iPersonMinatPly&amp;
                                                PersonStatusKawin=$iPersonStatusKawin&amp\">$c</a>&nbsp;\n";
                                        else
                                                echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                                }
                        }

                        // Show Link "... xx" if endpage is not the maximum number of pages
                        if ($endpage != $Pages)
                        {
                                $thisLinkResult = ($Pages - 1) * $iPerPage;
                                echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=$sMode&amp;type=$iGroupTypeMissing&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter&amp;
                                Classification=$iClassification&amp;FamilyRole=$iFamilyRole&amp;Gender=$iGender&amp;
                                grouptype=$iGroupType&amp;groupid=$iGroupID&amp;grouproleid=$iRoleID&amp;
                                PersonGolDarah=$iPersonGolDarah&amp;PersonPendidikan=$iPersonPendidikan&amp;PersonPekerjaan=$iPersonPekerjaan&amp;
                                PersonJabatan=$iPersonJabatan&amp;PersonProfesi=$iPersonProfesi&amp;PersonHobi=$iPersonHobi&amp;
                                PersonMinat=$iPersonMinat&amp;PersonMinatPly=$iPersonMinatPly&amp;
                                PersonStatusKawin=$iPersonStatusKawin&amp\">$Pages</a>\n";
                        }
                        // Show next-page link unless we're at the last page
                        if ($Result_Set >= 0 && $Result_Set < $Total)
                        {
                                $thisLinkResult=$Result_Set+$iPerPage;
                                if ($thisLinkResult<$Total)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=$sMode&amp;type=$iGroupTypeMissing&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter&amp;
                                        Classification=$iClassification&amp;FamilyRole=$iFamilyRole&amp;Gender=$iGender&amp;
                                        grouptype=$iGroupType&amp;groupid=$iGroupID&amp;grouproleid=$iRoleID&amp;
                                        PersonGolDarah=$iPersonGolDarah&amp;PersonPendidikan=$iPersonPendidikan&amp;PersonPekerjaan=$iPersonPekerjaan&amp;
                                        PersonJabatan=$iPersonJabatan&amp;PersonProfesi=$iPersonProfesi&amp;PersonHobi=$iPersonHobi&amp;
                                        PersonMinat=$iPersonMinat&amp;PersonMinatPly=$iPersonMinatPly&amp;
                                        PersonStatusKawin=$iPersonStatusKawin&amp\">" . gettext("Halaman Berikutnya") . "</a>";
                        }

                        echo '<input type="hidden" name="mode" value="';
						echo $sMode . '">';
                        if($iGroupTypeMissing > 0) {
                            echo '<input type="hidden" name="type" value="';
							echo $iGroupTypeMissing . '">'; }
                        if(isset($sFilter)) {
                            echo '<input type="hidden" name="Filter" value="';
							echo $sFilter . '">'; }
                        if(isset($sSort)) {
                            echo '<input type="hidden" name="Sort" value="';
							echo $sSort . '">'; }
                        if(isset($sLetter)) {
                            echo '<input type="hidden" name="Letter" value="';
							echo $sLetter . '">'; }
                        if(isset($iClassification)) {
                            echo '<input type="hidden" name="Classification" value="';
							echo $iClassification . '">'; }
						if(isset($iPersonGolDarah)) {
						    echo '<input type="hidden" name="PersonGolDarah" value="';
							echo $iPersonGolDarah . '">'; }
						if(isset($iPersonPendidikan)) {
						    echo '<input type="hidden" name="PersonPendidikan" value="';
							echo $iPersonPendidikan . '">'; }
						if(isset($iPersonPekerjaan)) {
						    echo '<input type="hidden" name="PersonPekerjaan" value="';
							echo $iPersonPekerjaan . '">'; }
						if(isset($iPersonJabatan)) {
						    echo '<input type="hidden" name="PersonJabatan" value="';
							echo $iPersonJabatan . '">'; }
						if(isset($iPersonProfesi)) {
						    echo '<input type="hidden" name="PersonProfesi" value="';
							echo $iPersonProfesi . '">'; }
						if(isset($iPersonHobi)) {
						    echo '<input type="hidden" name="PersonHobi" value="';
							echo $iPersonHobi . '">'; }
						if(isset($iPersonMinat)) {
						    echo '<input type="hidden" name="PersonMinat" value="';
							echo $iPersonMinat . '">'; }
						if(isset($iPersonMinatPly)) {
						    echo '<input type="hidden" name="PersonMinatPly" value="';
							echo $iPersonMinatPly . '">'; }
						if(isset($iPersonStatusKawin)) {
						    echo '<input type="hidden" name="PersonStatusKawin" value="';
							echo $iPersonStatusKawin . '">'; }
                        if(isset($iFamilyRole)) {
                            echo '<input type="hidden" name="FamilyRole" value="';
							echo $iFamilyRole . '">'; }
                        if(isset($iGender)) {
                            echo '<input type="hidden" name="Gender" value="';
							echo $iGender . '">'; }
                        if(isset($iGroupType)) {
                            echo '<input type="hidden" name="grouptype" value="';							echo $iGroupType . '">'; }
						if(isset($iPersonProperty)) {
                            echo '<input type="hidden" name="PersonProperties" value="';
							echo $iPersonProperty . '">'; }
                        if(isset($iGroupID)) {
                            echo '<input type="hidden" name="groupid" value="';
							echo $iGroupID . '">'; }

                        // Display record limit per page
                        if ($_SESSION['SearchLimit'] == "5")
                                $sLimit5 = "selected";
                        if ($_SESSION['SearchLimit'] == "10")
                                $sLimit10 = "selected";
                        if ($_SESSION['SearchLimit'] == "20")
                                $sLimit20 = "selected";
                        if ($_SESSION['SearchLimit'] == "25")
                                $sLimit25 = "selected";
                        if ($_SESSION['SearchLimit'] == "50")
                                $sLimit50 = "selected";

                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Perlihatkan:") . '&nbsp;
				<select class="SmallText" name="Number" onchange="this.form.submit()">
                                <option value="5" '.$sLimit5.'>5</option>
                                <option value="10" '.$sLimit10.'>10</option>
                                <option value="20" '.$sLimit20.'>20</option>
                                <option value="25" '.$sLimit25.'>25</option>
                                <option value="50" '.$sLimit50.'>50</option>
				</select>&nbsp;
                        </form>
                        </div>
                        <BR>';
                 } ?>

<?php

// At this point we have finished the forms at the top of SelectList.
// Now begin the table displaying results.

// Read if sort by person is selected columns 3 and 5 are user selectable.  If the
// user has not selected a value then read from session variable.
if (!isset($sPersonColumn3)) {
	switch ($_SESSION['sPersonColumn3'])
	{
	case ("Peran Keluarga"):
		$sPersonColumn3 = "Peran Keluarga";
		break;
	case ("Jenis Kelamin"):
		$sPersonColumn3 = "Jenis Kelamin";
		break;
	default:
		$sPersonColumn3 = "Klasifikasi";
		break;
	}
}

if (!isset($sPersonColumn5)) {
	switch ($_SESSION['sPersonColumn5'])
	{
	case ("Telp.Rumah"):
		$sPersonColumn5 = "Telp.Rumah";
		break;
	case ("Kelompok"):
		$sPersonColumn5 = "Kelompok";
		break;
    case ("Handphone"):
        $sPersonColumn5 = "Handphone";
        break;
	default:
		$sPersonColumn5 = "KodePos";
	break;
	}
}

// Header Row for results table
echo '<form method="get" action="SelectList.php" name="ColumnOptions">';
echo '<table cellpadding="2" align="center" cellspacing="0" width="100%">';
echo '<tr class="TableHeader">';

if ($_SESSION['bEditRecords'])
    echo '<td width="25">' . gettext("Edit") . '</td>';

echo '<td><a href="SelectList.php?mode=' .$sMode. '&amp;type=' .$iGroupTypeMissing;
echo '&amp;Sort=name&amp;Filter=' .$sFilter. '">' . gettext("Nama") . '</a></td>';

echo '<td><input type="hidden" name="mode" value="' .$sMode. '">';
if($iGroupTypeMissing > 0)
	echo '<input type="hidden" name="type" value="' .$iGroupTypeMissing. '">';
if(isset($sFilter))
	echo '<input type="hidden" name="Filter" value="' .$sFilter. '">';
if(isset($sSort))
	echo '<input type="hidden" name="Sort" value="' .$sSort. '">';
if(isset($sLetter))
	echo '<input type="hidden" name="Letter" value="' .$sLetter. '">';
if(isset($iClassification))
	echo '<input type="hidden" name="Classification" value="' .$iClassification. '">';
if(isset($iPersonGolDarah))
	echo '<input type="hidden" name="PersonGolDarah" value="' .$iPersonGolDarah. '">';
if(isset($iPersonPendidikan))
	echo '<input type="hidden" name="PersonPendidikan" value="' .$iPersonPendidikan. '">';
if(isset($iPersonPekerjaan))
	echo '<input type="hidden" name="PersonPekerjaan" value="' .$iPersonPekerjaan. '">';
if(isset($iPersonJabatan))
	echo '<input type="hidden" name="PersonJabatan" value="' .$iPersonJabatan. '">';
if(isset($iPersonProfesi))
	echo '<input type="hidden" name="PersonProfesi" value="' .$iPersonProfesi. '">';
if(isset($iPersonHobi))
	echo '<input type="hidden" name="PersonHobi" value="' .$iPersonHobi. '">';
if(isset($iPersonMinat))
	echo '<input type="hidden" name="PersonMinat" value="' .$iPersonMinat. '">';
if(isset($iPersonMinatPly))
	echo '<input type="hidden" name="PersonMinatPly" value="' .$iPersonMinatPly. '">';
if(isset($iPersonStatusKawin))
	echo '<input type="hidden" name="PersonStatusKawin" value="' .$iPersonStatusKawin. '">';
if(isset($iFamilyRole))
	echo '<input type="hidden" name="FamilyRole" value="' .$iFamilyRole. '">';
if(isset($iGender))
	echo '<input type="hidden" name="Gender" value="' .$iGender. '">';
if(isset($iPersonProperty)) {
	echo '<input type="hidden" name="PersonProperties" value="';
	echo $iPersonProperty . '">'; }
if(isset($iGroupType))
	echo '<input type="hidden" name="grouptype" value="' .$iGroupType. '">';
if(isset($iGroupID))
	echo '<input type="hidden" name="groupid" value="' .$iGroupID. '">';

echo '<select class="SmallText" name="PersonColumn3" onchange="this.form.submit()">';

$aPersonCol3 = array("Klasifikasi","Peran Keluarga","Jenis Kelamin");
foreach($aPersonCol3 as $s)
{
	$sel = "";
	if($sPersonColumn3 == $s)
		$sel = " selected";
	echo '<option value="'.$s.'"'.$sel.'>'.gettext($s).'</option>';
}

echo '</select></td>';

echo '<td><a href="SelectList.php?mode=' .$sMode. '&amp;type=' .$iGroupTypeMissing;
echo '&amp;Sort=family&amp;Filter=' .$sFilter. '">' . gettext("Keluarga") . '</a></td>';

echo '<td>';
echo '<select class="SmallText" name="PersonColumn5" onchange="this.form.submit()">';
$aPersonCol5 = array("Telp.Rumah","Kelompok","Handphone","KodePos");
foreach($aPersonCol5 as $s)
{
	$sel = "";
	if($sPersonColumn5 == $s)
		$sel = " selected";
	echo '<option value="'.$s.'"'.$sel.'>'.gettext($s).'</option>';
}
echo "</select></td>";


/** echo "<td>" . gettext("Keranjang") . "</td>"; **/

if ($iMode == 1)
{
	echo "<td>" . gettext("vCard") . "</td>";
	echo "<td>" . gettext("Cetak Data") . "</td>";
} else
{
	echo "<td>" . gettext("Assign") . "</td>";
}

// Table for results begins here
echo '</tr><tr><td>&nbsp;</td></tr>';

$sRowClass = "RowColorA";

$iPrevFamily = -1;

//Loop through the person recordset
while ($aRow = mysql_fetch_array($rsPersons))
{
	$per_Title = "";
	$per_FirstName = "";
	$per_MiddleName = "";
	$per_LastName = "";
	$per_Suffix = "";
	$per_Gender = "";

	$fam_Name = "";
	$fam_Address1 = "";
	$fam_Address2 = "";
	$fam_City = "";
	$fam_State = "";

	extract($aRow);

	// Add alphabetical headers based on sort
	$sBlankLine = "<tr><td>&nbsp;</td></tr>";
	switch($sSort)
	{
	case "family":
		if ($fam_ID != $iPrevFamily || $iPrevFamily == -1)
		{
			echo $sBlankLine;
			echo "<tr><td></td><td class=\"ControlBreak\">";

			if (strlen($fam_Name) > 0)
				echo $fam_Name;
			else
				echo " - ";

			echo "</td></tr>";
			$sRowClass = "RowColorA";
		}
		break;

	case "name":
	if (mb_strtoupper(mb_substr($per_FirstName,0,1,"UTF-8")) != $sPrevLetter)
//	if (mb_strtoupper(mb_substr($per_LastName,0,1,"UTF-8")) != $sPrevLetter)
		{
			echo $sBlankLine;
			echo "<tr><td></td>";
//			echo "<td class=\"ControlBreak\">" . mb_strtoupper(mb_substr($per_LastName,0,1,"UTF-8"));
		echo "<td class=\"ControlBreak\">" . mb_strtoupper(mb_substr($per_FirstName,0,1,"UTF-8"));

			echo "</td></tr>";
			$sRowClass = "RowColorA";
		}
		break;

	default:
		break;
	} // end switch

	//Alternate the row color
	$sRowClass = AlternateRowStyle($sRowClass);

	//Display the row
    echo "<tr class=\"" .$sRowClass. "\">";
	if ($_SESSION['bEditRecords'])
	{
		echo "<td><a href=\"PersonEditor.php?GID=$refresh&PersonID=" .$per_ID. "\">";
		echo gettext(Edit) . "</a></td>";
	}

	echo "<td><a href=\"PersonView.php?PersonID=" .$per_ID. "&GID=$refresh&\">";
	//echo FormatFullName($per_Title, $per_FirstName, $per_MiddleName,
	//					$per_LastName, $per_Suffix, 3);
	echo FormatFullName($per_Title, $per_FirstName, $per_MiddleName,
						$per_LastName, $per_Suffix, 9);
	echo "</a>&nbsp;</td>";

	echo "<td>";
	if ($sPersonColumn3 == "Klasifikasi")
 		echo $aClassificationName[$per_cls_ID];

	elseif ($sPersonColumn3 == "Peran Keluarga")
		echo $aFamilyRoleName[$per_fmr_ID];
	else
	{	// Display Gender
		switch ($per_Gender)
		{
			case 1: echo gettext("Laki-laki"); break;
			case 2: echo gettext("Perempuan"); break;
			default: echo "";
		}
	}
	echo "&nbsp;</td>";

	echo "<td>";
	if ($fam_Name != "")
	{   
		echo "<a href=\"FamilyView.php?FamilyID=" . $fam_ID . "&GID=$refresh&\">" . $fam_Name;
		echo FormatAddressLine($fam_Address1, $fam_City, $fam_State) . "</a>";
	}
	echo "&nbsp;</td>";

	echo "<td>";
    // Phone number or zip code
	if ($sPersonColumn5 == "Telp.Rumah")
    {
        echo SelectWhichInfo(ExpandPhoneNumber($fam_HomePhone,$fam_Country,$dummy),
                ExpandPhoneNumber($per_HomePhone,$fam_Country,$dummy), True);
    }
	elseif ($sPersonColumn5 == "Kelompok")
    {
        echo SelectWhichInfo(ExpandPhoneNumber($per_WorkPhone,$fam_Country,$dummy),
                ExpandPhoneNumber($fam_WorkPhone,$fam_Country,$dummy), True);
    }
    elseif ($sPersonColumn5 == "Handphone")
    {
        echo SelectWhichInfo(ExpandPhoneNumber($per_CellPhone,$fam_Country,$dummy),
                ExpandPhoneNumber($fam_CellPhone,$fam_Country,$dummy), True);
    }
	else
    {
        if (strlen($per_WorkPhone))
            echo $per_WorkPhone;
        else
            echo gettext(" - ");
    }
	echo "</td>";


//
//	echo "<td>";
//	if (!isset($_SESSION['aPeopleCart']) || !in_array($per_ID, $_SESSION['aPeopleCart'], false))
//	{
//
//		// Add to cart option
//		if (mb_substr($sRedirect, -1, 1) == '?')
//			echo "<a onclick=\"saveScrollCoordinates()\"
//					href=\"" .$sRedirect. "AddToPeopleCart=" .$per_ID. "\">";
//		elseif (mb_substr($sRedirect, -1, 1) == '&')
//			echo "<a onclick=\"saveScrollCoordinates()\"
//					href=\"" .$sRedirect. "AddToPeopleCart=" .$per_ID. "\">";
//		else
//			echo "<a onclick=\"saveScrollCoordinates()\"
//					href=\"" .$sRedirect. "&amp;AddToPeopleCart=" .$per_ID. "\">";
//
//		echo gettext("Tambahkan ke keranjang") . "</a>";
//	} else
//	{
//		// Remove from cart option
//		if (mb_substr($sRedirect, -1, 1) == '?')
//			echo "<a onclick=\"saveScrollCoordinates()\"
//					href=\"" .$sRedirect. "RemoveFromPeopleCart=" .$per_ID. "\">";
//		elseif (mb_substr($sRedirect, -1, 1) == '&')
//			echo "<a onclick=\"saveScrollCoordinates()\"
//					href=\"" .$sRedirect. "RemoveFromPeopleCart=" .$per_ID. "\">";
//		else
//			echo "<a onclick=\"saveScrollCoordinates()\"
//					href=\"" .$sRedirect. "&amp;RemoveFromPeopleCart=" .$per_ID. "\">";
//
//		echo gettext("Hilangkan") . "</a>";
//
//	}
//


	if ($iMode == 1)
	{
		echo "<td><a href=\"VCardCreate.php?PersonID=" .$per_ID. "\">";
		echo gettext("vCard") . "</a></td>";
		echo "<td><a href=\"PrintView.php?PersonID=" .$per_ID. "&GID=$refresh&\" target=\"_blank\">";
		echo gettext("Cetak") . "</a></td>";
	} else
	{
		echo "<td><a href=\"PersonToGroup.php?PersonID=" .$per_ID;
		echo "&amp;prevquery=" . rawurlencode($_SERVER["QUERY_STRING"]) . "\">";
		echo gettext("Tambahkan ke Grup") . "</a></td>";
	}

	echo "</tr>";

	//Store the family to enable the control break
	$iPrevFamily = $fam_ID;

	//If there was no family, set it to 0
	if (strlen($iPrevFamily) < 1)
		$iPrevFamily = 0;

	//Store the first letter of this record to enable the control break
//code asli	$sPrevLetter = mb_strtoupper(mb_substr($per_LastName,0,1,"UTF-8"));
	$sPrevLetter = mb_strtoupper(mb_substr($per_FirstName,0,1,"UTF-8"));

} // end of while loop

//Close the table
echo "</table></form>\n";

require "Include/Footer.php";
exit;

}

else // if (!($iMode == 1 || $iMode == 2))
{

require "$sHeaderFile";


echo "Filter Data : <br>";
//Kewargaan 			$aClassificationName
if ( $iClassification > 0 ) {
		$tClassification = 'Kewargaan : ' . $aClassificationName[$iClassification] . ' | ';
		echo $tClassification ;
	} elseif ( $iClassification < 0 ) {
		$iClassification = $iClassification + 10000;
		$tClassification = 'Kewargaan : Bukan ' . $aClassificationName[$iClassification] . ' | ';
		echo $tClassification ;
	} else {
		echo " ";
	}

//Status Kawin   		$aPersonStatusKawinName
if ( $iPersonStatusKawin > 0 ) {
		$tPersonStatusKawin = 'Status Kawin : ' . $aPersonStatusKawinName[$iPersonStatusKawin] . ' | ';
		echo $tPersonStatusKawin ;
	} elseif ( $iPersonStatusKawin < 0 ) {
		$iPersonStatusKawin = $iPersonStatusKawin + 10000;
		$tPersonStatusKawin = 'Status Kawin : Bukan ' . $aPersonStatusKawinName[$iPersonStatusKawin] . ' | ';
		echo $tPersonStatusKawin ;
	} else {
		echo " ";
	}

//Gol Darah			$aPersonGolDarahName
if ( $iPersonGolDarah > 0 ) {
		$tPersonGolDarah = 'Gol Darah : ' . $aPersonGolDarahName[$iPersonGolDarah] . ' | ';
		echo $tPersonGolDarah ;
	} elseif ( $iPersonGolDarah < 0 ) {
		$iPersonGolDarah = $iPersonGolDarah + 10000;
		$tPersonGolDarah = 'Gol Darah : Bukan ' . $aPersonGolDarahName[$iPersonGolDarah] . ' | ';
		echo $tPersonGolDarah ;
	} else {
		echo " ";
	}
//Pendidikan 			$aPersonPendidikanName
if ( $iPersonPendidikan > 0 ) {
		$tPersonPendidikan = 'Pendidikan : ' . $aPersonPendidikanName[$iPersonPendidikan] . ' | ';
		echo $tPersonPendidikan ;
	} elseif ( $iPersonPendidikan < 0 ) {
		$iPersonPendidikan = $iPersonPendidikan + 10000;
		$tPersonPendidikan = 'Pendidikan : Bukan ' . $aPersonPendidikanName[$iPersonPendidikan] . ' | ';
		echo $tPersonPendidikan ;
	} else {
		echo " ";
	}

//Pekerjaan 			$aPersonPekerjaanName
if ( $iPersonPekerjaan > 0 ) {
		$tPersonPekerjaan = 'Pekerjaan : ' . $aPersonPekerjaanName[$iPersonPekerjaan] . ' | ';
		echo $tPersonPekerjaan ;
	} elseif ( $iPersonPekerjaan < 0 ) {
		$iPersonPekerjaan = $iPersonPekerjaan + 10000;
		$tPersonPekerjaan = 'Pekerjaan : Bukan ' . $aPersonPekerjaanName[$iPersonPekerjaan] . ' | ';
		echo $tPersonPekerjaan ;
	} else {
		echo " ";
	}

//Jabatan   			$aPersonJabatanName
if ( $iPersonJabatan > 0 ) {
		$tPersonJabatan = 'Jabatan : ' . $aPersonJabatanName[$iPersonJabatan] . ' | ';
		echo $tPersonJabatan ;
	} elseif ( $iPersonJabatan < 0 ) {
		$iPersonJabatan = $iPersonJabatan + 10000;
		$tPersonJabatan = 'Jabatan : Bukan ' . $aPersonJabatanName[$iPersonJabatan] . ' | ';
		echo $tPersonJabatan ;
	} else {
		echo " ";
	}
//Profesi  			$aPersonProfesiName
if ( $iPersonProfesi > 0 ) {
		$tPersonProfesi = 'Profesi : ' . $aPersonProfesiName[$iPersonProfesi] . ' | ';
		echo $tPersonProfesi ;
	} elseif ( $iPersonProfesi < 0 ) {
		$iPersonProfesi = $iPersonProfesi + 10000;
		$tPersonProfesi = 'Profesi : Bukan ' . $aPersonProfesiName[$iPersonProfesi] . ' | ';
		echo $tPersonProfesi ;
	} else {
		echo " ";
	}

//Hobi 				$aPersonHobiName
if ( $iPersonHobi > 0 ) {
		$tPersonHobi = 'Hobi : ' . $aPersonHobiName[$iPersonHobi] . ' | ';
		echo $tPersonHobi ;
	} elseif ( $iPersonHobi < 0 ) {
		$iPersonHobi = $iPersonHobi + 10000;
		$tPersonHobi = 'Hobi : Bukan ' . $aPersonHobiName[$iPersonHobi] . ' | ';
		echo $tPersonHobi ;
	} else {
		echo " ";
	}

//Minat 			$aPersonMinatName
if ( $iPersonMinat > 0 ) {
		$tPersonMinat = 'Minat : ' . $aPersonMinatName[$iPersonMinat] . ' | ';
		echo $tPersonMinat ;
	} elseif ( $iPersonMinat < 0 ) {
		$iPersonMinat = $iPersonMinat + 10000;
		$tPersonMinat = 'Minat : Bukan ' . $aPersonMinatName[$iPersonMinat] . ' | ';
		echo $tPersonMinat ;
	} else {
		echo " ";
	}


//Minat Pelayanan  	$aPersonMinatPlyName
if ( $iPersonMinatPly > 0 ) {
		$tPersonMinatPly = 'Minat Pelayanan : ' . $aPersonMinatPlyName[$iPersonMinatPly] . ' | ';
		echo $tPersonMinatPly ;
	} elseif ( $iPersonMinatPly < 0 ) {
		$iPersonMinatPly = $iPersonMinatPly + 10000;
		$tPersonMinatPly = 'Minat Pelayanan : Bukan ' . $aPersonMinatPlyName[$iPersonMinatPly] . ' | ';
		echo $tPersonMinatPly ;
	} else {
		echo " ";
	}

//Peran Keluarga   	$aFamilyRoleName
if ( $iFamilyRole > 0 ) {
		$tFamilyRole = 'Status Keluarga : ' . $aFamilyRoleName[$iFamilyRole] . ' | ';
		echo $tFamilyRole ;
	} elseif ( $iFamilyRole < 0 ) {
		$iFamilyRole = $iFamilyRole + 10000;
		$tFamilyRole = 'Status Keluarga : Bukan ' . $aFamilyRoleName[$iFamilyRole] . ' | ';
		echo $tFamilyRole ;
	} else {
		echo " ";
	}

//Gender
if ($iGender == 1) {
		$tGender = 'Jenis Kelamin : LakiLaki';
		echo $tGender;
	} elseif ($iGender == 2) {
		$tGender = 'Jenis Kelamin : Perempuan';
		echo $tGender;
	} else {
		echo " ";
	}


//Prepare data for POST to PDF
$finalSQL = $sSQL . $sOrderSQL;
list($sqlpdf1, $sqlpdf2) = split ('[*]' , $finalSQL );

$PostSqlPdf1 = "SELECT per_FirstName as Nama,per_HomePhone as TelpRumah,per_CellPhone as Handphone,fam_Address1 as Alamat, per_WorkPhone as Kelompok ";
$PostSqlPdf2 = $sqlpdf2 ;
$PostSqlPdf3 = "$tGender $tClassification $tFamilyRole $tPersonStatusKawin $tPersonGolDarah $tPersonPendidikan
				$tPersonPekerjaan $tPersonJabatan $tPersonProfesi $tPersonHobi $tPersonMinat $tPersonMinatPly";



echo "<form action=\"PrintViewCustomQueryPdf.php\" method=\"post\">" ;
echo "<input type=\"hidden\" name=\"sqlpdf\"  value=\" $PostSqlPdf1 $PostSqlPdf2  \"  size=\"1\" >" ;
echo "<input type=\"hidden\" name=\"sqlpdf3\"  value=\" $PostSqlPdf3   \"  size=\"1\" >" ;
echo "<input type=\"submit\" value=\"pdf\" >" ;
echo "</form>" ;


?>

<table cellpadding="2" align="center" cellspacing="0" width="100%">

                        <tr class="TableHeader">
                                <td><?php echo gettext("Nama"); ?></td>
                                <td><?php echo gettext("Alamat"); ?><br><?php echo gettext("Kota, Daerah Kode Pos"); ?></td>
                                <td><?php echo gettext("Telp.Rumah") . " /"; ?>
                                <br><?php echo gettext("Kelompok") . " /"; ?>
                                <br><?php echo gettext("Handphone"); ?></td>

                                <td><?php echo gettext("Di-input"); ?></td>
                        </tr>
                <?php

                $sRowClass = "RowColorA";

                $iPrevFamily = -1;

                $rsPersons = RunQuery($finalSQL);

                //Loop through the person recordset
                while ($aRow = mysql_fetch_array($rsPersons))
                {
                        $per_Title = "";
                        $per_FirstName = "";
                        $per_MiddleName = "";
                        $per_LastName = "";
                        $per_Suffix = "";
                        $per_Address1 = "";
                        $per_Address2 = "";
                        $per_City = "";
                        $per_State = "";
                        $per_Zip = "";
                        $per_Country = "";
                        $per_HomePhone = "";
                        $per_WorkPhone = "";
                        $per_CellPhone = "";
                        $per_Email = "";

                        $per_DateEntered = "";
                        $fam_Name = "";
                        $fam_Address1 = "";
                        $fam_Address2 = "";
                        $fam_City = "";
                        $fam_State = "";
                        $fam_Zip = "";
                        $fam_Country = "";
                        $fam_HomePhone = "";
                        $fam_CellPhone = "";
                        $fam_Email = "";

                        extract($aRow);

                        //Alternate the row color
                        $sRowClass = AlternateRowStyle($sRowClass);

                        // Assign the values locally, after selecting whether to display the family or person information
                        SelectWhichAddress($sAddress1, $sAddress2, $per_Address1, $per_Address2, $fam_Address1, $fam_Address2, False);
                        $sCity = SelectWhichInfo($per_City, $fam_City, False);
                        $sState = SelectWhichInfo($per_State, $fam_State, False);
                        $sZip = SelectWhichInfo($per_Zip, $fam_Zip, False);
                        $sCountry = SelectWhichInfo($per_Country, $fam_Country, False);
                        $sHomePhone = SelectWhichInfo(ExpandPhoneNumber($per_HomePhone,$sCountry,$dummy), ExpandPhoneNumber($fam_HomePhone,$fam_Country,$dummy), False);
                        $sWorkPhone = SelectWhichInfo(ExpandPhoneNumber($per_WorkPhone,$sCountry,$dummy), ExpandPhoneNumber($fam_WorkPhone,$fam_Country,$dummy), False);
                        $sCellPhone = SelectWhichInfo(ExpandPhoneNumber($per_CellPhone,$sCountry,$dummy), ExpandPhoneNumber($fam_CellPhone,$fam_Country,$dummy), False);
                        $sUnformattedEmail = SelectWhichInfo($per_Email, $fam_Email, False);

                        //Display the row
                        ?>

                        <tr class="<?php echo $sRowClass; ?>">
                                <td><?php echo FormatFullName($per_Title, $per_FirstName, $per_MiddleName, $per_LastName, $per_Suffix, 9); ?>&nbsp;</td>
                         <?php //       <td><?php echo FormatFullName($per_Title, $per_FirstName, $per_MiddleName, $per_LastName, $per_Suffix, 0); ?>&nbsp;</td>

                                <td><?php echo $sAddress1;?>&nbsp;<?php if ($sAddress1 != "" && $sAddress2 != "") { echo ", "; } ?><?php if ($sAddress2 != "") echo $sAddress2; ?>
                                <?php if ($sCity || $sState || $sZip)
                                        echo "" . $sCity . ", " . $sState . " " . $sZip; ?></td>
                                <td><?php echo $sHomePhone ?>&nbsp;
                                <?php if($sWorkPhone) echo "<br>" . $sWorkPhone; ?>
                                <?php if($sCellPhone) echo "<br>" . $sCellPhone; ?></td>


                                <td><?php echo $per_DateEntered ?>&nbsp;</td>
                        </tr>

                        <?php

                        //Store the family to enable the control break
                        $iPrevFamily = $fam_ID;

                        //If there was no family, set it to 0
                        if (strlen($iPrevFamily) < 1)
                        {

                                $iPrevFamily = 0;

                        }

                        //Store the first letter of this record to enable the control break
                        $sPrevLetter = mb_strtoupper(mb_substr($per_FirstName,0,1,"UTF-8"));
                 //code asli $sPrevLetter = mb_strtoupper(mb_substr($per_LastName,0,1,"UTF-8"));

                }
                //Close the table
                echo "</table>\n";
    //            echo $sSQL;
    //                   echo "<br>";
    //            echo $sqlpdf1;
    //                   echo "<br>";
    //            echo $sqlpdf2;
    //                   echo "<br>";
    //            echo $finalSQL;
                require "Include/Footer-Short.php";
        }
}
elseif ($iMode == 4)
{
//echo "listing mail";
/**********************
**  Mail Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT * FROM SuratMasuk";

     //   if (isset($sLetter))
      //          $sSQL .= " WHERE Dari LIKE '" . $sLetter . "%'";
     //   elseif (isset($sFilter))
     //   {
    //            $sSQL .= " WHERE Dari LIKE '%" . $sFilter . "%'";
    //    }

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Dari":
                        $sSQL = $sSQL . " ORDER BY Dari DESC";
                        break;
                case "Institusi":
                        $sSQL = $sSQL . " ORDER BY Institusi, Dari";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY MailID Desc";
                        break;
        }

        $rsMailCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsMailCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsMail = RunQuery($sSQL);
		
		
        // Run query to get first letters of name.
    //    $sSQL = "SELECT DISTINCT LEFT(Institusi,1) AS letter FROM SuratMasuk ORDER BY Institusi";
    //    $rsLetters = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"MailEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Surat Masuk Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectList.php" name="Mail">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Dari"><?php echo gettext("Pengirim"); ?></option>
                        <option value="Institusi"><?php echo gettext("Institusi"); ?></option>
						<option value="Hal"><?php echo gettext("Hal"); ?></option>
						<option value="TanggalSurat"><?php echo gettext("Tanggal Surat"); ?></option>
						<option value="Ket1"><?php echo gettext("Tanggal Terima"); ?></option>
						<option value="FollowUp"><?php echo gettext("FollowUp"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="mail">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        // Create Sort Links
     //   echo "<div align=\"center\">";
     //   echo "<a href=\"SelectList.php?mode=mail\">" . gettext("Lihat Semua") . "</a>";
     //   while ($aLetter = mysql_fetch_array($rsLetters))
     //   {
     //           echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"SelectList.php?mode=mail";
     //           if($sSort) echo "&amp;Sort=$sSort";
     //           echo "&amp;Letter=" . $aLetter[0] . "\">" . $aLetter[0] . "</a>";
     //   }

        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=mail&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="mail">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';
        //        if(isset($sLetter))
         //               echo '<input type="hidden" name="Letter" value="' . $sLetter . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td><?php echo gettext("MailID"); ?></td>
				<td><?php echo gettext("Tanggal Surat"); ?></td>
				<td><?php echo gettext("Tanggal Terima"); ?></td>
				<td><?php echo gettext("NomorSurat"); ?></td>
                <td><?php echo gettext("Pengirim"); ?></td>
                <td><?php echo gettext("Institusi"); ?></td>
                <td><?php echo gettext("Tujuan"); ?></td>
                <td><?php echo gettext("Hal"); ?></td>
                <td><?php echo gettext("Urgensi"); ?></td>
				<td><?php echo gettext("FollowUp"); ?></td>
				<td><?php echo gettext("Kategori"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
        </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the Mail recordset
        while ($aRow = mysql_fetch_array($rsMail))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
                $Tanggal = "";
                $Ket1 = "";
                $NomorSurat = "";
                $Dari = "";
                $Institusi = "";
                $Kepada = "";
                $Hal = "";
                $Urgensi = "";
				$FollowUp = "";
				$Ket3 = "";

				
                extract($aRow);

                //Does this mail name start with a new letter?
        //        if (mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8")) != $sPrevLetter)
         //       {
                        //Display the header
         //               echo $sBlankLine;
         //               echo "<tr><td class=\"ControlBreak\" colspan=\"2\"><b>" . mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8")) . "</b></td></tr>";
		//				$sBlankLine = "<tr><td>&nbsp;</td></tr>";
         //               $sRowClass = "RowColorA";
          //      }

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="MailEditor.php?MailID=<?php echo $MailID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="MailView.php?MailID=<?php echo $MailID ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><?php echo $Tanggal ?>&nbsp;</td>
						<td><?php echo $Ket1 ?>&nbsp;</td>
						
						<td><?php 
						    if ($Ket3 == '31' ){
								// Link to Person Info to track previous Church
								$ssSQL = "SELECT * FROM `person_per`
										WHERE `per_LastName` LIKE  '" . $NomorSurat . "' 
										ORDER BY per_fmr_ID LIMIT 1 " ;				
								$perintah = mysql_query($ssSQL);
								if (mysql_num_rows($perintah) )
									{
										while ($hasilGD=mysql_fetch_array($perintah))
											{
												extract($hasilGD);
												echo "<a href=\"PersonView.php?PersonID=" . $hasilGD[per_ID] . "&GID=$refresh&\">" . $NomorSurat . "</a>"  ;								
											}
									}		
									else
									{
									echo "<a><img border=0 src=\"Images/exclaim.gif\"  width=15 height=15  > </a> ";
									echo "<font color=red >";	echo $NomorSurat ;echo "</font>";
									}
								}	
								else
								{ 
								
								echo $NomorSurat ; 
								}

						?>&nbsp;</td>
						
						<td><?php echo $Dari ?>&nbsp;</td>
						<td><?php echo $Institusi ?>&nbsp;</td>
						<td><?php echo $Kepada ?>&nbsp;</td>
						<td><?php echo $Hal ?>&nbsp;</td>
						<td><?php 
							switch ($Urgensi)
							{
							case 1:
								echo gettext("Sangat Segera");
								break;
							case 2:
								echo gettext("<b>Segera</b>");
								break;
							case 3:
								echo gettext("Biasa");
								break;
							}			
						?>&nbsp;</td>
						<td><?php echo $FollowUp ?>&nbsp;</td>
						<td><?php 
							switch ($Ket3)
							{
						    case 11:
									echo gettext("Informasi Umum");
									break;
							case 12:
									echo gettext("Surat Edaran");
									break;
							case 13:
									echo gettext("Undangan");
									break;
							case 14:
									echo gettext("Laporan Kegiatan");
									break;
			
							case 21:
									echo gettext("Permohonan Umum");
									break;
							case 22:
									echo gettext("Permohonan Bantuan");
									break;
							case 23:
									echo gettext("Permohonan Pelayanan Firman");
									break;
							case 24:
									echo gettext("Permohonan Peminjaman Asset Gereja");
									break;
							case 25:
									echo gettext("Permohonan Pelayanan Gerejawi (Baptis/Sidi/Nikah/dll)");
									break;

							case 31:
									echo gettext("Surat Pindah/Atestasi");
									break;
							case 32:
									echo gettext("Surat Pemberitahuan Sakramen");
									break;
							case 33:
									echo gettext("Surat Penitipan Rohani");
									break;
							}			
						?>&nbsp;</td>
						

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;



}
elseif ($iMode == 5)
{
// echo "listing pak";
/**********************
**  PAK Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT *, (Nilai1+Nilai2+Nilai3) as NilaiTotal FROM pakgkjbekti a
		LEFT JOIN person_per b ON a.per_ID =  b.per_ID  
		LEFT JOIN paktutor d ON a.TutorID=d.TutorID ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Kelas":
                        $sSQL = $sSQL . " ORDER BY Kelas DESC";
                        break;
                case "Semester":
                        $sSQL = $sSQL . " ORDER BY Semester, Kelas DESC";
                        break;
				case "TanggalInput":
                        $sSQL = $sSQL . " ORDER BY DateEntered DESC";
                        break;		
				case "TanggalEdit":
                        $sSQL = $sSQL . " ORDER BY DateLastEdited DESC";
                        break;			
				case "Tutor":
                        $sSQL = $sSQL . " ORDER BY TutorID DESC";
                        break;	
				case "Nilai":
                        $sSQL = $sSQL . " ORDER BY NilaiTotal DESC";
                        break;							
                default:
                        $sSQL = $sSQL . " ORDER BY TahunAjaran Desc,Semester Desc , Kelas Desc";
                        break;
        }

        $rsPakCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPakCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PakEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data PAK") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectList.php" name="Pak">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Kelas"><?php echo gettext("Kelas"); ?></option>
                        <option value="Semester"><?php echo gettext("Semester"); ?></option>
						<option value="TahunAjaran"><?php echo gettext("TahunAjaran"); ?></option>
						<option value="TanggalInput"><?php echo gettext("TanggalInput"); ?></option>
						<option value="TanggalEdit"><?php echo gettext("TanggalEdit"); ?></option>
						<option value="Tutor"><?php echo gettext("Tutor"); ?></option>
						<option value="Nilai"><?php echo gettext("Nilai"); ?></option>						
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="pak">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="pak">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
		<br>
		Catatan : Bobot Nilai : Nilai A	= nilai kerajinan , bobot = 30% | 	
		Nilai B	= nilai tugas , bobot = 30% |	
		Nilai C	= nilai test , bobot = 40%	

        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td><?php echo gettext("PakID"); ?></td>
				<td><?php echo gettext("Cetak"); ?></td>
			
				<td><?php echo gettext("Nama"); ?></td>
				<td><?php echo gettext("AlamatSekolah"); ?></td>
                <td align="center"><?php echo gettext("TahunAjaran"); ?></td>
				<td align="center"><?php echo gettext("Semester"); ?></td>
                <td align="center"><?php echo gettext("Kelas"); ?></td>
                <td><?php echo gettext("TutorID"); ?></td>
				<td><?php echo gettext("TutorName"); ?></td>
                <td align="center"><?php echo gettext("Nilai1<br>(A)"); ?></td>
				<td align="center"><?php echo gettext("Nilai2<br>(B)"); ?></td>
				<td align="center"><?php echo gettext("Nilai3<br>(C)"); ?></td>
				<td align="center"><?php echo gettext("Nilai<br>Final"); ?></td>
				
				<td align="center"><?php echo gettext("Ket"); ?></td>
				<td align="center"><?php echo gettext("Tgl<br>Input"); ?></td>
				<td align="center"><?php echo gettext("Tgl<br>Edit"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$PakID = "";
				$per_ID = "";
				$Nama = "";
				$AlamatSekolah = "";
                $Kelas = "";
                $Semester = "";
                $TahunAjaran = "";
                $TutorID = "";
				$TutorName = "";
                $Nilai1 = "";
				$Nilai2 = "";
				$Nilai3 = "";
				$Nilai4 = "";
				$Keterangan = "";
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="PakEditor.php?PakID=<?php echo $PakID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="PakView.php?PakID=<?php echo $PakID ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td>
						<a target="_blank" href="PrintViewPak.php?PakID=<?php echo $PakID."&GID=$refresh"; ?>"><?php echo "CetakNilai" ?></a><br>|
						<a target="_blank" href="PrintViewPak.php?PakID=<?php echo $PakID."&GID=$refresh"; ?>&TTD=1"><?php echo "P" ?></a>|
						<a target="_blank" href="PrintViewPak.php?PakID=<?php echo $PakID."&GID=$refresh"; ?>&TTD=61"><?php echo "K" ?></a>|
						<a target="_blank" href="PrintViewPak.php?PakID=<?php echo $PakID."&GID=$refresh"; ?>&TTD=65"><?php echo "S" ?></a>|
						</td>

						<td><a href="PersonView.php?PersonID=<?php echo $per_ID."&GID=$refresh"; ?>"><?php echo $per_FirstName ?></a>
						<?php if ($per_ID == 0){echo $Nama;} ?>&nbsp;</td>
						<td><?php echo $AlamatSekolah ?>&nbsp;</td>
						<td><?php echo "20" . $TahunAjaran . "/20" . ($TahunAjaran+1) . "" ?>&nbsp;</td>
						<td align="center"><?php echo $Semester ?>&nbsp;</td>
						<td align="center"><?php echo $Kelas ?>&nbsp;</td>
						<td><?php echo $TutorID ?>&nbsp;</td>
						<td><?php echo $TutorName ?>&nbsp;</td>
						<td align="center"><?php echo $Nilai1 ?>&nbsp;</td>
						<td align="center"><?php echo $Nilai2 ?>&nbsp;</td>
						<td align="center"><?php echo $Nilai3 ?>&nbsp;</td>

						<?php $NilaiAkhir=($Nilai1*0.3)+($Nilai2*0.3)+($Nilai3*0.4); ?>
						<td align="center" ><b><?php echo $NilaiAkhir; ?>&nbsp;</b></td>	

						<td><?php if ($per_ID == 0){echo "Non Warga";} else {echo $Keterangan;} ?>&nbsp;</td>
						<td align="center"><?php echo date2Ind($DateEntered,3)."<br>oleh: ".$EnteredBy; ?>&nbsp;</td>
						<td align="center"><?php echo date2Ind($DateLastEdited,3)."<br>oleh: ".$EditedBy; ?>&nbsp;</td>

						

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;



}


elseif ($iMode == 6)

{
// echo "listing aset";
/**********************
**  Aset Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT * FROM asetgkjbekti a
		    LEFT JOIN LokasiTI b ON a.Location=b.KodeTI 
			LEFT JOIN asetklasifikasi c ON a.AssetClass=c.ClassID 
			LEFT JOIN asetruangan d ON a.StorageCode=d.StorageCode
			LEFT JOIN asetstatus e ON a.Status=e.StatusCode 
		 ";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Location":
                        $sSQL = $sSQL . " ORDER BY Location, AssetClass DESC";
                        break;
                case "Tahun":
                        $sSQL = $sSQL . " ORDER BY Tahun, AssetClass DESC";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY AssetClass";
                        break;
        }

        $rsAsetCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsAsetCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

	//	echo $sSQL;
        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"AsetEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Aset") . "</a></div>
		<div align=\"center\"><a href=\"PrintViewLapAset.php?GID=$refresh\" target=\"_blank\" >" . gettext("Cetak Laporan Data Aset") . "</a></div>
		<BR>"; }
        ?>
		
        <form method="get" action="SelectList.php" name="aset">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="AssetClass"><?php echo gettext("Klasifikasi"); ?></option>
                        <option value="Tahun"><?php echo gettext("Tahun Pengadaan"); ?></option>
						<option value="Location"><?php echo gettext("Lokasi"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="aset">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=aset&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="aset">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td ALIGN=CENTER  ><?php echo gettext("View"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Cetak"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Klasifikasi"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Tahun"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Merk"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Type"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Spesifikasi"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Jumlah"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Unit"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Nilai"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Status"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Lokasi TI"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Tempat"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Rak"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Bin"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Keterangan"); ?></td> 				
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
            	$AssetID = ""; 
            	$AssetClass = ""; 
            	$Tahun = ""; 
            	$Merk = ""; 
            	$Type = ""; 
            	$Spesification = ""; 
            	$Quantity = ""; 
            	$UnitOfMasure = "";
            	$Value = ""; 
            	$Status = ""; 
            	$Description = ""; 
            	$Location = ""; 
            	$StorageCode = ""; 
            	$Rack = ""; 
            	$Bin = ""; 	

                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="AsetEditor.php?AssetID=<?php echo $AssetID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="AsetView.php?AssetID=<?php echo $AssetID ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><a target="_blank" href="PrintViewAset.php?AssetID=<?php echo $AssetID."&GID=$refresh"; ?>"><?php echo "Cetak" ?></a>&nbsp;</td>


						<td><?php echo $majorclass . "-" . $minorclass ?>&nbsp;</td> 
						<td><?php echo $Tahun ?>&nbsp;</td> 
						<td><?php echo $Merk ?>&nbsp;</td> 
						<td><?php echo $Type ?>&nbsp;</td> 
						<td><?php echo $Spesification ?>&nbsp;</td> 
						<td  ALIGN=CENTER ><?php echo $Quantity ?>&nbsp;</td> 
						<td><?php echo $UnitOfMasure ?>&nbsp;</td> 
						<td><?php echo currency('Rp. ',$Value,'.',',00') ?>&nbsp;</td> 
						<td><?php echo $StatusName ?>&nbsp;</td> 
						<td><?php echo $NamaTI ?>&nbsp;</td> 
						<td><?php echo $StorageDesc ?>&nbsp;</td> 
						<td><?php echo $Rack ?>&nbsp;</td> 
						<td><?php echo $Bin ?>&nbsp;</td> 
						<td><?php echo $Description ?>&nbsp;</td> 
				

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;



}

elseif ($iMode == 7)

{
// echo "listing persembahan";
/**********************
**  Persembahan Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT a.* , b.NamaTI as NamaTI, c.PelayanFirman,  IF(c.PelayanFirman>0, d.NamaPendeta , c.PFnonInstitusi) as Pengkotbah  FROM Persembahangkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI 
			LEFT JOIN JadwalPelayanFirman c ON a.Tanggal = c.TanggalPF 
			LEFT JOIN DaftarPendeta d ON c.PelayanFirman = d.PendetaID 
			WHERE ( a.KodeTI = c.KodeTI AND a.Pukul = c.WaktuPF) 
			";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY KodeTI, Tanggal DESC";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, a.KodeTI, Pukul";
                        break;
        }

        $rsPersembahanCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPersembahanCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

	//	echo $sSQL;
        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PersembahanEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Persembahan") . "</a></div>
		<div align=\"center\"><a href=\"PrintViewLapPersembahan.php?GID=$refresh\" target=\"_blank\" >" . gettext("Cetak Laporan Data Persembahan") . "</a></div>
		<BR>"; }
        ?>
		
        <form method="get" action="SelectList.php" name="Persembahan">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="TempatIbadah"><?php echo gettext("TempatIbadah"); ?></option>
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="Persembahan">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=Persembahan&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="Persembahan">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td ALIGN=CENTER  ><?php echo gettext("View"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Cetak"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Tanggal"); ?></td> 	
				<td ALIGN=CENTER ><?php echo gettext("Minggu Ke-"); ?></td> 				
				<td ALIGN=CENTER ><?php echo gettext("Tempat Ibadah"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Pukul"); ?></td> 	
				<td ALIGN=CENTER ><?php echo gettext("Nas"); ?></td> 					
				<td ALIGN=CENTER ><?php echo gettext("Pengkotbah"); ?></td>	
				<td colspan=2 ALIGN=CENTER ><?php echo gettext("Total Persembahan"); ?></td>
				<td colspan=2 ALIGN=CENTER ><?php echo gettext("Detail/Error"); ?></td>					
				<td ALIGN=CENTER ><?php echo gettext("Total Jemaat"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Total Majelis"); ?></td> 					
			
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the persembahan recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$sPersembahan_ID = "";
				$sTanggal = "";
				$sPukul = "";
				$sKodeTI = "";


                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="PersembahanEditor.php?Persembahan_ID=<?php echo $Persembahan_ID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="PersembahanView.php?Persembahan_ID=<?php echo $Persembahan_ID ?>"><?php echo "View" ?></a>&nbsp;</td>
						<td><a target="_blank" href="PrintViewPersembahan.php?Persembahan_ID=<?php echo $Persembahan_ID."&GID=$refresh"; ?>"><?php echo "Cetak" ?></a>&nbsp;</td>
 
						<td><a href="PrintViewInfoPersembahan.php?TGL=<?php echo $Tanggal."&GID=$refresh"; ?>"  target=\"_blank\"><?php echo $Tanggal; ?></a>&nbsp;</td> 
						<td ALIGN=center><?php echo date("W", strtotime($Tanggal));?>&nbsp;</td> 
						<td><?php echo $NamaTI ?>&nbsp;</td> 
						<td><?php echo $Pukul ?>&nbsp;</td> 
						<td><?php echo $Nas ?>&nbsp;</td>
						<td><?php echo $Pengkotbah ?>&nbsp;</td>
						
						
						
						
						<td><?php echo "Rp."; ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php 
						$TotalPersembahan = $KebDewasa + $KebAnak + $KebAnakJTMY + $KebRemaja + $KebPraRemaja + $KebPemuda + 
						$Syukur + $SyukurBaptis + $Bulanan + $Khusus + $KhususPerjamuan + $Marapas + 
						$Marapen + $Maranatal + $Unduh +$Pink + $LainLain;
							//echo currency(' ',$TotalPersembahan,'.',',00'); 
							
							if (date("W", strtotime(date("Y-m-d"))) > date("W", strtotime($Tanggal)) ){
								if ($TotalPersembahan > 0 ){ echo currency(' ',$TotalPersembahan,'.',',00'); 
								} else if ($KetPersembahan == 1){ echo "<font color=\"red\" >Tanpa Persembahan</font>";
								} else { echo "<font color=\"red\" ><blink> " . currency(' ',$TotalPersembahan,'.',',00') . "</blink></font>";  }
							} else { echo "<font color=\"red\" ><blink> " . currency(' ',$TotalPersembahan,'.',',00') . "</blink></font>";  }
				
				echo "</td><td align=\"center\" >";
				// Check Persembahan Bulanan
				$sSQL = "SELECT SUM(Bulanan) as SubTotalBulanan, SUM(Syukur) as SubTotalSyukur, SUM(ULK) as SubTotalULK  FROM PersembahanBulanan WHERE Tanggal = '".$Tanggal."' AND KodeTI=".$KodeTI." AND Pukul='".$Pukul."'" ;
				//echo $sSQL;
				$rsPersembahanBulanan = RunQuery($sSQL);		
				$num_rows = mysql_num_rows($rsPersembahanBulanan);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanBulanan))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[SubTotalBulanan]+$hasilGD[SubTotalSyukur]+$hasilGD[SubTotalULK] ;
						//echo $lPersembahan;
				}
									
				if ( $num_rows == 0 ) 
					{
						if ( $Bulanan > 0){
						echo "<a href=\"PersembahanBulananEditor.php?GID=$refresh&TGL=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font size=\"1\"  color=\"red\"><p class=\"help\" title=\"Belum Ada Data Pers Bulanan yang Diinput\"><blink>BulananNoData!!</blink></p></font>";
						}
					}
				elseif ( $Bulanan == $lPersembahan )
					{	if ( $Bulanan > 0){
						echo "<a class=\"help\" title=\"Lihat Detail Pers.Bulanan\" href=\"PrintViewPersembahanBulanan.php?GID=$refresh&TGL=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . "&NamaTI=".$NamaTI."  \" TARGET=\"_BLANK\">PB|</a>"; 
					} 
					}
				else
					{
						echo "<a href=\"PrintViewPersembahanBulanan.php?GID=$refresh&TGL=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . "&NamaTI=".$NamaTI."\"  TARGET=\"_BLANK\">

						<font size=\"1\" color=\"red\"> <blink><p class=\"help\" title=\"Bulanan Terinput:".currency(' ',$lPersembahan,'.',',00')." seharusnya : ".currency(' ',$Bulanan,'.',',00')."\">BulananDataErr!!!</p></blink></font>";
					}

				// Cek Persembahan Remaja
				$sSQL2 = "SELECT Persembahan_ID,Persembahan FROM PersembahanRemajagkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul= '" . $Pukul . "'";
				$rsPersembahanRemaja = RunQuery($sSQL2);
				$num_rows = mysql_num_rows($rsPersembahanRemaja);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanRemaja))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				}
									
				if ( $num_rows == 0 ) 
					{
						if ( $KebRemaja > 0){
						echo "<a href=\"PersembahanAnakEditor.php?GID=$refresh&Kategori=Remaja&Tanggal=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font size=\"1\"  color=\"red\"><p class=\"help\" title=\"Belum ADa Data Pers Remaja yang Diinput\"><blink>RemajaNoData!!</blink></p></font>";
						}
					}
				elseif ( $KebRemaja == $lPersembahan )
					{	
						echo "<a class=\"help\" title=\"Lihat Detail Pers.Remaja\" href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Remaja \" TARGET=\"_BLANK\">PR|</a>";
					} 
				else
					{
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Remaja \" TARGET=\"_BLANK\">
						<font size=\"1\" color=\"red\"> <blink><p class=\"help\" title=\"Remaja Terinput:".currency(' ',$KebRemaja,'.',',00')." seharusnya : ".currency(' ',$lPersembahan,'.',',00')."\">RemajaDataErr!!!</p></blink></font>";
					}

				// Cek Persembahan Pra Remaja
				$sSQL2b = "SELECT Persembahan_ID,Persembahan FROM PersembahanPraRemajagkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul= '" . $Pukul . "'";
				$rsPersembahanPraRemaja = RunQuery($sSQL2b);
				$num_rows = mysql_num_rows($rsPersembahanPraRemaja);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanPraRemaja))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				}
									
				if ( $num_rows == 0 ) 
					{
						if ( $KebPraRemaja > 0){
						echo "<a href=\"PersembahanAnakEditor.php?GID=$refresh&Kategori=PraRemaja&Tanggal=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font size=\"1\"  color=\"red\"><p class=\"help\" title=\"Belum ADa Data Pers Pra Remaja yang Diinput\"><blink>PraRemajaNoData!!</blink></p></font>";
						}
					}
				elseif ( $KebPraRemaja == $lPersembahan )
					{	
						echo "<a class=\"help\" title=\"Lihat Detail Pers.PraRemaja\" href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=PraRemaja \" TARGET=\"_BLANK\">PPR|</a>";
					} 
				else
					{
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=PraRemaja \" TARGET=\"_BLANK\">
						<font size=\"1\" color=\"red\"> <blink><p class=\"help\" title=\"PraRemaja Terinput:".currency(' ',$KebPraRemaja,'.',',00')." seharusnya : ".currency(' ',$lPersembahan,'.',',00')."\">PraRemajaDataErr!!!</p></blink></font>";
					}
	
				// Cek Persembahan Pemuda
				$sSQL2c = "SELECT Persembahan_ID,Persembahan FROM PersembahanPemudagkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul= '" . $Pukul . "'";
				$rsPersembahanPemuda = RunQuery($sSQL2c);
				$num_rows = mysql_num_rows($rsPersembahanPemuda);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanPemuda))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				}
									
				if ( $num_rows == 0 ) 
					{
						if ( $KebPemuda > 0){
						echo "<a href=\"PersembahanAnakEditor.php?GID=$refresh&Kategori=Pemuda&Tanggal=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font size=\"1\"  color=\"red\"><p class=\"help\" title=\"Belum ADa Data Pers Pra Remaja yang Diinput\"><blink>PemudaNoData!!</blink></p></font>";
						}
					}
				elseif ( $KebPemuda == $lPersembahan )
					{	
						echo "<a class=\"help\" title=\"Lihat Detail Pers.Pemuda\" href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Pemuda \" TARGET=\"_BLANK\">PP|</a>";
					} 
				else
					{
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Pemuda \" TARGET=\"_BLANK\">
						<font size=\"1\" color=\"red\"> <blink><p class=\"help\" title=\"Pemuda Terinput:".currency(' ',$KebPemuda,'.',',00')." seharusnya : ".currency(' ',$lPersembahan,'.',',00')."\">PemudaDataErr!!!</p></blink></font>";
					}
		
				
				// Cek Persembahan Anak
				$sSQL3 = "SELECT Persembahan_ID,Persembahan FROM PersembahanAnakgkjbekti  WHERE Tanggal='" . $Tanggal . "' AND KodeTI='" . $KodeTI . "' AND Pukul= '" . $Pukul . "'";
				$rsPersembahanAnak = RunQuery($sSQL3);
				$num_rows = mysql_num_rows($rsPersembahanAnak);	
				while ($hasilGD=mysql_fetch_array($rsPersembahanAnak))
				{
						extract($hasilGD);
						$lPersembahan_ID = $hasilGD[Persembahan_ID];
						$lPersembahan = $hasilGD[Persembahan] ;
				}
									
				if ( $num_rows == 0 ) 
					{
						if ( $KebAnak > 0){
						echo "<a href=\"PersembahanAnakEditor.php?GID=$refresh&Kategori=Anak&Tanggal=" . $Tanggal . "&KodeTI=" . $KodeTI . "&Pukul=" . $Pukul . " \">
						<font size=\"1\"  color=\"red\"><p class=\"help\" title=\"Belum ADa Data Pers Anak yang Diinput\"><blink>AnakNoData!!</blink></p></font>";
						}
					}
				elseif ( $KebAnak == $lPersembahan )
					{	
						echo "<a class=\"help\" title=\"Lihat Detail Pers.Anak\" href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Anak \" TARGET=\"_BLANK\">PA|</a>";
					} 
				else
					{
						echo "<a href=\"PersembahanAnakView.php?Persembahan_ID=" . $lPersembahan_ID . "&Kategori=Anak \" TARGET=\"_BLANK\">
						<font size=\"1\" color=\"red\"> <blink><p class=\"help\" title=\"Anak Terinput:".currency(' ',$KebAnak,'.',',00')." seharusnya : ".currency(' ',$lPersembahan,'.',',00')."\">AnakDataErr!!!</p></blink></font>";
					}				
					
							
						 ?></td>
						 
						 
						<td ALIGN=RIGHT><?php 
						$TotalJemaat = $Pria + $Wanita ;
						echo $TotalJemaat ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php 
						if (trim($Majelis1)) { $Mjls1=1 ;}else{ $Mjls1=0 ; }
						if (trim($Majelis2)) { $Mjls2=1 ;}else{ $Mjls2=0 ; }
						if (trim($Majelis3)) { $Mjls3=1 ;}else{ $Mjls3=0 ; }
						if (trim($Majelis4)) { $Mjls4=1 ;}else{ $Mjls4=0 ; }
						if (trim($Majelis5)) { $Mjls5=1 ;}else{ $Mjls5=0 ; }
						if (trim($Majelis6)) { $Mjls6=1 ;}else{ $Mjls6=0 ; }
						if (trim($Majelis7)) { $Mjls7=1 ;}else{ $Mjls7=0 ; }
						if (trim($Majelis8)) { $Mjls8=1 ;}else{ $Mjls8=0 ; }
						if (trim($Majelis9)) { $Mjls9=1 ;}else{ $Mjls9=0 ; }
						if (trim($Majelis10)) { $Mjls10=1 ;}else{ $Mjls10=0 ; }
						if (trim($Majelis11)) { $Mjls11=1 ;}else{ $Mjls11=0 ; }
						if (trim($Majelis12)) { $Mjls12=1 ;}else{ $Mjls12=0 ; }
						if (trim($Majelis13)) { $Mjls13=1 ;}else{ $Mjls13=0 ; }
						if (trim($Majelis14)) { $Mjls14=1 ;}else{ $Mjls14=0 ; }
						$TotMjls = $Mjls1 + $Mjls2 + $Mjls3 + $Mjls4 + $Mjls5 + $Mjls6 + $Mjls7 
						+ $Mjls8 + $Mjls9 + $Mjls10 + $Mjls11 + $Mjls12 + $Mjls13 + $Mjls14 ;
						
						//
						//echo $TotalMajelis 
						echo $TotMjls;
						?>&nbsp;</td>
			

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;



}


elseif ($iMode == 8)

{
// echo "listing persembahan Anak";
/**********************
**  Persembahan Anak Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
$Kategori = $_GET["Kategori"];		
        $sSQL = "SELECT * FROM Persembahan" . $Kategori . "gkjbekti a
		    LEFT JOIN LokasiTI b ON a.KodeTI=b.KodeTI ";
			
		//echo $sSQL;	

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "TempatIbadah":
                        $sSQL = $sSQL . " ORDER BY KodeTI, Tanggal DESC";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, a.KodeTI, Pukul";
                        break;
        }

        $rsPersembahanCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPersembahanCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

	//	echo $sSQL;
        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"PersembahanAnakEditor.php?GID=$refresh&Kategori=" . $Kategori . "\">" . gettext("Tambahkan Data Persembahan") . "</a></div>
		<div align=\"center\"><a href=\"PrintViewLapPersembahanAnak.php?GID=$refresh&Kategori=" . $Kategori . "\" target=\"_blank\" >" . gettext("Cetak Laporan Data Persembahan") . "</a></div>
		<BR>"; }
        ?>
		
        <form method="get" action="SelectList.php" name="PersembahanAnak">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="TempatIbadah"><?php echo gettext("TempatIbadah"); ?></option>
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="PersembahanAnak">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=PersembahanAnak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="PersembahanAnak">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				
				<?if ($Kategori=='Kontribusi'){
				echo "<td ALIGN=CENTER  >View</td>";
				echo "<td ALIGN=CENTER >Cetak Kwitansi</td>";
				echo "<td ALIGN=CENTER >Tanggal</td> 		";		
				echo "<td ALIGN=CENTER >Tempat Ibadah</td> ";
				echo "<td ALIGN=CENTER >Pukul</td> 	";
				echo "<td ALIGN=CENTER >Kontribusi dari</td> ";					
				echo "<td ALIGN=CENTER >Diterima dari</td>	";	
				echo "<td ALIGN=CENTER >Diterima oleh</td>		";				
				echo "<td ALIGN=CENTER >Total Persembahan</td> ";
				} 
				else {
				echo "<td ALIGN=CENTER  >View</td> ";
				echo "<td ALIGN=CENTER >Cetak</td>";
				echo "<td ALIGN=CENTER >Tanggal</td> 	";			
				echo "<td ALIGN=CENTER >Tempat Ibadah</td> ";
				echo "<td ALIGN=CENTER >Pukul</td> 	";
				echo "<td ALIGN=CENTER >Nas</td> 			";		
				echo "<td ALIGN=CENTER >Pengkotbah</td>			";	
				echo "<td ALIGN=CENTER >Total Persembahan</td> ";
				echo "<td ALIGN=CENTER >Total Jemaat</td> ";
				echo "<td ALIGN=CENTER >Total Majelis</td> 			";		
				
				}
				?>
				
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the persembahan recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$sPersembahan_ID = "";
				$sTanggal = "";
				$sPukul = "";
				$sKodeTI = "";


                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="PersembahanAnakEditor.php?Persembahan_ID=<?php echo $Persembahan_ID . "&Kategori=$Kategori&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="PersembahanAnakView.php?Persembahan_ID=<?php echo $Persembahan_ID ?>&Kategori=<?php echo $Kategori ?> "><?php echo "View" ?></a>&nbsp;</td>

				<?if ($Kategori=='Kontribusi'){
					?>	
						<td  ALIGN=CENTER><a target="_blank" href="PrintViewVoucher.php?WHO=Kontribusi&VCHID=<?php echo $Persembahan_ID."&GID=$refresh&"; ?>"><?php echo "Cetak" ?></a>&nbsp;</td>
					
						<td><?php echo $Tanggal ?>&nbsp;</td> 
						<td><?php echo $NamaTI ?>&nbsp;</td> 
						<td><?php echo $Pukul ?>&nbsp;</td> 
						<td><?php echo $Nas ?>&nbsp;</td>
						<td><?php echo $Pengkotbah ?>&nbsp;</td>
						<td><?php echo $Majelis1 ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php echo currency('Rp. ',$Persembahan,'.',',00');  ?>&nbsp;</td>

				<?	}else{	
						?>			
						<td><a target="_blank" href="PrintViewPersembahanAnak.php?Persembahan_ID=<?php echo $Persembahan_ID ?>&Kategori=<?php echo $Kategori."&GID=$refresh&"; ?> "><?php echo "Cetak" ?></a>&nbsp;</td>

						<td><?php echo $Tanggal ?>&nbsp;</td> 
						<td><?php echo $NamaTI ?>&nbsp;</td> 
						<td><?php echo $Pukul ?>&nbsp;</td> 
						<td><?php echo $Nas ?>&nbsp;</td>
						<td><?php echo $Pengkotbah ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php echo currency('Rp. ',$Persembahan,'.',',00');  ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php $TotalJemaat = $Pria + $Wanita ;	echo $TotalJemaat ?>&nbsp;</td>
						<td ALIGN=RIGHT><?php 
						if (trim($Majelis1)) { $Mjls1=1 ;}else{ $Mjls1=0 ; }
						if (trim($Majelis2)) { $Mjls2=1 ;}else{ $Mjls2=0 ; }
						$TotMjls = $Mjls1 + $Mjls2 ;
						echo $TotMjls;
						?>&nbsp;</td>
				<?	}		?>

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;



}
elseif ($iMode == 9)

{
// echo "listing formulir umum";
/**********************
**  Formulir Umum Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
$Kategori = $_GET["Kategori"];		
        $sSQL = "SELECT * FROM FormulirUmum" . $Kategori . "gkjbekti a
			LEFT JOIN person_per b ON a.per_ID = b.per_ID
			LEFT JOIN family_fam c ON b.per_fam_ID = c.fam_ID
			LEFT JOIN MasterKomisi d ON a.KomisiID = d.KomisiID
			LEFT JOIN MasterBidang e ON d.BidangID = e.BidangID
		    LEFT JOIN LokasiTI f ON a.KodeTI=f.KodeTI 
			LEFT JOIN asetgkjbekti g ON a.AssetID = g.AssetID
			LEFT JOIN asetklasifikasi h ON. g.AssetClass = h.classID
			";
			
		//echo $sSQL;	

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "NamaPeminjam":
                        $sSQL = $sSQL . " ORDER BY per_FirstName, Tanggal DESC";
                        break;
                case "NamaAset":
                        $sSQL = $sSQL . " ORDER BY majorclass, minorclass, Tanggal DESC";
                        break;	
				case "Tanggal":
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, a.KodeTI";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY Tanggal DESC, a.KodeTI";
                        break;
        }

        $rsPersembahanCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPersembahanCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

		//echo $sSQL;
        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"FormulirUmumEditor.php?GID=$refresh&Kategori=" . $Kategori . "\">" . gettext("Tambahkan Data ".$Kategori."") . "</a></div>
		<div align=\"center\"><a href=\"PrintViewLapFormulirUmum.php?GID=$refresh&Kategori=" . $Kategori . "\" target=\"_blank\" >" . gettext("Cetak Laporan Data ".$Kategori."") . "</a></div>
		<BR>"; }
        ?>
		
        <form method="get" action="SelectList.php" name="FormulirUmum">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="NamaPeminjam"><?php echo gettext("Nama Peminjam"); ?></option>
						<option value="NamaAset"><?php echo gettext("Nama Aset"); ?></option>
                        <option value="Tanggal"><?php echo gettext("Tanggal"); ?></option>
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="FormulirUmum">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=FormulirUmum&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=FormulirUmum&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=FormulirUmum&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=FormulirUmum&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=FormulirUmum&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="FormulirUmum">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td ALIGN=CENTER  ><?php echo gettext("View"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Cetak"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Tanggal"); ?></td> 				
				<td ALIGN=CENTER ><?php echo gettext("Tempat Ibadah"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Peminjam"); ?></td> 
				<td ALIGN=CENTER ><?php echo gettext("Klasifikasi"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Aset Detail"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Rencana Tgl Pengembalian"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Tgl Pengembalian"); ?></td>	
				<td ALIGN=CENTER ><?php echo gettext("Keperluan"); ?></td>
				<td ALIGN=CENTER ><?php echo gettext("Komisi/Bidang/Kelp"); ?></td>				
				<td ALIGN=CENTER ><?php echo gettext("Keterangan"); ?></td> 					
					
			
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the persembahan recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				$sPersembahan_ID = "";
				$sTanggal = "";
				$sPukul = "";
				$sKodeTI = "";


                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="FormulirUmumEditor.php?FormID=<?php echo $FormID . "&Kategori=$Kategori&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="FormulirUmumView.php?FormID=<?php echo $FormID ?>&Kategori=<?php echo $Kategori."&GID=$refresh&"; ?> " target="_blank" ><?php echo "View" ?></a>&nbsp;</td>
						<td><a href="PrintViewVoucher.php?FormID=<?php echo $FormID ?>&Kategori=<?php echo $Kategori ?>&WHO=<?php echo $Kategori."&GID=$refresh&"; ?> "target="_blank" ><?php echo "Cetak" ?></a>&nbsp;</td>

						<td><?php echo $Tanggal ?>&nbsp;</td> 
						<td><?php echo $NamaTI ?>&nbsp;</td> 
						<td align="center" ><a href="PersonView.php?PersonID=<?php echo $per_ID."&GID=$refresh&"; ?>" target="_blank"><?php echo $per_FirstName."<br>(".$per_WorkPhone.")"; ?></a>&nbsp;</td> 
						<td align="center" ><?php echo "<u>".$majorclass."</u><br>".$minorclass; ?>&nbsp;</td> 
						<td align="center" ><a href="AsetView.php?AssetID=<?php echo $AssetID ?>" target="_blank"><?php echo $Merk."-".$Type; ?></a>&nbsp;</td> 
						<td align="center" ><?php echo date2Ind($TanggalKembali,2); ?>&nbsp;</td>
						<td align="center" ><?php echo date2Ind($TanggalDikembalikan,2); ?>&nbsp;</td>
						<td><?php echo $Keperluan ?>&nbsp;</td>
						<td align="center" ><?php echo "<u>".$BidangLain.".".$NamaKomisi."</u><br>".$NamaBidang; ?>&nbsp;</td>
						<td><?php echo $Keterangan; ?>&nbsp;</td>
						
		</td>
			

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;



}
elseif ($iMode == 10)
{
// echo "listing soal pak";
/**********************
**  Soal PAK Listing  **
**********************/
		require "$sHeaderFile";
        // Base SQL
        $sSQL = "SELECT * FROM MasterSoalPak";

         //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "Kelas":
                        $sSQL = $sSQL . " ORDER BY KelasID DESC";
                        break;
                case "Semester":
                        $sSQL = $sSQL . " ORDER BY SemesterID, Kelas DESC";
                        break;
				case "TanggalInput":
                        $sSQL = $sSQL . " ORDER BY DateEntered DESC";
                        break;		
				case "TanggalEdit":
                        $sSQL = $sSQL . " ORDER BY DateLastEdited DESC";
                        break;			
				case "OpsiSoal":
                        $sSQL = $sSQL . " ORDER BY OpsiSoal ASC";
                        break;							
                default:
                        $sSQL = $sSQL . " ORDER BY KelasID ,SemesterID , OpsiSoal";
                        break;
        }

        $rsPakCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsPakCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsPak = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"SoalPakEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Soal PAK") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectList.php" name="SoalPak">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="Kelas"><?php echo gettext("Kelas"); ?></option>
                        <option value="Semester"><?php echo gettext("Semester"); ?></option>
						<option value="TanggalInput"><?php echo gettext("TanggalInput"); ?></option>
						<option value="TanggalEdit"><?php echo gettext("TanggalEdit"); ?></option>
						<option value="OpsiSoal"><?php echo gettext("OpsiSoal"); ?></option>						
                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="soalpak">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
    <?php
        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=pak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=soalpak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=soalpak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=soalpak&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="soalpak">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";
				if ($_SESSION['SearchLimit'] == "100")
                        $sLimit100 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
						<option value="100" '.$sLimit100.'>100</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
				<td><?php echo gettext("SoalID"); ?></td>
				<td><?php echo gettext("KelasID"); ?></td>
				<td><?php echo gettext("SemesterID"); ?></td>
				<td><?php echo gettext("Soal"); ?></td>
				<td><?php echo gettext("OpsiSoal"); ?></td>
				<td><?php echo gettext("Keterangan"); ?></td>

        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
	   </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsPak))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
				
				$SoalPAKID = "";
				$Enable = "";
				$KelasID = "";
				$SemesterID = "";
				$Soal = "";
				$OpsiSoal = "";
				$Keterangan = "";
				$DateLastEdited = "";
				$DateEntered = "";
				$EnteredBy = "";
				$EditedBy = "";
				
                extract($aRow);

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="SoalPakEditor.php?SoalPAKID=<?php echo $SoalPAKID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="SoalPakView.php?SoalPAKID=<?php echo $SoalPAKID ?>"><?php echo "View" ?></a>&nbsp;</td>
				<?php		
				if ($KelasID == 1) { $KelasID = "1 - SD"; } else 
				if ($KelasID == 2) { $KelasID = "2 - SD"; } else 
				if ($KelasID == 3) { $KelasID = "3 - SD"; } else 
				if ($KelasID == 4) { $KelasID = "4 - SD"; } else 
				if ($KelasID == 5) { $KelasID = "5 - SD"; } else 
				if ($KelasID == 6) { $KelasID = "6 - SD"; } else 
				if ($KelasID == 7) { $KelasID = "7 - SMP"; } else 
				if ($KelasID == 8) { $KelasID = "8 - SMP"; } else 
				if ($KelasID == 9) { $KelasID = "9 - SMP"; } else 
				if ($KelasID == 10) { $KelasID = "10 - SMA"; } else 
				if ($KelasID == 11) { $KelasID = "11 - SMA"; } else 
				if ($KelasID == 12) { $KelasID = "12 - SMA"; }  
				?>		
						<td><?php echo $KelasID ?>&nbsp;</td>
				<?php		
				if ($SemesterID == 1) { $SemesterID = "Ulangan Semester Ganjil"; } else 
				if ($SemesterID == 2) { $SemesterID = "Ulangan Semester Genap"; } else 
				if ($SemesterID == 3) { $SemesterID = "Ulangan Mid Semester Ganjil"; } else 
				if ($SemesterID == 4) { $SemesterID = "Ulangan Mid Semester Genap"; } else 
				if ($SemesterID == 5) { $SemesterID = "Ulangan Umum Kenaikan Kelas"; }  
				?>						
						<td><?php echo $SemesterID ?>&nbsp;</td>
						<td><?php echo $Soal ?>&nbsp;</td>
				<?php		
				if ($OpsiSoal == 1) { $OpsiSoal = "Benar-Salah"; } else 
				if ($OpsiSoal == 2) { $OpsiSoal = "Pilihan Ganda, 1Jawab"; } else 
				if ($OpsiSoal == 3) { $OpsiSoal = "Pilihan Ganda, 2Jawab"; } else 
				if ($OpsiSoal == 4) { $OpsiSoal = "Uraian - Essay"; }  
				?>		
						<td><?php echo $OpsiSoal ?>&nbsp;</td>
						<td><?php echo $Keterangan ?>&nbsp;</td>

				

                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($Institusi,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;



}


/**********************
**  Family Listing  **
**********************/
else
{
		require "$sHeaderFile";

        // Base SQL
        $sSQL = "SELECT * FROM family_fam";
		if  (is_numeric($sFilter)) {
               $sSQL .= " WHERE fam_ID LIKE '" . $sFilter . "%'";
		}
        elseif (isset($sLetter))
                $sSQL .= " WHERE fam_Name LIKE '" . $sLetter . "%'";
        elseif (isset($sFilter))
        {
                // break on the space...
                // $aFilter = explode(" ",$sFilter);
                //$sSQL .= " WHERE fam_Name LIKE '%" . $aFilter[0] . "%'";

                $sSQL .= " WHERE fam_Name LIKE '%" . $sFilter . "%'";
        }

        //Apply the sort based on what was passed in
        switch($sSort)
        {
                case "entered":
                        $sSQL = $sSQL . " ORDER BY fam_DateEntered DESC";
                        break;
                case "kelompok":
                        $sSQL = $sSQL . " ORDER BY fam_WorkPhone, fam_Name";
                        break;
                default:
                        $sSQL = $sSQL . " ORDER BY fam_Name";
                        break;
        }

        $rsFamCount = RunQuery($sSQL);
        $Total = mysql_num_rows($rsFamCount);

        // Append a LIMIT clause to the SQL statement
        if (empty($_GET['Result_Set']))
        {
                $Result_Set = 0;
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }
        else
        {
                $Result_Set = FilterInput($_GET['Result_Set'],'int');
                $sSQL .= " LIMIT $Result_Set, $iPerPage";
        }

        // Run The Query With a Limit to get result
        $rsFamilies = RunQuery($sSQL);

        // Run query to get first letters of name.
        $sSQL = "SELECT DISTINCT LEFT(fam_Name,1) AS letter FROM family_fam ORDER BY letter";
        $rsLetters = RunQuery($sSQL);

        //Does this user have AddModify permissions?
        if ($_SESSION['bAddRecords']) { echo "<div align=\"center\"><a href=\"FamilyEditor.php?GID=$refresh&\">" . gettext("Tambahkan Data Keluarga Baru") . "</a></div><BR>"; }
        ?>
        <form method="get" action="SelectList.php" name="FamilyList">
                <p align="center">
                <?php echo gettext("Diurutkan berdasarkan:"); ?>
                <select name="Sort">
                        <option value="name"><?php echo gettext("Nama"); ?></option>
                        <option value="kelompok"><?php echo gettext("Kelompok"); ?></option>
                        <option value="entered"><?php echo gettext("Isian terbaru"); ?></option>

                </select>&nbsp;
                <input type="text" name="Filter">
                <input type="hidden" name="mode" value="family">
                <input type="submit" class="icButton" value="<?php echo gettext("Aktifkan Filter"); ?>">
                </p>
        </form>
        <?php
        // Create Sort Links
        echo "<div align=\"center\">";
        echo "<a href=\"SelectList.php?mode=family\">" . gettext("Lihat Semua") . "</a>";
        while ($aLetter = mysql_fetch_array($rsLetters))
        {
                echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"SelectList.php?mode=family";
                if($sSort) echo "&amp;Sort=$sSort";
                echo "&amp;Letter=" . $aLetter[0] . "\">" . $aLetter[0] . "</a>";
        }

        echo "</div>";
        echo "<BR>";

        // Create Next / Prev Links and $Result_Set Value
        if ($Total > 0)
        {
                echo "<div align=\"center\">";
                echo "<form method=\"get\" action=\"SelectList.php\" name=\"ListNumber\">";

                // Show previous-page link unless we're at the first page
                if ($Result_Set < $Total && $Result_Set > 0)
                {
                        $thisLinkResult = $Result_Set - $iPerPage;
                        echo "<A HREF=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=family&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">Previous Page</A>&nbsp;&nbsp;";
                }

                // Calculate starting and ending Page-Number Links
                $Pages = ceil($Total / $iPerPage);
                $startpage =  (ceil($Result_Set / $iPerPage)) - 6;
                if ($startpage <= 2)
                        $startpage = 1;
                $endpage = (ceil($Result_Set / $iPerPage)) + 9;
                if ($endpage >= ($Pages - 1))
                        $endpage = $Pages;

                // Show Link "1 ..." if startpage does not start at 1
                if ($startpage != 1)
                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=0&amp;mode=family&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">1</a> ... \n";

                // Display page links
                if ($Pages > 1)
                {
                        for ($c = $startpage; $c <= $endpage; $c++)
                        {
                                $b = $c - 1;
                                $thisLinkResult = $iPerPage * $b;
                                if ($thisLinkResult != $Result_Set)
                                        echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=family&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$c</a>&nbsp;\n";
                                else
                                        echo "&nbsp;&nbsp;[ " . $c . " ]&nbsp;&nbsp;";
                        }
                }

                // Show Link "... xx" if endpage is not the maximum number of pages
                if ($endpage != $Pages)
                {
                        $thisLinkResult = ($Pages - 1) * $iPerPage;
                        echo " ... <a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=family&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">$Pages</a>\n";
                }

                // Show next-page link unless we're at the last page
                if ($Result_Set >= 0 && $Result_Set < $Total)
                {
                        $thisLinkResult=$Result_Set+$iPerPage;
                        if ($thisLinkResult<$Total)
                                echo "&nbsp;&nbsp;<a href=\"SelectList.php?Result_Set=$thisLinkResult&amp;mode=family&amp;Filter=$sFilter&amp;Sort=$sSort&amp;Letter=$sLetter\">" . gettext("Next Page") . "</a>";
                }
                ?>

                <input type="hidden" name="mode" value="family">
                <?php
                if(isset($sFilter))
                        echo '<input type="hidden" name="Filter" value="' . $sFilter . '">';
                if(isset($sSort))
                        echo '<input type="hidden" name="Sort" value="' . $sSort . '">';
                if(isset($sLetter))
                        echo '<input type="hidden" name="Letter" value="' . $sLetter . '">';

                // Display record limit per page
                if ($_SESSION['SearchLimit'] == "5")
                        $sLimit5 = "selected";
                if ($_SESSION['SearchLimit'] == "10")
                        $sLimit10 = "selected";
                if ($_SESSION['SearchLimit'] == "20")
                        $sLimit20 = "selected";
                if ($_SESSION['SearchLimit'] == "25")
                        $sLimit25 = "selected";
                if ($_SESSION['SearchLimit'] == "50")
                        $sLimit50 = "selected";

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'. gettext("Diperlihatkan:") . '&nbsp;
                <select class="SmallText" name="Number">
                        <option value="5" '.$sLimit5.'>5</option>
                        <option value="10" '.$sLimit10.'>10</option>
                        <option value="20" '.$sLimit20.'>20</option>
                        <option value="25" '.$sLimit25.'>25</option>
                        <option value="50" '.$sLimit50.'>50</option>
                </select>&nbsp;
                <input type="submit" class="icTinyButton" value="' . gettext("Jalankan") .'">
                </form>
                </div>';

         } ?>
        <BR>

        <table cellpadding="2" align="center" cellspacing="0" width="100%">

        <tr class="TableHeader">
                <?php if ($_SESSION['bEditRecords']) { ?>
                        <td width="25"><?php echo gettext("Edit"); ?></td>
				<?php } ?>
                <td><?php echo gettext("Nama Keluarga"); ?></td>
                <?php if ($bFamListFirstNames) echo "<td>" . gettext("Anggota Kelg.") . "</td>"; ?>
                <td><?php echo gettext("Alamat"); ?></td>
                <td><?php echo gettext("Kota"); ?></td>
                <td><?php echo gettext("Telp Rumah"); ?></td>
                <td><?php echo gettext("Kelompok"); ?></td>
        <?php
        //        <td><?php echo gettext("Diedit terakhir"); </td>
        ?>
        </tr>

        <tr>
                <td>&nbsp;</td>
        </tr>

        <?php
        //Loop through the family recordset
        while ($aRow = mysql_fetch_array($rsFamilies))
        {
                // Unfortunately, extract()'s behavior with NULL array entries is inconsistent across different PHP versions
                // To be safe, we need to manually clear these variables.
                $fam_Name = "";
                $fam_Address1 = "";
                $fam_Address2 = "";
                $fam_City = "";
                $fam_HomePhone = "";
                $fam_WorkPhone = "";
                $fam_DateLastEdited = "";

                extract($aRow);

                if ($bFamListFirstNames)
                {
                        // build string of member first names
                        $sFirstNames = "";
                        $sSQL = "SELECT per_FirstName FROM person_per
                                LEFT JOIN list_lst fmr ON per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
                                WHERE per_fam_ID = " . $fam_ID . " ORDER BY fmr.lst_OptionSequence";
                        $rsFirstNames = RunQuery($sSQL);

                        $bFirstItem = true;
                        while ($aTemp = mysql_fetch_array($rsFirstNames))
                        {
                                if ($bFirstItem) {
                                        $sFirstNames .= $aTemp["per_FirstName"];
                                        $bFirstItem = false;
                                }
                                else
                                        $sFirstNames .= ", " . $aTemp["per_FirstName"];
                        }
                }

                //Does this family name start with a new letter?
                if (mb_strtoupper(mb_substr($fam_Name,0,1,"UTF-8")) != $sPrevLetter)
                {
                        //Display the header
                        echo $sBlankLine;
                        echo "<tr><td class=\"ControlBreak\" colspan=\"2\"><b>" . mb_strtoupper(mb_substr($fam_Name,0,1,"UTF-8")) . "</b></td></tr>";
			$sBlankLine = "<tr><td>&nbsp;</td></tr>";
                        $sRowClass = "RowColorA";
                }

                //Alternate the row style
                $sRowClass = AlternateRowStyle($sRowClass);

                //Display the row
                ?>

                <tr class="<?php echo $sRowClass ?>">
                        <?php if ($_SESSION['bEditRecords']) { ?>
                                <td><a href="FamilyEditor.php?FamilyID=<?php echo $fam_ID . "&GID=$refresh&\">" . gettext ("Edit"); ?></a></td>
                        <?php } ?>
                        <td><a href="FamilyView.php?FamilyID=<?php echo $fam_ID."&GID=$refresh&"; ?>"><?php echo $fam_Name ?></a>&nbsp;</td>
                        <?php if ($bFamListFirstNames) echo "<td>" . $sFirstNames . "</td>"; ?>
                        <td><?php echo $fam_Address1;?><?php if ($fam_Address1 != "" && $fam_Address2 != "") { echo ", "; } ?><?php if ($fam_Address2 != "") echo $fam_Address2; ?>&nbsp;</td>
                        <td><?php echo $fam_City ?>&nbsp;</td>
                        <td><?php echo $fam_HomePhone ?>&nbsp;</td>
                        <td><?php echo $fam_WorkPhone ?>&nbsp;</td>
                <?php //        <td><?php echo $fam_DateLastEdited ?>&nbsp;</td>
                </tr>
                <?php
                //Store the first letter of the family name to allow for the control break
                $sPrevLetter = mb_strtoupper(mb_substr($fam_Name,0,1,"UTF-8"));
        }

        //Close the table
        echo "</table>";
        require "Include/Footer.php";
        exit;
}
?>
