<?php
/*******************************************************************************
 *
 *  filename    : PersonViewMove.php
 *  last change : 2003-04-14
 *  description : Displays all the information about a single person
 *
 *  http://www.infocentral.org/
 *  Copyright 2001-2003 Phillip Hullquist, Deane Barker, Chris Gebhardt
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *  InfoCentral is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Get the person ID from the querystring
$iPersonID = FilterInput($_GET["PersonID"],'int');

$iRemoveVO = FilterInput($_GET["RemoveVO"],'int');

if ( isset($_POST["GroupAssign"]) && $_SESSION['bManageGroups'] )
{
	$iGroupID = FilterInput($_POST["GroupAssignID"],'int');
	AddToGroup($iPersonID,$iGroupID,0);

	$sSQL = "SELECT grp_Name FROM person2group2role_p2g2r INNER JOIN group_grp
			ON person2group2role_p2g2r.p2g2r_grp_ID = group_grp.grp_ID
			WHERE p2g2r_per_ID = " . $iPersonID . " LIMIT 1 ";
	$rsAssignedKelompok = RunQuery($sSQL);
	$Row = mysql_fetch_row($rsAssignedKelompok);
		$iRoleID = $Row[0];


	$sSQL = "UPDATE `person_pindah` SET `per_WorkPhone` = \" " . $iRoleID . " \" WHERE `per_ID` = " . $iPersonID . "  LIMIT 1 ";
	$rsUpdateKelompokPersonPer = RunQuery($sSQL);

}

if ( isset($_POST["VolunteerOpportunityAssign"]) && $_SESSION['bEditRecords'])
{
	$iVolunteerOpportunityID = FilterInput($_POST["VolunteerOpportunityID"],'int');
	AddVolunteerOpportunity($iPersonID,$iVolunteerOpportunityID);
}

// Service remove-volunteer-opportunity (these links set RemoveVO)
if ($iRemoveVO > 0  && $_SESSION['bEditRecords'])
{
	RemoveVolunteerOpportunity($iPersonID, $iRemoveVO);
}

$dSQL= "SELECT per_ID FROM person_pindah order by per_LastName, per_FirstName";
$dResults = RunQuery($dSQL);

$last_id = 0;
$next_id = 0;
$capture_next = 0;
while($myrow = mysql_fetch_row($dResults))
{
	$pid = $myrow[0];
	if ($capture_next == 1)
	{
	    $next_id = $pid;
		break;
	}
	if ($pid == $iPersonID)
	{
		$previous_id = $last_id;
		$capture_next = 1;
	}
	$last_id = $pid;
}

if (($previous_id > 0)) {
    $previous_link_text = "<a class=\"SmallText\" href=\"PersonViewMove.php?PersonID=$previous_id\"><img border=0 src=\"Images/Icons/ico_prev.png\"  width=\"30\" height=\"30\"  > " . gettext("Sebelumnya") . "</a>";
}

if (($next_id > 0)) {
    $next_link_text = "<a class=\"SmallText\" href=\"PersonViewMove.php?PersonID=$next_id\"><img border=0 src=\"Images/Icons/ico_next.png\"   width=\"30\" height=\"30\"   >" . gettext("Berikutnya") . "</a>";
}

// Get this person's data
$sSQL = "SELECT a.*, family_pindah.*, cls.lst_OptionName AS sClassName, fmr.lst_OptionName AS sFamRole, b.per_FirstName AS EnteredFirstName,
				b.Per_LastName AS EnteredLastName, c.per_FirstName AS EditedFirstName, c.per_LastName AS EditedLastName
			FROM person_pindah a
			LEFT JOIN family_pindah ON a.per_fam_ID = family_pindah.fam_ID
			LEFT JOIN list_lst cls ON a.per_cls_ID = cls.lst_OptionID AND cls.lst_ID = 1
			LEFT JOIN list_lst fmr ON a.per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
			LEFT JOIN person_pindah b ON a.per_EnteredBy = b.per_ID
			LEFT JOIN person_pindah c ON a.per_EditedBy = c.per_ID
			WHERE a.per_ID = " . $iPersonID;
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

// Get the lists of custom person fields
$sSQL = "SELECT person_custom_master.* FROM person_custom_master
			WHERE custom_Side = 'left' ORDER BY custom_Order";
$rsLeftCustomFields = RunQuery($sSQL);

$sSQL = "SELECT person_custom_master.* FROM person_custom_master
			WHERE custom_Side = 'right' ORDER BY custom_Order";
$rsRightCustomFields = RunQuery($sSQL);

// Get the custom field data for this person.
$sSQL = "SELECT * FROM person_custom_pindah WHERE per_ID = " . $iPersonID;
$rsCustomData = RunQuery($sSQL);
$aCustomData = mysql_fetch_array($rsCustomData, MYSQL_BOTH);

// Get the notes for this person
$sSQL = "SELECT nte_Private, nte_ID, nte_Text, nte_DateEntered, nte_EnteredBy, nte_DateLastEdited, nte_EditedBy, a.per_FirstName AS EnteredFirstName, a.Per_LastName AS EnteredLastName, b.per_FirstName AS EditedFirstName, b.per_LastName AS EditedLastName ";
$sSQL .= "FROM note_nte ";
$sSQL .= "LEFT JOIN person_pindah a ON nte_EnteredBy = a.per_ID ";
$sSQL .= "LEFT JOIN person_pindah b ON nte_EditedBy = b.per_ID ";
$sSQL .= "WHERE nte_per_ID = " . $iPersonID;

// Admins should see all notes, private or not.  Otherwise, only get notes marked non-private or private to the current user.
if (!$_SESSION['bAdmin'])
	$sSQL .= " AND (nte_Private = 0 OR nte_Private = " . $_SESSION['iUserID'] . ")";

$rsNotes = RunQuery($sSQL);

// Get the Groups this Person is assigned to
$sSQL = "SELECT grp_ID, grp_Name, grp_hasSpecialProps, role.lst_OptionName AS roleName
		FROM group_grp
		LEFT JOIN person2group2role_p2g2r ON p2g2r_grp_ID = grp_ID
		LEFT JOIN list_lst role ON lst_OptionID = p2g2r_rle_ID AND lst_ID = grp_RoleListID
		WHERE person2group2role_p2g2r.p2g2r_per_ID = " . $iPersonID . "
		ORDER BY grp_Name";
$rsAssignedGroups = RunQuery($sSQL);

// Get all the Groups
$sSQL = "SELECT grp_ID, grp_Name FROM group_grp ORDER BY grp_Name";
$rsGroups = RunQuery($sSQL);

// Get the volunteer opportunities this Person is assigned to
$sSQL = "SELECT vol_ID, vol_Name, vol_Description FROM volunteeropportunity_vol
		LEFT JOIN person2volunteeropp_p2vo ON p2vo_vol_ID = vol_ID
		WHERE person2volunteeropp_p2vo.p2vo_per_ID = " . $iPersonID;
$rsAssignedVolunteerOpps = RunQuery($sSQL);

// Get all the volunteer opportunities
$sSQL = "SELECT vol_ID, vol_Name FROM volunteeropportunity_vol ORDER BY vol_Name";
$rsVolunteerOpps = RunQuery($sSQL);

// Get the Properties assigned to this Person
$sSQL = "SELECT pro_Name, pro_ID, pro_Prompt, r2p_Value, prt_Name, pro_prt_ID
		FROM record2property_r2p
		LEFT JOIN property_pro ON pro_ID = r2p_pro_ID
		LEFT JOIN propertytype_prt ON propertytype_prt.prt_ID = property_pro.pro_prt_ID
		WHERE pro_Class = 'p' AND r2p_record_ID = " . $iPersonID .
		" ORDER BY prt_Name, pro_Name";
$rsAssignedProperties = RunQuery($sSQL);

// Get all the properties
$sSQL = "SELECT * FROM property_pro WHERE pro_Class = 'p' ORDER BY pro_Name";
$rsProperties = RunQuery($sSQL);

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
	extract ($aRow);
	$aSecurityType[$lst_OptionID] = $lst_OptionName;
}


$dBirthDate = FormatBirthDate($per_BirthYear, $per_BirthMonth, $per_BirthDay,"-",$per_Flags);

$sFamilyInfoBegin = "<span style=\"color: red;\">";
$sFamilyInfoEnd = "</span>";

// Assign the values locally, after selecting whether to display the family or person information

SelectWhichAddress($sAddress1, $sAddress2, $per_Address1, $per_Address2, $fam_Address1, $fam_Address2, True);
$sCity = SelectWhichInfo($per_City, $fam_City, True);
$sState = SelectWhichInfo($per_State, $fam_State, True);
$sZip = SelectWhichInfo($per_Zip, $fam_Zip, True);
$sCountry = SelectWhichInfo($per_Country, $fam_Country, True);
$sPhoneCountry = SelectWhichInfo($per_Country, $fam_Country, False);
$sHomePhone = SelectWhichInfo(ExpandPhoneNumber($per_HomePhone,$sPhoneCountry,$dummy), ExpandPhoneNumber($fam_HomePhone,$fam_Country,$dummy), True);
$sWorkPhone = SelectWhichInfo(ExpandPhoneNumber($per_WorkPhone,$sPhoneCountry,$dummy), ExpandPhoneNumber($fam_WorkPhone,$fam_Country,$dummy), True);
$sCellPhone = SelectWhichInfo(ExpandPhoneNumber($per_CellPhone,$sPhoneCountry,$dummy), ExpandPhoneNumber($fam_CellPhone,$fam_Country,$dummy), True);
$sEmail = SelectWhichInfo($per_Email, $fam_Email, True);
$sUnformattedEmail = SelectWhichInfo($per_Email, $fam_Email, False);

if ($per_Envelope > 0)
	$sEnvelope = $per_Envelope;
else
	$sEnvelope = gettext("Not assigned");

Switch($per_Flags){
	Case"0"; $statuswarga="Terdaftar" ; Break;
	Case"1"; $statuswarga="Pindah" ; Break;
	Case"2"; $statuswarga="Meninggal" ; Break;
}


// Set the page title and include HTML header
$sPageTitle = gettext("Detail Jemaat - No.Induk: $per_ID$per_fam_ID$per_Gender$per_fmr_ID - Status : $statuswarga");
require "Include/Header.php";

gettext("Nomor Induk Jemaat : $per_ID$per_fam_ID$per_Gender$per_fmr_ID # format : 4 digit ID jemaat, 4 digit ID keluarga, 1 digit Gender, 1 digit Kode Keluarga");

$iTableSpacerWidth = 10;

$bOkToEdit = ($_SESSION['bEditRecords'] ||
			  ($_SESSION['bEditSelf'] && $per_ID==$_SESSION['iUserID']) ||
			  ($_SESSION['bEditSelf'] && $per_fam_ID==$_SESSION['iFamID'])
			 );
if ($previous_link_text) {
	echo "$previous_link_text | ";
}

//if ($bOkToEdit) {
//	echo "<a class=\"SmallText\" href=\"PersonEditor.php?PersonID=" . $per_ID .
//		 "\"><img border=0 src=\"Images/Icons/ico_edit.png\"  width=\"30\" height=\"30\"   >" . gettext("Edit") . "</a> | ";
//}

//if ($_SESSION['bDeleteRecords']) { echo "<a class=\"SmallText\" href=\"SelectDelete.php?mode=person&PersonID=" . $per_ID . "\"><img border=0 src=\"Images/Icons/ico_del.png\"   width=\"30\" height=\"30\"   > " . gettext("Hapus") . "</a> | " ; }
?>
<?php /**  <a href="VCardCreate.php?PersonID=<?php echo $per_ID; ?>" class="SmallText"><img border=0 src="Images/Icons/ico_vcard.png"  width=\"30\" height=\"30\"  > <?php echo gettext("vCard"); ?></a> |
**/ ?>

<a href="PrintViewMove.php?PersonID=<?php echo $per_ID; ?>" class="SmallText" target="_blank"><img border=0 src="Images/Icons/ico_print.png"  width=\"30\" height=\"30\"  >  <?php echo gettext("Cetak"); ?></a>
<?php /** | <a href="PersonViewMove.php?PersonID=<?php echo $per_ID; ?>&AddToPeopleCart=<?php echo $per_ID; ?>" class="SmallText"><?php echo gettext("Cart"); ?></a>
**/ ?>
<?php
//if ($_SESSION['bAdmin'])
//{
//	$sSQL = "SELECT usr_per_ID FROM user_usr WHERE usr_per_ID = " . $per_ID;
//	if (mysql_num_rows(RunQuery($sSQL)) == 0)
//		echo " | <a class=\"SmallText\" href=\"UserEditor.php?NewPersonID=" . $per_ID . "\"><img border=0 src=\"Images/Icons/ico_eduser.png\"  width=\"30\" height=\"30\"  > " . gettext("Operator Baru") . "</a>" ;
//	else
//		echo " | <a class=\"SmallText\" href=\"UserEditor.php?PersonID=" . $per_ID . "\"><img border=0 src=\"Images/Icons/ico_eduser.png\"  width=\"30\" height=\"30\"  > " . gettext("Operator") . "</a>" ;
//}
//
if ($next_link_text) {
	echo " | $next_link_text";
}
echo " | <a class=\"SmallText\" href=\"SelectListMove.php?mode=person\"><img border=0 src=\"Images/Icons/ico_list.png\"  width=\"30\" height=\"30\"  >" .
gettext("Daftar Jemaat") . "</a> ";

?>

<br><br>
<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>
<td width="25%" valign="top" align="center" style="background-color: rgb(255, 255, 0);">
	<div class="LightShadedBox">
	<?php
		echo "<font size=\"3\"><b>";
		// Kalo single field middle sama last nya dihide
		//echo FormatFullName($per_Title, $per_FirstName, $per_MiddleName, $per_LastName, $per_Suffix, 0);
		echo $per_Title, $per_FirstName, $per_Suffix;

		echo "</font></b><br>";

		if ($fam_ID != "") {
			echo "<font size=\"2\">";
			if ($sFamRole != "") { echo $sFamRole; } else { echo gettext("Anggota"); }
			echo gettext(" dari <br> Kelg.") . " <a href=\"FamilyViewMove.php?FamilyID=" . $fam_ID . "\">" . $fam_Name . "<img border=0 src=\"Images/Icons/ico_kel.png\" align=\"middle\"  width=\"30\" height=\"30\"  ></a>" . gettext(" ") . "</font><br><br>";
		}
		else
			echo gettext("(Tidak Ada data Keluarga)") . "<br><br>";

		echo "<div class=\"TinyShadedBox\">";
			echo "<font size=\"2\">";
			if ($sAddress1 != "") { echo $sAddress1 . "<br>"; }
			if ($sAddress2 != "") { echo $sAddress2 . "<br>"; }
			if ($sCity != "") { echo $sCity . ", "; }
			if ($sState != "") { echo $sState; }
			if ($sZip != "") { echo " " . $sZip; }
			if ($sCountry != "") {echo "<br>" . $sCountry; }
			echo "</font>";
		echo "</div>";

		// Strip tags in case they were added for family inherited data
		$sAddress1 = strip_tags($sAddress1);
		$sCity = strip_tags($sCity);
		$sState = strip_tags($sState);
		$sCountry = strip_tags($sCountry);

		//Show link to mapquest
		if ($sAddress1 != "" && $sCity != "" && $sState != "")
		{
			if ($sCountry == "United States") {
				$sMQcountry = "";
				$bShowMQLink = true;
			}
			elseif ($sCountry == "Canada") {
				$sMQcountry = "country=CA&";
				$bShowMQLink = true;
			}
			else
				$bShowMQLink = false;
		}

		if ($bShowMQLink)
			echo "<div align=\"right\"><a class=\"SmallText\" target=\"_blank\" href=\"http://www.mapquest.com/maps/map.adp?" .$sMQcountry . "city=" . urlencode($sCity) . "&state=" . $sState . "&address=" . urlencode($sAddress1) . "\">" . gettext("Lihat Peta") . "</a></div>";

		echo "<br>";


		// Display photo or upload from file
		$photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
        if (file_exists($photoFile))
        {
            echo '<a target="_blank" href="Images/Person/' . $iPersonID . '.jpg">';
            echo '<img border="1" src="'.$photoFile.'"  width=\"128\" height=\"150\"  ></a>';
            if ($bOkToEdit) {

                }
        } else {
            // Some old / M$ browsers can't handle PNG's correctly.
            if ($bDefectiveBrowser)
                echo '<img border="0" src="Images/NoPhoto.gif" width=\"128\" height=\"150\"  ><br><br><br>';
            else
                echo '<img border="0" src="Images/NoPhoto.png" width=\"128\" height=\"150\"  ><br><br><br>';

            if ($bOkToEdit) {
                if (isset($PhotoError))
                    echo '<span style="color: red;">' . $PhotoError . '</span><br>';


            }
        }


        		echo "<br>";

				// Upload Surat Baptis Anak
				if ( isset($_POST["UploadSuratBaptisAnak"]) && ($_SESSION['bAddRecords'] || $bOkToEdit) ) {
					if ($_FILES['SuratBaptisAnak']['name'] == "") {
						$SuratBaptisAnakError = gettext("Tidak Ada SuratBaptisAnak yang di upload.");
					} elseif ($_FILES['SuratBaptisAnak']['type'] != "image/pjpeg" && $_FILES['SuratBaptisAnak']['type'] != "image/jpeg") {
						$SuratBaptisAnakError = gettext("Hanya Format JPEG yang bisa di upload.");
					} else {
						// Create the thumbnail used by PersonViewMove

		            chmod ($_FILES['SuratBaptisAnak']['tmp_name'], 0777);

						$srcImage=imagecreatefromjpeg($_FILES['SuratBaptisAnak']['tmp_name']);
						$src_w=imageSX($srcImage);
		    			$src_h=imageSY($srcImage);

						// Calculate thumbnail's height and width (a "maxpect" algorithm)
						$dst_max_w = 150;
						$dst_max_h = 300;
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
						imagejpeg($dstImage, "Images/BaptisAnak/thumbnails/SBA" . $iPersonID . ".jpg");
						imagedestroy($dstImage);
		    			imagedestroy($srcImage);
						move_uploaded_file($_FILES['SuratBaptisAnak']['tmp_name'], "Images/BaptisAnak/SBA" . $iPersonID . ".jpg");
					}
				} elseif (isset($_POST["DeleteSuratBaptisAnak"]) && $_SESSION['bDeleteRecords']) {
					unlink("Images/BaptisAnak/SBA" . $iPersonID . ".jpg");
					unlink("Images/BaptisAnak/thumbnails/SBA" . $iPersonID . ".jpg");
				}

				// Display SuratBaptisAnak or upload from file
				$SuratBaptisAnakFile = "Images/BaptisAnak/thumbnails/SBA" . $iPersonID . ".jpg";
		        if (file_exists($SuratBaptisAnakFile))
		        {
		            echo '<a target="_blank" href="Images/BaptisAnak/SBA' . $iPersonID . '.jpg"  >';
		            echo '<img border="1" src="'.$SuratBaptisAnakFile.'"  width=\"60\" height=\"30\"  ></a>';
		            if ($bOkToEdit) {
		                echo '
		                    <form method="post"
		                    action="PersonViewMove.php?PersonID=' . $iPersonID . '">
		                    <br>
		                    <input type="submit" class="icTinyButton"
		                    value="' . gettext("Hapus SuratBaptisAnak") . '" name="DeleteSuratBaptisAnak">
		                    </form>';
		                }
		        } else {
		            // Some old / M$ browsers can't handle PNG's correctly.
		            if ($bDefectiveBrowser)
		                echo '<img border="0" src="Images/NoSuratBaptisAnak.gif"  width=\"60\" height=\"30\"  ><br><br><br>';
		            else
		                echo '<img border="0" src="Images/NoSuratBaptisAnak.png"  width=\"60\" height=\"30\"  ><br><br><br>';

		            if ($bOkToEdit) {
		                if (isset($SuratBaptisAnakError))
		                    echo '<span style="color: red;">' . $SuratBaptisAnakError . '</span><br>';

		                echo '
		                    <form method="post"
		                    action="PersonViewMove.php?PersonID=' . $iPersonID . '"
		                    enctype="multipart/form-data">
		                    <input class="icTinyButton" type="file" name="SuratBaptisAnak">
		                    <input type="submit" class="icTinyButton"
		                    value="' . gettext("SuratBaptisAnak") . '" name="UploadSuratBaptisAnak">
		                    </form>';
		            }
        }

        		echo "<br>";

				// Upload Surat Baptis Dewasa
				if ( isset($_POST["UploadSuratBaptisDewasa"]) && ($_SESSION['bAddRecords'] || $bOkToEdit) ) {
					if ($_FILES['SuratBaptisDewasa']['name'] == "") {
						$SuratBaptisDewasaError = gettext("Tidak Ada SuratBaptisDewasa yang di upload.");
					} elseif ($_FILES['SuratBaptisDewasa']['type'] != "image/pjpeg" && $_FILES['SuratBaptisDewasa']['type'] != "image/jpeg") {
						$SuratBaptisDewasaError = gettext("Hanya Format JPEG yang bisa di upload.");
					} else {
						// Create the thumbnail used by PersonViewMove

		            chmod ($_FILES['SuratBaptisDewasa']['tmp_name'], 0777);

						$srcImage=imagecreatefromjpeg($_FILES['SuratBaptisDewasa']['tmp_name']);
						$src_w=imageSX($srcImage);
		    			$src_h=imageSY($srcImage);

						// Calculate thumbnail's height and width (a "maxpect" algorithm)
						$dst_max_w = 150;
						$dst_max_h = 300;
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
						imagejpeg($dstImage, "Images/BaptisDewasa/thumbnails/SBD" . $iPersonID . ".jpg");
						imagedestroy($dstImage);
		    			imagedestroy($srcImage);
						move_uploaded_file($_FILES['SuratBaptisDewasa']['tmp_name'], "Images/BaptisDewasa/SBD" . $iPersonID . ".jpg");
					}
				} elseif (isset($_POST["DeleteSuratBaptisDewasa"]) && $_SESSION['bDeleteRecords']) {
					unlink("Images/BaptisDewasa/SBD" . $iPersonID . ".jpg");
					unlink("Images/BaptisDewasa/thumbnails/SBD" . $iPersonID . ".jpg");
				}

				// Display SuratBaptisDewasa or upload from file
				$SuratBaptisDewasaFile = "Images/BaptisDewasa/thumbnails/SBD" . $iPersonID . ".jpg";
		        if (file_exists($SuratBaptisDewasaFile))
		        {
		            echo '<a target="_blank" href="Images/BaptisDewasa/SBD' . $iPersonID . '.jpg"  >';
		            echo '<img border="1" src="'.$SuratBaptisDewasaFile.'"  width=\"60\" height=\"30\"  ></a>';
		            if ($bOkToEdit) {
		                echo '
		                    <form method="post"
		                    action="PersonViewMove.php?PersonID=' . $iPersonID . '">
		                    <br>
		                    <input type="submit" class="icTinyButton"
		                    value="' . gettext("Hapus SuratBaptisDewasa") . '" name="DeleteSuratBaptisDewasa">
		                    </form>';
		                }
		        } else {
		            // Some old / M$ browsers can't handle PNG's correctly.
		            if ($bDefectiveBrowser)
		                echo '<img border="0" src="Images/NoSuratBaptisDewasa.gif"  width=\"60\" height=\"30\"  ><br><br><br>';
		            else
		                echo '<img border="0" src="Images/NoSuratBaptisDewasa.png"  width=\"60\" height=\"30\"  ><br><br><br>';

		            if ($bOkToEdit) {
		                if (isset($SuratBaptisDewasaError))
		                    echo '<span style="color: red;">' . $SuratBaptisDewasaError . '</span><br>';

		                echo '
		                    <form method="post"
		                    action="PersonViewMove.php?PersonID=' . $iPersonID . '"
		                    enctype="multipart/form-data">
		                    <input class="icTinyButton" type="file" name="SuratBaptisDewasa">
		                    <input type="submit" class="icTinyButton"
		                    value="' . gettext("SuratBaptisDewasa") . '" name="UploadSuratBaptisDewasa">
		                    </form>';
		            }
        }


        		echo "<br>";

				// Upload Surat Baptis Sidhi
				if ( isset($_POST["UploadSuratSidhi"]) && ($_SESSION['bAddRecords'] || $bOkToEdit) ) {
					if ($_FILES['SuratSidhi']['name'] == "") {
						$SuratSidhiError = gettext("Tidak Ada SuratSidhi yang di upload.");
					} elseif ($_FILES['SuratSidhi']['type'] != "image/pjpeg" && $_FILES['SuratSidhi']['type'] != "image/jpeg") {
						$SuratSidhiError = gettext("Hanya Format JPEG yang bisa di upload.");
					} else {
						// Create the thumbnail used by PersonViewMove

		            chmod ($_FILES['SuratSidhi']['tmp_name'], 0777);

						$srcImage=imagecreatefromjpeg($_FILES['SuratSidhi']['tmp_name']);
						$src_w=imageSX($srcImage);
		    			$src_h=imageSY($srcImage);

						// Calculate thumbnail's height and width (a "maxpect" algorithm)
						$dst_max_w = 150;
						$dst_max_h = 300;
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
						imagejpeg($dstImage, "Images/Sidhi/thumbnails/SS" . $iPersonID . ".jpg");
						imagedestroy($dstImage);
		    			imagedestroy($srcImage);
						move_uploaded_file($_FILES['SuratSidhi']['tmp_name'], "Images/Sidhi/SS" . $iPersonID . ".jpg");
					}
				} elseif (isset($_POST["DeleteSuratSidhi"]) && $_SESSION['bDeleteRecords']) {
					unlink("Images/Sidhi/SS" . $iPersonID . ".jpg");
					unlink("Images/Sidhi/thumbnails/SS" . $iPersonID . ".jpg");
				}

				// Display SuratSidhi or upload from file
				$SuratSidhiFile = "Images/Sidhi/thumbnails/SS" . $iPersonID . ".jpg";
		        if (file_exists($SuratSidhiFile))
		        {
		            echo '<a target="_blank" href="Images/Sidhi/SS' . $iPersonID . '.jpg"  >';
		            echo '<img border="1" src="'.$SuratSidhiFile.'"  width=\"60\" height=\"30\"  ></a>';
		            if ($bOkToEdit) {
		                echo '
		                    <form method="post"
		                    action="PersonViewMove.php?PersonID=' . $iPersonID . '">
		                    <br>
		                    <input type="submit" class="icTinyButton"
		                    value="' . gettext("Hapus SuratSidhi") . '" name="DeleteSuratSidhi">
		                    </form>';
		                }
		        } else {
		            // Some old / M$ browsers can't handle PNG's correctly.
		            if ($bDefectiveBrowser)
		                echo '<img border="0" src="Images/NoSuratSidhi.gif"  width=\"60\" height=\"30\"  ><br><br><br>';
		            else
		                echo '<img border="0" src="Images/NoSuratSidhi.png"  width=\"60\" height=\"30\"  ><br><br><br>';

		            if ($bOkToEdit) {
		                if (isset($SuratSidhiError))
		                    echo '<span style="color: red;">' . $SuratSidhiError . '</span><br>';

		                echo '
		                    <form method="post"
		                    action="PersonViewMove.php?PersonID=' . $iPersonID . '"
		                    enctype="multipart/form-data">
		                    <input class="icTinyButton" type="file" name="SuratSidhi">
		                    <input type="submit" class="icTinyButton"
		                    value="' . gettext("SuratSidhi") . '" name="UploadSuratSidhi">
		                    </form>';
		            }
        }




        ?>
	</div>
</td>

<td width="75%" valign="top" align="left">

	<b><?php echo gettext("Informasi Umum:  ")  ; ?></b>
	<br>
	<b><?php echo gettext("Nomor Induk Jemaat : $per_ID$per_fam_ID$per_Gender$per_fmr_ID # format : 4 digit ID jemaat, 4 digit ID keluarga, 1 digit Gender, 1 digit Kode Keluarga"); ?> </b>
	<div class="LightShadedBox">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td align="center">
			<table cellspacing="1" cellpadding="0" border="0" >

			<tr>
							<td class="TinyLabelColumn"><?php echo gettext("Kelompok"); ?></td>

							<td class="TinyTextColumn"><?php echo $per_WorkPhone; ?></td>
			</tr>

			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Jenis Kelamin:"); ?></td>
				<td class="TinyTextColumn">
				<?php
					switch ($per_Gender)
					{
					case 1:
						echo gettext("Laki-laki");
						break;
					case 2:
						echo gettext("Perempuan");
						break;
					}
				?>
				</td>
			</tr>


			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Tempat,Tanggal Lahir:"); ?></td>
				<td class="TinyTextColumn"><?php echo "$per_WorkEmail  -  $dBirthDate"; ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Umur:"); ?></td>
				<td class="TinyTextColumn"><?php PrintAge($per_BirthMonth,$per_BirthDay,$per_BirthYear,$per_Flags); ?></td>
			</tr>
<?php if (!$bHideFriendDate) { /* Friend Date can be hidden - General Settings */ ?>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Friend Date:"); ?></td>
				<td class="TinyTextColumn"><?php echo FormatDate($per_FriendDate,false); ?></td>
			</tr>
<?php } ?>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Tanggal Daftar:"); ?></td>
				<td class="TinyTextColumn"><?php echo FormatDate($per_MembershipDate,false); ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Klasifikasi:"); ?></td>
				<td class="TinyTextColumn"><?php echo $sClassName; ?></td>
			</tr>
			<?php
				// Display the left-side custom fields
				while ($Row = mysql_fetch_array($rsLeftCustomFields)) {
					extract($Row);
					if (($aSecurityType[$custom_FieldSec] == 'bAll') or ($_SESSION[$aSecurityType[$custom_FieldSec]]))
					{
						$currentData = trim($aCustomData[$custom_Field]);
						if ($type_ID == 11) $custom_Special = $sPhoneCountry;
						echo "<tr><td class=\"TinyLabelColumn\">" . $custom_Name . "</td>";
						echo "<td><div  class='TDscroll' id='customrow'>";
						echo nl2br((displayCustomField($type_ID, $currentData, $custom_Special)));
						echo "</div></td></tr>";
//						echo "<td class=\"TinyTextColumn\">" . displayCustomField($type_ID, $currentData, $custom_Special) . "</td></tr>";
					}
				}
			?>
			</table>
		</td>
		<td align="center" >
			<table cellspacing="1" cellpadding="0" border="0" >
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Telp. Rumah:"); ?></td>
				<td class="TinyTextColumn"><?php echo $sHomePhone; ?></td>
			</tr>

			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Handphone:"); ?></td>
				<td class="TinyTextColumn"><?php echo $sCellPhone; ?></td>
			</tr>
			<tr>
				<td class="TinyLabelColumn"><?php echo gettext("Email:"); ?></td>
				<td class="TinyTextColumn"><?php if ($sEmail != "") { echo "<a href=\"mailto:" . $sUnformattedEmail . "\">" . $sEmail . "</a>"; } ?></td>
			</tr>

			<?php
				// Display the right-side custom fields
				while ($Row = mysql_fetch_array($rsRightCustomFields)) {
					extract($Row);
					$currentData = trim($aCustomData[$custom_Field]);
					if ($type_ID == 11) $custom_Special = $sPhoneCountry;
					echo "<tr><td class=\"TinyLabelColumn\">" . $custom_Name . "</td>";
					echo "<td><div  class='TDscroll' id='customrow'>";
					echo nl2br((displayCustomField($type_ID, $currentData, $custom_Special)));
					echo "</div></td></tr>";
//					echo "<td class=\"TinyTextColumn\">" . displayCustomField($type_ID, $currentData, $custom_Special) . "</td></tr>";
				}
			?>
			</table>
		</td>
	</tr>
	</table>
	</div>
	<br>
	<b><?php echo gettext("Keadaan Khusus:"); ?></b>

	<?php

	$sAssignedProperties = ",";

	//Was anything returned?
	if (mysql_num_rows($rsAssignedProperties) == 0)
	{
		echo "<p align\"center\">" . gettext("Tidak Ada Data.") . "</p>";
	}
	else
	{
		//Yes, start the table
		echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
		echo "<tr class=\"TableHeader\">";
		echo "<td width=\"10%\" valign=\"top\"><b>" . gettext("Type") . "</b>";
		echo "<td width=\"15%\" valign=\"top\"><b>" . gettext("Name") . "</b>";
		echo "<td valign=\"top\"><b>" . gettext("Value") . "</b></td>";

		if ($bOkToEdit)
		{
			echo "<td valign=\"top\"><b>" . gettext("Edit") . "</b></td>";
			echo "<td valign=\"top\"><b>" . gettext("Hapus") . "</b></td>";
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

			echo "<td valign=\"center\">" . $pro_Name . "&nbsp;</td>";
			echo "<td valign=\"center\">" . $r2p_Value . "&nbsp;</td>";

			if ($bOkToEdit)
			{
				if (strlen($pro_Prompt) > 0)
				{
					echo "<td valign=\"center\"><a href=\"PropertyAssign.php?PersonID=" . $iPersonID . "&PropertyID=" . $pro_ID . "\">" . gettext("Edit") . "</a></td>";
				}
				else
				{
					echo "<td>&nbsp;</td>";
				}
				echo "<td valign=\"center\"><a href=\"PropertyUnassign.php?PersonID=" . $iPersonID . "&PropertyID=" . $pro_ID . "\">" . gettext("Remove") . "</a></td>";
			}
			echo "</tr>";

			//Alternate the row style
			$sRowClass = AlternateRowStyle($sRowClass);

			$sAssignedProperties .= $pro_ID . ",";
		}
		echo "</table>";
	}

	?>




	<b><?php echo gettext("Pelayanan yang diikuti:"); ?></b>

	<?php

	//Initialize row shading
	$sRowClass = "RowColorA";

	$sAssignedVolunteerOpps = ",";

	//Was anything returned?
	if (mysql_num_rows($rsAssignedVolunteerOpps) == 0)
	{
		echo "<p align=\"left\">" . gettext("Tidak ada data.") . "</p>";
	}
	else
	{
		echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
		echo "<tr class=\"TableHeader\">";
		echo "<td>" . gettext("Name") . "</td>";
		echo "<td>" . gettext("Description") . "</td>";
		if ($_SESSION['bEditRecords']) {
			echo "<td width=\"10%\">" . gettext("Remove") . "</td>";
		}
		echo "</tr>";

		// Loop through the rows
		while ($aRow = mysql_fetch_array($rsAssignedVolunteerOpps))
		{
			extract($aRow);

			// Alternate the row style
			$sRowClass = AlternateRowStyle($sRowClass);

			echo "<tr class=\"" . $sRowClass . "\">";
			echo "<td>" . $vol_Name . "</a></td>";
			echo "<td>" . $vol_Description . "</a></td>";

			if ($_SESSION['bEditRecords']) echo "<td><a class=\"SmallText\" href=\"PersonViewMove.php?PersonID=" . $per_ID . "&RemoveVO=" . $vol_ID . "\">" . gettext("Remove") . "</a></td>";

			echo "</tr>";

			// NOTE: this method is crude.  Need to replace this with use of an array.
			$sAssignedVolunteerOpps .= $vol_ID . ",";
		}
		echo "</table>";
	}
	?>

</td>
</tr>
</table>

</td>
</tr>
</table>



<p class="SmallText">
	<span style="color: red;"><?php echo gettext("Tulisan MERAH"); ?></span> <?php echo gettext("Berarti ada relasi data dengan data Keluarga."); ?>
</p>

<p class="SmallText"><i>
	<?php echo gettext("Entered:"); ?> <?php echo FormatDate($per_DateEntered,true); ?> <?php echo gettext("by"); ?> <?php echo $EnteredFirstName . " " . $EnteredLastName; ?>
<?php

	if (strlen($per_DateLastEdited) > 0)
	{
		?>
			<br>
			<?php echo gettext("Last edited:") . ' ' . FormatDate($per_DateLastEdited,true) . ' ' . gettext("by") . ' ' . $EditedFirstName . " " . $EditedLastName ?>
		</i></p>
		<?php
	}
	?>

</p>


<?php if ($_SESSION['bNotes']) { ?>
<p>
	<b><?php echo gettext("Catatan:"); ?></b>
</p>

<p>
<?php /**	<a class="SmallText" href="WhyCameEditor.php?PersonID=<?php echo $per_ID ?>"><?php echo gettext("Edit \"Why Came\" Notes"); ?></a></font>
**/ ?>	<br>
	<a class="SmallText" href="NoteEditor.php?PersonID=<?php echo $per_ID ?>"><?php echo gettext("Tambah Catatan untuk Data ini"); ?></a></font>
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
	<span class="SmallText"><?php echo gettext("Entered:") . ' ' . FormatDate($nte_DateEntered,True) . ' ' . gettext("by") . ' ' . $EnteredFirstName . " " . $EnteredLastName ?></span>
	<br>
	<?php

	if (strlen($nte_DateLastEdited))
	{ ?>
		<span class="SmallText"><?php echo gettext("Last Edited:") . ' ' . FormatDate($nte_DateLastEdited,True) . ' ' . gettext("by") . ' ' . $EditedFirstName . " " . $EditedLastName ?></span>
		<br>
	<?php
	}
	if ($_SESSION['bNotes']) { ?><a class="SmallText" href="NoteEditor.php?PersonID=<?php echo $iPersonID ?>&NoteID=<?php echo $nte_ID ?>"><?php echo gettext("Edit This Note"); ?></a>&nbsp;|&nbsp;<?php }
	if ($_SESSION['bNotes']) { ?><a class="SmallText" href="NoteDelete.php?NoteID=<?php echo $nte_ID ?>"><?php echo gettext("Delete This Note"); ?></a> <?php }

}
?>
<?php }

require "Include/Footer.php";
?>
