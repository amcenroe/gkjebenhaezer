<?php
/*******************************************************************************
 *
 *  filename    : FamilyView.php
 *  last change : 2002-04-18
 *  website     : http://www.churchdb.org
 *  copyright   : Copyright 2001, 2002 Deane Barker, 2003 Chris Gebhardt, 2004-2005 Michael Wilt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *
 *  ChurchInfo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

//Include the function library
require "Include/Config.php";
require "Include/Functions.php";
require "Include/GeoCoder.php";

$iGID = FilterInput($_GET["GID"]);
$refresh=$refresh+$iGID;

//Set the page title
$sPageTitle = gettext("Data Keluarga");

//Get the FamilyID out of the querystring
$iFamilyID = FilterInput($_GET["FamilyID"],'int');

		$logvar1 = "View";
		$logvar2 = "Family View";
		//Update Logger
		$sSQL = "INSERT INTO logger (date, userid, fam_id, var1, var2)
			VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iFamilyID . "','" . $logvar1 . "','" . $logvar2 . "')";
		//Execute the SQL
			RunQuery($sSQL);


// Get the list of funds
$sSQL = "SELECT fun_ID,fun_Name,fun_Description,fun_Active FROM donationfund_fun";
if ($editorMode == 0) $sSQL .= " WHERE fun_Active = 'true'"; // New donations should show only active funds.
$rsFunds = RunQuery($sSQL);

if (isset($_POST["UpdatePledgeTable"]) && $_SESSION['bFinance'])
{
	$_SESSION['sshowPledges'] = isset($_POST["ShowPledges"]);
	$_SESSION['sshowPayments'] = isset($_POST["ShowPayments"]);
	$_SESSION['sshowSince'] = FilterInput($_POST["ShowSinceDate"]);
}

$dSQL = "SELECT fam_ID FROM family_fam order by fam_Name";
$dResults = RunQuery($dSQL);

$last_id = 0;
$next_id = 0;
$capture_next = 0;
while($myrow = mysql_fetch_row($dResults))
{
	$fid = $myrow[0];
	if ($capture_next == 1)
	{
	    $next_id = $fid;
		break;
	}
	if ($fid == $iFamilyID)
	{
		$previous_id = $last_id;
		$capture_next = 1;
	}
	$last_id = $fid;
}

if (($previous_id > 0)) {
    $previous_link_text = "<a class=\"SmallText\" href=\"FamilyView.php?FamilyID=$previous_id&GID=$refresh&\">" . gettext("Data Keluarga Sebelumnya") . "</a>";
}

if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"FamilyView.php?FamilyID=$next_id&GID=$refresh&\">" . gettext("Data Keluarga Berikutnya") . "</a>";
}

//Get the information for this family
$sSQL = "SELECT *, a.per_FirstName AS EnteredFirstName, a.Per_LastName AS EnteredLastName, b.per_FirstName AS EditedFirstName, b.per_LastName AS EditedLastName
		FROM family_fam
		LEFT JOIN person_per a ON fam_EnteredBy = a.per_ID
		LEFT JOIN person_per b ON fam_EditedBy = b.per_ID
		WHERE fam_ID = " . $iFamilyID;
$rsFamily = RunQuery($sSQL);
extract(mysql_fetch_array($rsFamily));

// Get the lists of custom person fields
$sSQL = "SELECT family_custom_master.* FROM family_custom_master
			WHERE fam_custom_Side = 'left' ORDER BY fam_custom_Order";
$rsLeftFamCustomFields = RunQuery($sSQL);

$sSQL = "SELECT family_custom_master.* FROM family_custom_master
			WHERE fam_custom_Side = 'right' ORDER BY fam_custom_Order";
$rsRightFamCustomFields = RunQuery($sSQL);

// Get the custom field data for this person.
$sSQL = "SELECT * FROM family_custom WHERE fam_ID = " . $iFamilyID;
$rsFamCustomData = RunQuery($sSQL);
$aFamCustomData = mysql_fetch_array($rsFamCustomData, MYSQL_BOTH);

//Get the notes for this family
$sSQL = "SELECT nte_ID, nte_Text, nte_DateEntered, nte_EnteredBy, nte_DateLastEdited, nte_EditedBy, a.per_FirstName AS EnteredFirstName, a.Per_LastName AS EnteredLastName, b.per_FirstName AS EditedFirstName, b.per_LastName AS EditedLastName 		FROM note_nte
		LEFT JOIN person_per a ON nte_EnteredBy = a.per_ID
		LEFT JOIN person_per b ON nte_EditedBy = b.per_ID
		WHERE nte_fam_ID = " . $iFamilyID . " AND (nte_Private = 0 OR nte_Private = " . $_SESSION['iUserID'] . ")";
$rsNotes = RunQuery($sSQL);

//Get the family members for this family
$sSQL = "SELECT per_ID, per_Title, per_FirstName, per_MiddleName, per_LastName, per_Suffix, per_Gender,
		per_BirthMonth, per_BirthDay, per_BirthYear, per_Flags, cls.lst_OptionName AS sClassName, fmr.lst_OptionName AS sFamRole
		FROM person_per
		LEFT JOIN list_lst cls ON per_cls_ID = cls.lst_OptionID AND cls.lst_ID = 1
		LEFT JOIN list_lst fmr ON per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
		WHERE per_fam_ID = " . $iFamilyID . " ORDER BY fmr.lst_OptionSequence, per_BirthYear";
$rsFamilyMembers = RunQuery($sSQL);

//Get the family members for this family Ayah 
$sSQL = "SELECT per_ID, per_FirstName as NamaAyah
		FROM person_per
		WHERE per_fam_ID = " . $iFamilyID . " AND per_fmr_ID = 1 LIMIT 1";
$rsAyah = RunQuery($sSQL);
	while ($aRow1 = mysql_fetch_array($rsAyah))
	{
	extract($aRow1);
	}

//Get the family members for this family Ibu 
$sSQL = "SELECT per_ID, per_FirstName as NamaIbu
		FROM person_per
		WHERE per_fam_ID = " . $iFamilyID . " AND per_fmr_ID = 2 LIMIT 1";
$sSQL; 
$rsIbu = RunQuery($sSQL);
	while ($aRow1 = mysql_fetch_array($rsIbu))
	{
	extract($aRow1);
	}
	
	
//Get the person members for this Ayah Ibu family  
$sSQL = "SELECT a.* , d.lst_OptionName AS NAHubKK , c.fam_Name AS NANamaKK
		FROM person_per a 
		LEFT JOIN person_custom b ON a.per_ID = b.per_ID 
		LEFT JOIN family_fam c ON a.per_fam_ID = c.fam_ID
		LEFT JOIN list_lst d ON a.per_fmr_ID = d.lst_OptionID AND d.lst_ID = 2 
		WHERE b.c16 = '" . $NamaAyah . "' AND b.c16 <> '' AND b.c17 <> '' AND b.c17 = '" . $NamaIbu . "' AND a.per_fam_id<>'" . $iFamilyID . "' 
		ORDER by a.per_BirthYear ASC";
//		echo $sSQL; 
$rsNamaAnak = RunQuery($sSQL);

//Get the pledges for this family
$sSQL = "SELECT plg_plgID, plg_FYID, plg_date, plg_amount, plg_schedule, plg_method,
         plg_comment, plg_DateLastEdited, plg_PledgeOrPayment, a.per_FirstName AS EnteredFirstName, a.Per_LastName AS EnteredLastName, b.fun_Name AS fundName, plg_NonDeductible
		 FROM pledge_plg
		 LEFT JOIN person_per a ON plg_EditedBy = a.per_ID
		 LEFT JOIN donationfund_fun b ON plg_fundID = b.fun_ID
		 WHERE plg_famID = " . $iFamilyID . " ORDER BY pledge_plg.plg_date";
$rsPledges = RunQuery($sSQL);

//Get the automatic payments for this family
$sSQL = "SELECT *, a.per_FirstName AS EnteredFirstName,
                   a.Per_LastName AS EnteredLastName,
                   b.fun_Name AS fundName
		 FROM autopayment_aut
		 LEFT JOIN person_per a ON aut_EditedBy = a.per_ID
		 LEFT JOIN donationfund_fun b ON aut_Fund = b.fun_ID
		 WHERE aut_famID = " . $iFamilyID . " ORDER BY autopayment_aut.aut_NextPayDate";
$rsAutoPayments = RunQuery($sSQL);

//Get the Properties assigned to this Family
$sSQL = "SELECT pro_Name, pro_ID, pro_Prompt, r2p_Value, prt_Name, pro_prt_ID
		FROM record2property_r2p
		LEFT JOIN property_pro ON pro_ID = r2p_pro_ID
		LEFT JOIN propertytype_prt ON propertytype_prt.prt_ID = property_pro.pro_prt_ID
		WHERE pro_Class = 'f' AND r2p_record_ID = " . $iFamilyID .
		" ORDER BY prt_Name, pro_Name";
$rsAssignedProperties = RunQuery($sSQL);

//Get all the properties
$sSQL = "SELECT * FROM property_pro WHERE pro_Class = 'f' ORDER BY pro_Name";
$rsProperties = RunQuery($sSQL);

//Get classifications
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 1 ORDER BY lst_OptionSequence";
$rsClassifications = RunQuery($sSQL);

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
	extract ($aRow);
	$aSecurityType[$lst_OptionID] = $lst_OptionName;
}

//Set the spacer cell width
$iTableSpacerWidth = 10;

// Format the phone numbers
$sHomePhone = ExpandPhoneNumber($fam_HomePhone,$fam_Country,$dummy);
$sWorkPhone = ExpandPhoneNumber($fam_WorkPhone,$fam_Country,$dummy);
$sCellPhone = ExpandPhoneNumber($fam_CellPhone,$fam_Country,$dummy);

require "Include/Header.php";

if ($previous_link_text) {
	echo "$previous_link_text | ";
}

$bOkToEdit = ($_SESSION['bEditRecords'] || ($_SESSION['bEditSelf'] && ($iFamilyID == $_SESSION['iFamID'])));

if ($bOkToEdit) { echo "<a class=\"SmallText\" href=\"FamilyEditor.php?FamilyID=" . $fam_ID . "&GID=$refresh&\">" . gettext("Edit Data Ini") . "</a> | "; }
if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?FamilyID=" . $fam_ID . "\">" . gettext("Hapus Data Ini") . "</a>"; }
if ($next_link_text && ($bOkToEdit || $_SESSION['bDeleteRecords'])) {
	echo " | $next_link_text";
}
elseif ($next_link_text) {
	echo "$next_link_text";
}
?>
<BR><br>
<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>
<td width="25%" valign="top" align="center">
	<?php


		if ($fam_Email == "Tamu")
		{
		echo "<div class=\"LightShadedBoxRIP\"> ";
		} elseif ($fam_Email == "Pindah")
		{
		echo "<div class=\"LightShadedBoxMOVE\"> ";
		} else
		{
		echo "<div class=\"LightShadedBox\"> ";
		}

		//Print the name and address header
		echo "<font size=\"4\"><b>" . gettext("Keluarga ") . " $fam_Name " . gettext(" .") . "</b></font>";
		if ($fam_Email == "NonAktif")
		{
		echo "<br><b><blink><font color=\"red\">" .$fam_Email. "</font></blink></b><br>";
		}else{
		echo "<br>" .$fam_Email. "<br>";}
		echo "<div class=\"TinyShadedBox\"> ";
		echo "<font size=\"3\">";
			if ($fam_Address1 != "") { echo $fam_Address1 . "<br>"; }
			if ($fam_Address2 != "") { echo $fam_Address2 . "<br>"; }
			if ($fam_City != "") { echo $fam_City . ", "; }
			if ($fam_State != "") { echo $fam_State; }
			if ($fam_Zip != "") { echo " " . $fam_Zip; }
			if ($fam_Country != "") { echo "<br>" . $fam_Country . "<br>"; }

			if ($fam_Latitude && $fam_Longitude) {
				if ($nChurchLatitude && $nChurchLongitude) {
					$sDistance = LatLonDistance($nChurchLatitude, $nChurchLongitude,$fam_Latitude, $fam_Longitude);
					$sDirection = LatLonBearing($nChurchLatitude, $nChurchLongitude,$fam_Latitude, $fam_Longitude);
		//			echo $sDistance . " " . strtolower($sDistanceUnit) . " " . $sDirection . "  Gereja<br>";
					echo $sDistance . " " . km . " " . $sDirection . "  Gereja<br>";
				}
			}
		echo "</font></div>";

		//Show links to mapquest, US Post Office, and Geocoder US
		$bShowUSLinks = false;
		$bShowMQLink = false;
		if ($fam_Address1 != "" && $fam_City != "" && $fam_State != "")
		{
			if ($fam_Country == "United States") {
				$sMQcountry = "";
				$bShowUSLinks = true;
			}
			elseif ($fam_Country == "Canada") {
				$sMQcountry = "country=CA&amp;";
				$bShowMQLink = true;
			}
		}

		if ($bShowUSLinks) {
			echo "<div align=left><a class=\"SmallText\" target=\"_blank\"
				href=\"http://www.mapquest.com/maps/map.adp?" .$sMQcountry .
				"city=" . urlencode($fam_City) . "&amp;state=" . $fam_State .
				"&amp;address=" . urlencode($fam_Address1) . "\">" . gettext("View Map") .
				"</a></div>";
				echo "<div align=center><a class=\"SmallText\" target=\"_blank\"
				href=\"http://zip4.usps.com/zip4/welcome.jsp?address2=" .
				urlencode($fam_Address1) . "&amp;city=" . urlencode($fam_City) .
				"&amp;state=" . $fam_State . "\">" . gettext("USPS") .
				"</a></div>";
				echo "<div align=right><a class=\"SmallText\" target=\"_blank\"
				href=\"http://geocoder.us/demo.cgi?address=" . urlencode($fam_Address1) .
				"%2C" . urlencode($fam_Zip) . "\">" . gettext("Geocode") .
				"</a></div>";
		}
		if ($bShowMQLink) {
			echo "<div align=left><a class=\"SmallText\" target=\"_blank\"
				href=\"http://www.mapquest.com/maps/map.adp?" .$sMQcountry .
				"city=" . urlencode($fam_City) . "&amp;state=" . $fam_State .
				"&amp;address=" . urlencode($fam_Address1) . "\">" . gettext("View Map") .
				"</a></div>";
		}
		echo "<br>";

		// Upload photo
		if ( isset($_POST["UploadPhoto"]) && ($_SESSION['bAddRecords'] || $bOkToEdit) ) {
			if ($_FILES['Photo']['name'] == "") {
				$PhotoError = gettext("Tidak ada Foto yang di pilih untuk UPLOAD.");
			} elseif ($_FILES['Photo']['type'] != "image/pjpeg" && $_FILES['Photo']['type'] != "image/jpeg") {
				$PhotoError = gettext("Hanya format JPEG/JPG yang bisa di UPLOAD.");
			} else {
				// Create the thumbnail used by PersonView
				$srcImage=imagecreatefromjpeg($_FILES['Photo']['tmp_name']);
				$src_w=imageSX($srcImage);
    			$src_h=imageSY($srcImage);

				// Calculate thumbnail's height and width (a "maxpect" algorithm)
				$dst_max_w = 200;
				$dst_max_h = 200;
				if ($src_w > $dst_max_w) {
					$thumb_w=$dst_max_w;
					$thumb_h=$src_h*($dst_max_w/$src_w);
					if ($thumb_h > $dst_max_h) {
						$thumb_h = $dst_max_h;
						$thumb_w = $src_w*($dst_max_h/$src_h);
					}
				}
				elseif ($src_h > $dst_max_h) {
					$thumb_h=$dst_max_h;
					$thumb_w=$src_w*($dst_max_h/$src_h);
					if ($thumb_w > $dst_max_w) {
						$thumb_w = $dst_max_w;
						$thumb_h = $src_h*($dst_max_w/$src_w);
					}
				}
				else {
					if ($src_w > $src_h) {
						$thumb_w = $dst_max_w;
						$thumb_h = $src_h*($dst_max_w/$src_w);
					} elseif ($src_w < $src_h) {
						$thumb_h = $dst_max_h;
						$thumb_w = $src_w*($dst_max_h/$src_h);
					} else {
						if ($dst_max_w >= $dst_max_h) {
							$thumb_w=$dst_max_h;
							$thumb_h=$dst_max_h;
						} else {
							$thumb_w=$dst_max_w;
							$thumb_h=$dst_max_w;
						}
					}
				}
				$dstImage=ImageCreateTrueColor($thumb_w,$thumb_h);
        		imagecopyresampled($dstImage,$srcImage,0,0,0,0,$thumb_w,$thumb_h,$src_w,$src_h);
				imagejpeg($dstImage,"Images/Family/thumbnails/" . $iFamilyID . ".jpg");
//				imagedestroy($dstImage);
//    			imagedestroy($srcImage);

				// Calculate thumbnail's height and width (a "maxpect" algorithm)
				$dst_max_w = 400;
				$dst_max_h = 350;
				if ($src_w > $dst_max_w) {
					$thumb_w=$dst_max_w;
					$thumb_h=$src_h*($dst_max_w/$src_w);
					if ($thumb_h > $dst_max_h) {
						$thumb_h = $dst_max_h;
						$thumb_w = $src_w*($dst_max_h/$src_h);
					}
				}
				elseif ($src_h > $dst_max_h) {
					$thumb_h=$dst_max_h;
					$thumb_w=$src_w*($dst_max_h/$src_h);
					if ($thumb_w > $dst_max_w) {
						$thumb_w = $dst_max_w;
						$thumb_h = $src_h*($dst_max_w/$src_w);
					}
				}
				else {
					if ($src_w > $src_h) {
						$thumb_w = $dst_max_w;
						$thumb_h = $src_h*($dst_max_w/$src_w);
					} elseif ($src_w < $src_h) {
						$thumb_h = $dst_max_h;
						$thumb_w = $src_w*($dst_max_h/$src_h);
					} else {
						if ($dst_max_w >= $dst_max_h) {
							$thumb_w=$dst_max_h;
							$thumb_h=$dst_max_h;
						} else {
							$thumb_w=$dst_max_w;
							$thumb_h=$dst_max_w;
						}
					}
				}
				$dstImage=ImageCreateTrueColor($thumb_w,$thumb_h);
        		imagecopyresampled($dstImage,$srcImage,0,0,0,0,$thumb_w,$thumb_h,$src_w,$src_h);
				imagejpeg($dstImage,"Images/Family/" . $iFamilyID . ".jpg");
				imagedestroy($dstImage);
    			imagedestroy($srcImage);

//				move_uploaded_file($_FILES['Photo']['tmp_name'], "Images/Family/" . $iFamilyID . ".jpg");
			}
		} elseif (isset($_POST["DeletePhoto"]) && $_SESSION['bDeleteRecords']) {
			unlink("Images/Family/" . $iFamilyID . ".jpg");
			unlink("Images/Family/thumbnails/" . $iFamilyID . ".jpg");
		}

		// Display photo or upload from file
		$photoFile = "Images/Family/thumbnails/" . $iFamilyID . ".jpg";
		if (file_exists($photoFile))
		{
			echo '<a target="_blank" href="Images/Family/' . $iFamilyID . '.jpg">';
			echo '<img border="1" src="'.$photoFile.'" width="200" height="200" ></a>';
			if ($bOkToEdit)
            {
                echo '
                    <form method="post" action="FamilyView.php?FamilyID=' . $iFamilyID . '">
                    <br>
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Delete Photo") . '"
                    name="DeletePhoto">
                    </form>';
			}
		} else {
			// Some old / M$ browsers can't handle PNG's correctly.
			if ($bDefectiveBrowser)
				echo '<img border="0" src="Images/NoFamPhoto.gif" alt="'.gettext("No Family Photo Found").'"><br><br><br>';
			else
				echo '<img border="0" src="Images/NoFamPhoto.png" alt="'.gettext("No Family Photo Found").'"><br><br><br>';

			if ($bOkToEdit) {
				if (isset($PhotoError))
                    echo '<span style="color: red;">' . $PhotoError . '</span><br>';

                echo '
                    <form method="post"
                    action="FamilyView.php?FamilyID=' . $iFamilyID . '"
                    enctype="multipart/form-data">
                    <input class="icTinyButton" type="file" name="Photo">
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Upload Photo") . '" name="UploadPhoto">
                    </form>';
			}
		}


		echo "<br>";

		// Upload Surat Nikah
		if ( isset($_POST["UploadSuratNikah"]) && ($_SESSION['bAddRecords'] || $bOkToEdit) ) {
			if ($_FILES['SuratNikah']['name'] == "") {
				$SuratNikahError = gettext("No SuratNikah selected for uploading.");
			} elseif ($_FILES['SuratNikah']['type'] != "image/pjpeg" && $_FILES['SuratNikah']['type'] != "image/jpeg") {
				$SuratNikahError = gettext("Only jpeg SuratNikahs can be uploaded.");
			} else {
				// Create the thumbnail used by PersonView
				$srcImage=imagecreatefromjpeg($_FILES['SuratNikah']['tmp_name']);
				$src_w=imageSX($srcImage);
    			$src_h=imageSY($srcImage);

				// Calculate thumbnail's height and width (a "maxpect" algorithm)
				$dst_max_w = 200;
				$dst_max_h = 350;
				if ($src_w > $dst_max_w) {
					$thumb_w=$dst_max_w;
					$thumb_h=$src_h*($dst_max_w/$src_w);
					if ($thumb_h > $dst_max_h) {
						$thumb_h = $dst_max_h;
						$thumb_w = $src_w*($dst_max_h/$src_h);
					}
				}
				elseif ($src_h > $dst_max_h) {
					$thumb_h=$dst_max_h;
					$thumb_w=$src_w*($dst_max_h/$src_h);
					if ($thumb_w > $dst_max_w) {
						$thumb_w = $dst_max_w;
						$thumb_h = $src_h*($dst_max_w/$src_w);
					}
				}
				else {
					if ($src_w > $src_h) {
						$thumb_w = $dst_max_w;
						$thumb_h = $src_h*($dst_max_w/$src_w);
					} elseif ($src_w < $src_h) {
						$thumb_h = $dst_max_h;
						$thumb_w = $src_w*($dst_max_h/$src_h);
					} else {
						if ($dst_max_w >= $dst_max_h) {
							$thumb_w=$dst_max_h;
							$thumb_h=$dst_max_h;
						} else {
							$thumb_w=$dst_max_w;
							$thumb_h=$dst_max_w;
						}
					}
				}
				$dstImage=ImageCreateTrueColor($thumb_w,$thumb_h);
        		imagecopyresampled($dstImage,$srcImage,0,0,0,0,$thumb_w,$thumb_h,$src_w,$src_h);
				imagejpeg($dstImage,"Images/Nikah/thumbnails/SN" . $iFamilyID . ".jpg");
//				imagedestroy($dstImage);
 //   			imagedestroy($srcImage);

 				// Calculate docs's height and width (a "maxpect" algorithm)
 				$dst_max_w = 800;
 				$dst_max_h = 1100;
 				if ($src_w > $dst_max_w) {
 					$thumb_w=$dst_max_w;
 					$thumb_h=$src_h*($dst_max_w/$src_w);
 					if ($thumb_h > $dst_max_h) {
 						$thumb_h = $dst_max_h;
 						$thumb_w = $src_w*($dst_max_h/$src_h);
 					}
 				}
 				elseif ($src_h > $dst_max_h) {
 					$thumb_h=$dst_max_h;
 					$thumb_w=$src_w*($dst_max_h/$src_h);
 					if ($thumb_w > $dst_max_w) {
 						$thumb_w = $dst_max_w;
 						$thumb_h = $src_h*($dst_max_w/$src_w);
 					}
 				}
 				else {
 					if ($src_w > $src_h) {
 						$thumb_w = $dst_max_w;
 						$thumb_h = $src_h*($dst_max_w/$src_w);
 					} elseif ($src_w < $src_h) {
 						$thumb_h = $dst_max_h;
 						$thumb_w = $src_w*($dst_max_h/$src_h);
 					} else {
 						if ($dst_max_w >= $dst_max_h) {
 							$thumb_w=$dst_max_h;
 							$thumb_h=$dst_max_h;
 						} else {
 							$thumb_w=$dst_max_w;
 							$thumb_h=$dst_max_w;
 						}
 					}
 				}
 				$dstImage=ImageCreateTrueColor($thumb_w,$thumb_h);
         		imagecopyresampled($dstImage,$srcImage,0,0,0,0,$thumb_w,$thumb_h,$src_w,$src_h);
 				imagejpeg($dstImage,"Images/Nikah/SN" . $iFamilyID . ".jpg");
 				imagedestroy($dstImage);
     			imagedestroy($srcImage);


//				move_uploaded_file($_FILES['SuratNikah']['tmp_name'], "Images/Nikah/SN" . $iFamilyID . ".jpg");
			}
		} elseif (isset($_POST["DeleteSuratNikah"]) && $_SESSION['bDeleteRecords']) {
			unlink("Images/Nikah/SN" . $iFamilyID . ".jpg");
			unlink("Images/Nikah/thumbnails/SN" . $iFamilyID . ".jpg");
		}

		// Display SuratNikah or upload from file
		$SuratNikahFile = "Images/Nikah/thumbnails/SN" . $iFamilyID . ".jpg";
		if (file_exists($SuratNikahFile))
		{
			echo '<a target="_blank" href="Images/Nikah/SN' . $iFamilyID . '.jpg">';
			echo '<img border="1" src="'.$SuratNikahFile.'"></a>';
			if ($bOkToEdit)
            {
                echo '
                    <form method="post" action="FamilyView.php?FamilyID=' . $iFamilyID . '">
                    <br>
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Delete SuratNikah") . '"
                    name="DeleteSuratNikah">
                    </form>';
			}
		} else {
			// Some old / M$ browsers can't handle PNG's correctly.
			if ($bDefectiveBrowser)
				echo '<img border="0" src="Images/NoSuratNikah.gif" alt="'.gettext("No Family SuratNikah Found").'"><br><br><br>';
			else
				echo '<img border="0" src="Images/NoSuratNikah.png" alt="'.gettext("No Family SuratNikah Found").'"><br><br><br>';

			if ($bOkToEdit) {
				if (isset($SuratNikahError))
                    echo '<span style="color: red;">' . $SuratNikahError . '</span><br>';

                echo '
                    <form method="post"
                    action="FamilyView.php?FamilyID=' . $iFamilyID . '"
                    enctype="multipart/form-data">
                    <input class="icTinyButton" type="file" name="SuratNikah">
                    <input type="submit" class="icTinyButton"
                    value="' . gettext("Upload SuratNikah") . '" name="UploadSuratNikah">
                    </form>';
			}
		}












	?>
	</div>
</td>
<td width="75%" valign="top" align="left">

	<b><?php echo gettext("Informasi Umum:"); ?></b>
	<div class="LightShadedBox">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
	<td align="center" valign="top">
		<table cellspacing="4" cellpadding="0" border="0">
		<tr>
			<td class="TinyLabelColumn"><?php echo gettext("Kelompok:"); ?></td>
			<td class="TinyTextColumn">
			<?php 
				if ($sWorkPhone == "Pindah")
				{
				echo "<font color=\"red\"><b> $sWorkPhone </b></font> ";
				} else 	if ($sWorkPhone == "")
				{
				echo "<font color=\"red\"><b><blink> ERROR !!! </blink></b></font> ";
				} else
				{
				echo $sWorkPhone ;
				}
				?>				

</td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Telp.Rumah:"); ?></td>
				<td class="TinyTextColumn"><?php echo $sHomePhone; ?></td>
			</tr>

		<?php /**
		**	<tr>
		**		<td class="TinyLabelColumn"><?php echo gettext("Handphone:"); ?></td>
		**		<td class="TinyTextColumn"><?php echo $sCellPhone; ?></td>
		**	</tr>
		**/ ?>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Status :"); ?></td>
				<td class="TinyTextColumn"><?php echo $fam_Email ; ?>		  </td>
			</tr>
		
		<?php
				// Display the left-side custom fields
				while ($Row = mysql_fetch_array($rsLeftFamCustomFields)) {
					extract($Row);
					if (($aSecurityType[$fam_custom_FieldSec] == 'bAll') or ($_SESSION[$aSecurityType[$fam_custom_FieldSec]]))
					{
						$currentData = trim($aFamCustomData[$fam_custom_Field]);
						if ($type_ID == 11) $fam_custom_Special = $sPhoneCountry;
						echo "<tr><td class=\"TinyLabelColumn\">" . $fam_custom_Name . "</td>";
						echo "<td class=\"TinyTextColumn\">" . displayCustomField($type_ID, $currentData, $fam_custom_Special) . "</td></tr>";
					}
				}
			?>
			</table>
		</td>
		<td align="center" valign="top">
			<table cellspacing="4" cellpadding="0" border="0">
<?php if (!$bHideFamilyNewsletter) { /* Newsletter can be hidden - General Settings */ ?>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Send newsletter:"); ?></td>
				<td class="TinyTextColumn"><?php echo $fam_SendNewsLetter; ?></td>
			</tr>
<?php } ?>
<?php if (!$bHideWeddingDate) { /* Wedding Date can be hidden - General Settings */ ?>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Tanggal Pernikahan:"); ?></td>
				<td class="TinyTextColumn"><?php echo FormatDate($fam_WeddingDate,false) ?>				  </td>
			</tr>
<?php } /* Wedding date can be hidden - General Settings */ ?>
<?php if (!$bHideLatLon) { /* Lat/Lon can be hidden - General Settings */ ?>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Latitude/Longitude"); ?></td>
				<td class="TinyTextColumn"><?php echo $fam_Latitude . " / ", $fam_Longitude; ?>				  </td>
			</tr>
<?php } /* Lat/Lon can be hidden - General Settings */ ?>
<?php if ($bUseDonationEnvelopes) { ?>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Envelope Number"); ?></td>
				<td class="TinyTextColumn"><?php echo $fam_Envelope; ?></td>
			</tr>
<?php } ?>
			<?php
				// Display the right-side custom fields
				while ($Row = mysql_fetch_array($rsRightFamCustomFields)) {
					extract($Row);
					if (($aSecurityType[$fam_custom_FieldSec] == 'bAll') or ($_SESSION[$aSecurityType[$fam_custom_FieldSec]]))
					{
						$currentData = trim($aFamCustomData[$fam_custom_Field]);
						if ($type_ID == 11) $fam_custom_Special = $sPhoneCountry;
						echo "<tr><td class=\"TinyLabelColumn\">" . $fam_custom_Name . "</td>";
						echo "<td class=\"TinyTextColumn\">" . displayCustomField($type_ID, $currentData, $fam_custom_Special) . "</td></tr>";
					}
				}
			?>
			</table>
		</td>
	</tr>
	</table>
	</div>

	<BR>

	<b><?php echo gettext("Anggota Keluarga:"); ?></b>

	<table cellpadding="5" cellspacing="0" width="100%">

	<tr class="TableHeader">
		<td><?php echo gettext("Nama"); ?></td>
		<td><?php echo gettext("Jenis Kelamin"); ?></td>
		<td><?php echo gettext("Peran"); ?></td>
		<td><?php echo gettext("Tgl.Lahir"); ?></td>
		<td><?php echo gettext("Umur"); ?></td>
		<td><?php echo gettext("Klasifikasi"); ?></td>
		<?php if ($bOkToEdit) { ?>
		<td><?php echo gettext("Edit"); ?></td>
		<?php } ?>
	</tr>

	<?php

	$sRowClass = "RowColorA";

	//Loop through all the family members
	while ($aRow =mysql_fetch_array($rsFamilyMembers))
	{
		$per_Title = "";
		$per_FirstName = "";
		$per_MiddleName = "";
		$per_LastName = "";
		$per_Suffix = "";
		$per_Gender = "";
		$per_BirthMonth = "";
		$per_BirthDay = "";
		$per_BirthYear = "";

		$sFamRole = "";
		$sClassName = "";

		extract($aRow);

		//Alternate the row style
		$sRowClass = AlternateRowStyle($sRowClass)

		//Display the family member
		?>

		<tr class="<?php echo $sRowClass ?>">
			<td>
				<a href="PersonView.php?PersonID=<?php echo $per_ID."&amp;GID=$refresh"; ?>"><?php
			if ($per_Suffix)
			{
				if ($per_Title)
				{
					 echo $per_Title . " " . $per_FirstName . " , " . $per_Suffix;
				}
				else
				{
					 echo $per_FirstName . " , " . $per_Suffix;
				}
			}
			else
			{
				if ($per_Title)
				{
					echo $per_Title . " " . $per_FirstName . " " ;
				}
				else
				{
					echo $per_FirstName . " " ;
				}





			}?></a>
				<br>
			</td>
			<td>
				<?php switch ($per_Gender) {case 1: echo gettext("Laki-laki"); break; case 2: echo gettext("Perempuan"); break; default: echo "";} ?>&nbsp;
			</td>
			<td>
				<?php echo $sFamRole ?>&nbsp;
			</td>
			<td>
				<?php echo $per_BirthDay,"/",$per_BirthMonth,"/",$per_BirthYear ?>&nbsp;
			</td>
			<td>
				<?php PrintAge($per_BirthMonth,$per_BirthDay,$per_BirthYear,$per_Flags); ?>&nbsp;
			</td>
			<td>
				<?php

				if ($sClassName == "Meninggal")
				{
				echo "<font color=\"red\"><b> $sClassName </b></font> ";

				} elseif ($sClassName == "Pindah")
				{
				echo "<font color=\"red\"><b> $sClassName </b></font> ";
				} else
				{
				echo $sClassName ;
				}
				?>
			</td>
			<?php if ($bOkToEdit) { ?>
			<td>
				<a href="PersonEditor.php?PersonID=<?php echo $per_ID."&GID=$refresh&"; ?>">Edit</a>
			</td>
			<?php } ?>
		</tr>

		<?php

	}

?>



</td>



  </tr>








</table>
<BR>

	<BR>

	<b><?php echo gettext("Anggota Keluarga yang sudah Menikah/Mandiri/Berkeluarga:"); ?></b>

	<table cellpadding="5" cellspacing="0" width="100%">

	<tr class="TableHeader">
		<td><?php echo gettext("Nama"); ?></td>
		<td><?php echo gettext("Jenis Kelamin"); ?></td>
		<td><?php echo gettext("Tgl.Lahir"); ?></td>
		<td><?php echo gettext("Umur"); ?></td>
		<td><?php echo gettext("Klasifikasi"); ?></td>
		<td><?php echo gettext("Status Keluarga"); ?></td>
		<?php if ($bOkToEdit) { ?>
		<td><?php echo gettext("Edit"); ?></td>
		<?php } ?>
	</tr>

	<?php

	$sRowClass = "RowColorA";

	//Loop through all the family members
	while ($aRow =mysql_fetch_array($rsNamaAnak))
	{
		$per_Title = "";
		$per_FirstName = "";
		$per_MiddleName = "";
		$per_LastName = "";
		$per_Suffix = "";
		$per_Gender = "";
		$per_BirthMonth = "";
		$per_BirthDay = "";
		$per_BirthYear = "";
		$per_fam_ID = "";

		$sFamRole = "";
		$sClassName = "";

		extract($aRow);

		//Alternate the row style
		$sRowClass = AlternateRowStyle($sRowClass)

		//Display the family member
		?>

		<tr class="<?php echo $sRowClass ?>">
			<td>
				<a href="PersonView.php?PersonID=<?php echo $per_ID."&amp;GID=$refresh"; ?>"><?php
			if ($per_Suffix)
			{
				if ($per_Title)
				{
					 echo $per_Title . " " . $per_FirstName . " , " . $per_Suffix;
				}
				else
				{
					 echo $per_FirstName . " , " . $per_Suffix;
				}
			}
			else
			{
				if ($per_Title)
				{
					echo $per_Title . " " . $per_FirstName . " " ;
				}
				else
				{
					echo $per_FirstName . " " ;
				}





			}?></a>
				<br>
			</td>
			<td>
				<?php switch ($per_Gender) {case 1: echo gettext("Laki-laki"); break; case 2: echo gettext("Perempuan"); break; default: echo "";} ?>&nbsp;
			</td>
			<td>
				<?php echo $per_BirthDay,"/",$per_BirthMonth,"/",$per_BirthYear ?>&nbsp;
			</td>
			<td>
				<?php PrintAge($per_BirthMonth,$per_BirthDay,$per_BirthYear,$per_Flags); ?>&nbsp;
			</td>
			<td>
				<?php
				if ($per_cls_ID == 1)
				{
				echo "<font color=\"green\"><b> Warga </b></font> ";

				} elseif ($sClassName == 5)
				if ($per_cls_ID == 6)
				{
				echo "<font color=\"red\"><b> Meninggal </b></font> ";

				} elseif ($sClassName == 5)
				{
				echo "<font color=\"red\"><b> Pindah </b></font> ";
				} else
				{
				echo $per_cls_ID ;
				}
				?>
			</td>
			<td>
			<?php
			//Get the person members for this Ayah Ibu family  
			//$sSQL = "SELECT fam_Name  as NamaKK
			//		FROM family_fam 
			//		WHERE fam_ID = '" . $per_fam_ID . "'";
					
			//		$rsNamaKK = RunQuery($sSQL);
			//while ($bRow =mysql_fetch_array($rsNamaKK))
			//{
			//		$fam_Name = "";
			//		extract($bRow);
			//echo "<a href=\"FamilyView.php?FamilyID=" . $per_fam_ID . "\">" . $NamaKK . "</a>";		
			//}		
			echo $NAHubKK ;
			echo " <a href=\"FamilyView.php?FamilyID=" . $per_fam_ID . "&GID=$refresh&\">" . $NANamaKK . "</a>";		
			?>

			
			
			</td>
			<?php if ($bOkToEdit) { ?>
			<td>
				<a href="PersonEditor.php?PersonID=<?php echo $per_ID."&GID=$refresh&"; ?>">Edit</a>
			</td>
			<?php } ?>
		</tr>

		<?php

	}

?>



</td>



  </tr>








</table>
<BR>

	<BR>
	<b><?php echo gettext("Keterangan Khusus:"); ?></b>
    <?php

$sAssignedProperties = ",";

//Was anything returned?
if (mysql_num_rows($rsAssignedProperties) == 0)
{
	//No, indicate nothing returned
	echo "<p align=\"left\">" . gettext("Tidak Ada Data.") . "</p>";
}
else
{

	//Yes, start the table
	echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
	echo "<tr class=\"TableHeader\">";
	echo "<td width=\"10%\" valign=\"top\"><b>" . gettext("Type") . "</b></td>";
	echo "<td width=\"15%\" valign=\"top\"><b>" . gettext("Name") . "</b></td>";
	echo "<td valign=\"top\"><b>" . gettext("Value") . "</b></td>";

	if ($bOkToEdit)
	{
		echo "<td width=\"10%\" valign=\"top\"><b>" . gettext("Edit Value") . "</td>";
		echo "<td valign=\"top\"><b>" . gettext("Remove") . "</td>";
	}

	echo "</tr>";

	$last_pro_prt_ID = "";
	$bIsFirst = true;

	//Loop through the rows
	while ($aRow = mysql_fetch_array($rsAssignedProperties))
	{
		$pro_Prompt = "";
		$r2p_Value = "";

		extract($aRow);

		if ($pro_prt_ID != $last_pro_prt_ID)
		{
			echo "<tr class=\"";
			if ($bIsFirst)
				echo "RowColorB";
			else
				echo "RowColorC";
			echo "\"><td><b>" . $prt_Name . "</b></td>";

			$bIsFirst = false;
			$last_pro_prt_ID = $pro_prt_ID;
			$sRowClass = "RowColorB";
		}
		else
		{
			echo "<tr class=\"" . $sRowClass . "\">";
			echo "<td valign=\"top\">&nbsp;</td>";
		}

		echo "<td valign=\"center\">" . $pro_Name . "</td>";
		echo "<td valign=\"center\">" . $r2p_Value . "&nbsp;</td>";

		if ($bOkToEdit)
		{
			if (strlen($pro_Prompt) > 0)
			{
				echo "<td valign=\"center\"><a href=\"PropertyAssign.php?FamilyID=" . $iFamilyID . "&amp;PropertyID=" . $pro_ID . "\">" . gettext("Edit Value") . "</a></td>";
			}
			else
			{
				echo "<td>&nbsp;</td>";
			}

			echo "<td valign=\"center\"><a href=\"PropertyUnassign.php?FamilyID=" . $iFamilyID . "&amp;PropertyID=" . $pro_ID . "\">" . gettext("Remove") . "</a></td>";
		}

		echo "</tr>";

		//Alternate the row style
		$sRowClass = AlternateRowStyle($sRowClass);

		$sAssignedProperties .= $pro_ID . ",";
	}

	//Close the table
	echo "</table>";


}



?>





    <?php if ($bOkToEdit) { ?>
    <form method="post" action="PropertyAssign.php?FamilyID=<?php echo $iFamilyID ?>">
      <p align="center"> <span class="SmallText"><?php echo gettext("Menambahkan Keterangan Khusus:"); ?></span>
          <select name="PropertyID">
            <?php
		while ($aRow = mysql_fetch_array($rsProperties))
		{
			extract($aRow);
			//If the property doesn't already exist for this Person, write the <OPTION> tag
			if (strlen(strstr($sAssignedProperties,"," . $pro_ID . ",")) == 0)
			{
				echo "<option value=\"" . $pro_ID . "\">" . $pro_Name . "</option>";
			}
		}
		?>
          </select>
          <input type="submit" class="icButton" value="Tambahkan" name="Submit2" style="font-size: 8pt;">
      </p>
    </form>

	<?php } ?>





<BR>
<BR>
<hr>
<p class="SmallText"><i>
	<?php echo gettext("Didata pada:"); ?> <?php echo FormatDate($fam_DateEntered,True) . " oleh " . $EnteredFirstName . " " . $EnteredLastName; ?>
	<br>

	<?php
	if (strlen($fam_DateLastEdited) > 0)
	{
		echo gettext("DiUpdate Terakhir:") . " " . FormatDate($fam_DateLastEdited,True) . " ". gettext("oleh") . " " . $EditedFirstName . " " . $EditedLastName;
	}
	?>
	</i>
</p>



</table>


<?php if ($_SESSION['bFinance']) { ?>

<?php if (mysql_num_rows ($rsAutoPayments) > 0) { ?>

<br>
<b><?php echo gettext("Automatic Payments:"); ?></b>
<br>

<table cellpadding="5" cellspacing="0" width="100%">

<tr class="TableHeader">
	<td><?php echo gettext("Type"); ?></td>
	<td><?php echo gettext("Next payment date"); ?></td>
	<td><?php echo gettext("Amount"); ?></td>
	<td><?php echo gettext("Interval (months)"); ?></td>
	<td><?php echo gettext("Fund"); ?></td>
	<td><?php echo gettext("Edit"); ?></td>
	<td><?php echo gettext("Delete"); ?></td>
	<td><?php echo gettext("Date Updated"); ?></td>
	<td><?php echo gettext("Updated By"); ?></td>
</tr>

<?php

	$tog = 0;

	//Loop through all automatic payments
	while ($aRow =mysql_fetch_array($rsAutoPayments))
	{
		$tog = (! $tog);

		extract($aRow);

		$payType = "Disabled";
		if ($aut_EnableBankDraft)
			$payType = "Bank Draft";
		if ($aut_EnableCreditCard)
			$payType = "Credit Card";

		//Alternate the row style
		if ($tog)
			$sRowClass = "RowColorA";
		else
			$sRowClass = "RowColorB";

		?>

		<tr class="<?php echo $sRowClass ?>">
			<td>
				<?php echo $payType ?>&nbsp;
			</td>
			<td>
				<?php echo $aut_NextPayDate ?>&nbsp;
			</td>
			<td>
				<?php echo $aut_Amount ?>&nbsp;
			</td>
			<td>
				<?php echo $aut_Interval ?>&nbsp;
			</td>
			<td>
				<?php echo $fundName ?>&nbsp;
			</td>
			<td>
				<a href="AutoPaymentEditor.php?AutID=<?php echo $aut_ID ?>&amp;FamilyID=<?php echo $iFamilyID;?>&amp;linkBack=FamilyView.php?FamilyID=<?php echo $iFamilyID;?>">Edit</a>
			</td>
			<td>
				<a href="AutoPaymentDelete.php?AutID=<?php echo $aut_ID ?>&amp;linkBack=FamilyView.php?FamilyID=<?php echo $iFamilyID;?>">Delete</a>
			</td>
			<td>
				<?php echo $aut_DateLastEdited; ?>&nbsp;
			</td>
			<td>
				<?php echo $EnteredFirstName . " " . $EnteredLastName; ?>&nbsp;
			</td>
		</tr>
		<?php
	}

?></table><?php

}

?>

<br>

<br>


<?php


$tog = 0;

if ($_SESSION['sshowPledges'] || $_SESSION['sshowPayments'])
{
	//Loop through all pledges
	while ($aRow =mysql_fetch_array($rsPledges))
	{
		$tog = (! $tog);

		$plg_FYID = "";
		$plg_date = "";
		$plg_amount = "";
		$plg_schedule = "";
		$plg_method = "";
		$plg_comment = "";
		$plg_plgID = 0;
		$plg_DateLastEdited  = "";
		$plg_EditedBy = "";

		extract($aRow);

		//Display the pledge or payment if appropriate
		if ((($_SESSION['sshowPledges'] && $plg_PledgeOrPayment == 'Pledge') ||
		     ($_SESSION['sshowPayments'] && $plg_PledgeOrPayment == 'Payment')
			 ) &&
		    ($_SESSION['sshowSince'] == "" || $plg_date > $_SESSION['sshowSince'])
		   )
		{
			//Alternate the row style
			if ($tog)
				$sRowClass = "RowColorA";
			else
				$sRowClass = "RowColorB";

			if ($plg_PledgeOrPayment == 'Payment') {
				if ($tog)
					$sRowClass = "PaymentRowColorA";
				else
					$sRowClass = "PaymentRowColorB";
			}

			?>

			<tr class="<?php echo $sRowClass ?>" align="center">
				<td>
					<?php echo $plg_PledgeOrPayment ?>&nbsp;
				</td>
				<td>
					<?php echo $fundName ?>&nbsp;
				</td>
				<td>
					<?php echo MakeFYString ($plg_FYID) ?>&nbsp;
				</td>
				<td>
					<?php echo $plg_date ?>&nbsp;
				</td>
				<td align=center>
					<?php echo $plg_amount ?>&nbsp;
				</td>
				<td align=center>
					<?php echo $plg_NonDeductible ?>&nbsp;
				</td>
				<td>
					<?php echo $plg_schedule ?>&nbsp;
				</td>
				<td>
					<?php echo $plg_method; ?>&nbsp;
				</td>
				<td>
					<?php echo $plg_comment; ?>&nbsp;
				</td>
				<td>
					<a href="PledgeEditor.php?PledgeID=<?php echo $plg_plgID ?>&amp;linkBack=FamilyView.php?FamilyID=<?php echo $iFamilyID;?>">Edit</a>
				</td>
				<td>
					<a href="PledgeDelete.php?PledgeID=<?php echo $plg_plgID ?>&amp;linkBack=FamilyView.php?FamilyID=<?php echo $iFamilyID;?>">Delete</a>
				</td>
				<td>
					<?php echo $plg_DateLastEdited; ?>&nbsp;
				</td>
				<td>
					<?php echo $EnteredFirstName . " " . $EnteredLastName; ?>&nbsp;
				</td>
			</tr>
			<?php
		}
	}
} // if bShowPledges

?>

</table>


<?php } ?>

<?php if ($_SESSION['bCanvasser']) { ?>


<?php } ?>

<br>

<?php if ($_SESSION['bNotes']) { ?>
<p>
	<b><?php echo gettext("Catatan:"); ?></b>
</p>

<p>
	<a class="SmallText" href="NoteEditor.php?FamilyID=<?php echo $fam_ID."&GID=$refresh&"; ?>"><?php echo gettext("Tambahkan Catatan Baru"); ?></a>
</p>

<?php

//Loop through all the notes
while($aRow = mysql_fetch_array($rsNotes))
{
	extract($aRow);

	?>

<p class="ShadedBox")>
		<?php echo $nte_Text ?>
</p>
	<span class="SmallText"><?php echo gettext("Didata pada:") . ' ' . FormatDate($nte_DateEntered,True) . ' ' . gettext("oleh") . ' ' . $EnteredFirstName . " " . $EnteredLastName ?></span>
	<br>
	<?php

	if (strlen($nte_DateLastEdited) > 0)
	{ ?>
		<span class="SmallText"><?php echo gettext("DiUpdate Terakhir:") . ' ' . FormatDate($nte_DateLastEdited,True) . ' ' . gettext("oleh") . ' ' . $EditedFirstName . " " . $EditedLastName ?></span>
		<br>
	<?php
	} ?>
	<a class="SmallText" href="NoteEditor.php?FamilyID=<?php echo $iFamilyID."&GID=$refresh&"; ?>&amp;NoteID=<?php echo $nte_ID ?>"><?php echo gettext("Edit This Note"); ?></a></span>
	|
	<a class="SmallText" href="NoteDelete.php?NoteID=<?php echo $nte_ID ?>"><?php echo gettext("Delete This Note"); ?></a>

	<?php

}
?>
<?php } ?>

<?php
require "Include/Footer.php";
?>