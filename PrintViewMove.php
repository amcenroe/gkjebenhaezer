<?php
/*******************************************************************************
 *
 *  filename    : PrintView.php
 *  last change : 2003-01-29
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

// Get this person
$sSQL = "SELECT a.*, family_fam.*, cls.lst_OptionName AS sClassName, fmr.lst_OptionName AS sFamRole, b.per_FirstName AS EnteredFirstName,
				b.Per_LastName AS EnteredLastName, c.per_FirstName AS EditedFirstName, c.per_LastName AS EditedLastName
			FROM person_per a
			LEFT JOIN family_fam ON a.per_fam_ID = family_fam.fam_ID
			LEFT JOIN list_lst cls ON a.per_cls_ID = cls.lst_OptionID AND cls.lst_ID = 1
			LEFT JOIN list_lst fmr ON a.per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
			LEFT JOIN person_per b ON a.per_EnteredBy = b.per_ID
			LEFT JOIN person_per c ON a.per_EditedBy = c.per_ID
			WHERE a.per_ID = " . $iPersonID;
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));

// Save for later
$sWorkEmail = trim($per_WorkEmail);

// Get the list of custom person fields
$sSQL = "SELECT person_custom_master.* FROM person_custom_master ORDER BY custom_Order";
$rsCustomFields = RunQuery($sSQL);
$numCustomFields = mysql_num_rows($rsCustomFields);

// Get the actual custom field data
$sSQL = "SELECT * FROM person_custom WHERE per_ID = " . $iPersonID;
$rsCustomData = RunQuery($sSQL);
$aCustomData = mysql_fetch_array($rsCustomData, MYSQL_BOTH);

// Get the notes for this person
$sSQL = "SELECT nte_Private, nte_ID, nte_Text, nte_DateEntered, nte_EnteredBy, nte_DateLastEdited, nte_EditedBy, a.per_FirstName AS EnteredFirstName, a.Per_LastName AS EnteredLastName, b.per_FirstName AS EditedFirstName, b.per_LastName AS EditedLastName ";
$sSQL = $sSQL . "FROM note_nte ";
$sSQL = $sSQL . "LEFT JOIN person_per a ON nte_EnteredBy = a.per_ID ";
$sSQL = $sSQL . "LEFT JOIN person_per b ON nte_EditedBy = b.per_ID ";
$sSQL = $sSQL . "WHERE nte_per_ID = " . $iPersonID . " ";
$sSQL = $sSQL . "AND (nte_Private = 0 OR nte_Private = " . $_SESSION['iUserID'] . ")";
$rsNotes = RunQuery($sSQL);

// Get the Groups this Person is assigned to
$sSQL = "SELECT grp_ID, grp_Name, grp_hasSpecialProps, role.lst_OptionName AS roleName
		FROM group_grp
		LEFT JOIN person2group2role_p2g2r ON p2g2r_grp_ID = grp_ID
		LEFT JOIN list_lst role ON lst_OptionID = p2g2r_rle_ID AND lst_ID = grp_RoleListID
		WHERE person2group2role_p2g2r.p2g2r_per_ID = " . $iPersonID . "
		ORDER BY grp_Name";
$rsAssignedGroups = RunQuery($sSQL);

// Get the Properties assigned to this Person
$sSQL = "SELECT pro_Name, pro_ID, pro_Prompt, r2p_Value, prt_Name, pro_prt_ID
		FROM record2property_r2p
		LEFT JOIN property_pro ON pro_ID = r2p_pro_ID
		LEFT JOIN propertytype_prt ON propertytype_prt.prt_ID = property_pro.pro_prt_ID
		WHERE pro_Class = 'p' AND r2p_record_ID = " . $iPersonID .
		" ORDER BY prt_Name, pro_Name";
$rsAssignedProperties = RunQuery($sSQL);

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
	extract ($aRow);
	$aSecurityType[$lst_OptionID] = $lst_OptionName;
}

// Format the BirthDate
$dBirthDate = FormatBirthDate($per_BirthYear, $per_BirthMonth, $per_BirthDay, "/", $per_Flags);
if ($per_BirthMonth > 0 && $per_BirthDay > 0)
{
	$dBirthDate = " , " . $per_BirthDay . " / " . $per_BirthMonth;
	if (is_numeric($per_BirthYear))
	{
		$dBirthDate .= " / " . $per_BirthYear;
	}
}
elseif (is_numeric($per_BirthYear))
{
	$dBirthDate = $per_BirthYear;
}
else
{
	$dBirthDate = "";
}

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

// Set the page title and include HTML header
//$sPageTitle = gettext("Informasi Jemaat (Versi Cetak)");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";
?>




<table border="0"  width="750" cellspacing=0 cellpadding=0>>

  <tr><!-- Row 2 -->
     <td valign=top align=left>
     <img border="0" src="gkj_logo.jpg" width="120" >
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b><font size="5">GEREJA KRISTEN JAWA BEKASI </font></b><BR>
	 <b><font size="5">Wilayah Timur </font></b><br>
	 <b><font size="2">Surat Pindah</font></b><br>
	 <hr>
	 <b>Nomor Induk Jemaat : <?php echo $per_ID,$per_fam_ID,$per_Gender,$per_fmr_ID ?>
	 		-- Kelompok :
	 		<?php

	 			$sAssignedGroups = ",";

	 			//Was anything returned?
	 			if (mysql_num_rows($rsAssignedGroups) == 0)
	 			{
	 				echo "<p align\"center\">" . gettext(" Null ") . "</p>";
	 			}
	 			else
	 			{
	 				//Loop through the rows
	 				while ($aRow = mysql_fetch_array($rsAssignedGroups))
	 				{
	 					extract($aRow);

	 					//Alternate the row style
	 					$sRowClass = AlternateRowStyle($sRowClass);

	 					echo " $grp_Name ";
	 				}
	 			}
	 	        ?>
	 		</b><br>
		Tanggal Daftar : <b><?php echo FormatDate($per_MembershipDate,false); ?></b>

	 </td><!-- Col 2 -->

     <td valign=top align=right >
     <?php

	 		// Display photo or upload from file
	 		$photoFile = "Images/Person/thumbnails/" . $iPersonID . ".jpg";
	         if (file_exists($photoFile))
	         {
	             echo '<a target="_blank" href="Images/Person/' . $iPersonID . '.jpg" >';
	             echo '<img border="1" src="'.$photoFile.'" width="100" ></a>';
	             if ($bOkToEdit) {
	                 echo '
	                     <form method="post"
	                     action="PersonView.php?PersonID=' . $iPersonID . '">
	                     <br>
	                     <input type="submit" class="icTinyButton"
	                     value="' . gettext("Delete Photo") . '" name="DeletePhoto">
	                     </form>';
	                 }
	         } else {
	             // Some old / M$ browsers can't handle PNG's correctly.
	             if ($bDefectiveBrowser)
	                 echo '<img border="0" src="Images/NoPhoto.gif"><br><br><br>';
	             else
	                 echo '<img border="0" src="Images/NoPhoto.png"><br><br><br>';

	             if ($bOkToEdit) {
	                 if (isset($PhotoError))
	                     echo '<span style="color: red;">' . $PhotoError . '</span><br>';

	                 echo '
	                     <form method="post"
	                     action="PersonView.php?PersonID=' . $iPersonID . '"
	                     enctype="multipart/form-data">
	                     <input class="icTinyButton" type="file" name="Photo">
	                     <input type="submit" class="icTinyButton"
	                     value="' . gettext("Upload Photo") . '" name="UploadPhoto">
	                     </form>';
	             }
	         }

		?>
     </td><!-- Col 3 -->
  </tr>

</table>

<table border="0"  width="750" cellspacing=0 cellpadding=0>>


  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u>IDENTITAS PRIBADI</u></b></font><br>
     <table border="0"  width="100%">
  		<?php
  		echo "<tr><td valign=top>Nama :</td><td><b><font size=\"2\">  " . $per_FirstName . " " . $per_MiddleName . " " . $per_LastName . "</font></b></td></tr>";
		echo "<tr><td valign=top>Alamat :</td> <td><b><font size=\"2\">";
		if ($sAddress1 != "") { echo $sAddress1 . "<br>"; }
		if ($sAddress2 != "") { echo $sAddress2 . "<br>"; }
		if ($sCity != "") { echo $sCity . ", "; }
		if ($sState != "") { echo $sState; }
		if ($sZip != "") { echo " " . $sZip; }
		if ($sCountry != "") {echo "<br>" . $sCountry; }
		echo "</font></b></td><br>";
		echo "<tr><td valign=top>Telepon   :</td> <td><b><font size=\"2\"></b>   Rumah : <b> " . $sHomePhone. "</b>,  Handphone : <b>" . $sCellPhone. " </font></b></tr>";
		echo "<tr><td valign=top>Email   :</td> <td><b><font size=\"2\">  " . $sUnformattedEmail . "</font></b></tr>";
		echo "<tr><td valign=top>Tempat/Tanggal Lahir   :</td> <td><b><font size=\"2\">  " . $sWorkEmail , $dBirthDate . "</font></b></tr>";
		echo "<tr><td valign=top>Jenis Kelamin   :</td> <td><b><font size=\"2\">  ";
					switch (strtolower($per_Gender))
					{
						case 1:
						echo gettext("Laki-laki");
						break;
						case 2:
						echo gettext("Perempuan");
						break;
					}
				echo "</td></tr>";
		echo "<tr><td valign=top>Status Kewargaan   :</td> <td><b><font size=\"2\">  " .  $sClassName. "</font></b></td></tr>";


		$iFamilyID = $fam_ID;

		if ($fam_ID)
		{
			//Get the family members for this family
			$sSQL = "SELECT per_ID, per_Title, per_FirstName, per_MiddleName, per_LastName, per_Suffix, per_Gender,
				per_BirthMonth, per_BirthDay, per_BirthYear, cls.lst_OptionName AS sClassName,
				fmr.lst_OptionName AS sFamRole
				FROM person_per
				LEFT JOIN list_lst cls ON per_cls_ID = cls.lst_OptionID AND cls.lst_ID = 1
				LEFT JOIN list_lst fmr ON per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
				WHERE per_fam_ID = " . $iFamilyID . " ORDER BY fmr.lst_OptionSequence";
			$rsFamilyMembers = RunQuery($sSQL);
		}
		echo "<tr><td valign=top>Dari Keluarga   :</td> <td><b><font size=\"2\"> ";
			 if ($fam_Name != "") { echo $fam_Name; } else { echo " "; }
			 echo "</font></b></td></tr>";
		echo "<tr><td valign=top>Peran Dalam Keluarga   :</td> <td><b><font size=\"2\"> ";
			 if ($sFamRole != "") { echo $sFamRole; } else { echo " "; }
			 echo "</font></b></td></tr>";
		?>
	 </table>
	 </td><!-- Col 1 -->
  </tr>

</table>






<BR>

<table border="0" width=750 cellspacing="0" cellpadding="0">
<tr>
	<td width="50%" valign="top" align="left">
		<table cellspacing="1" cellpadding="4">



		</table>
	</td>

	<td width="50%" valign="top" align="left">
		<table cellspacing="1" cellpadding="4">




		</table>
	</td>

	<td width="0%" valign="top" align="left">
		<table cellspacing="1" cellpadding="4">




		</table>
    </td>
</tr>
</table>
<br>

<?php
require "Include/Footer-Short.php";
?>
