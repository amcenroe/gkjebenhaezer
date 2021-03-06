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
$sPageTitle = gettext("Informasi Jemaat : ID : $per_ID , $per_FirstName ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Individual View or Print";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>
<table border="0"  width="805" cellspacing=0 cellpadding=0>
<tr><td valign=top align=center>
<BR>
<table border="0"  width="800" cellspacing=0 cellpadding=0>
  <tr><!-- Row 2 -->
     <td valign=top align=left>
     <img border="0" src="gkj_logo.jpg" width="100" >
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo $sChurchName;?></font></b><BR>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);"><b style="font-family: Arial; color: rgb(0, 0, 102);">
	 <hr style="width: 100%; height: 2px;">
	 <b><font size="2">Data Warga Jemaat</font></b><br>
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
              echo '<img border="1" src="'.$photoFile.'" width="90" ></a>';
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
                  echo '<img border="0" src="Images/NoPhoto.gif">';
              else
                  echo '<img border="0" src="Images/NoPhoto.png">';

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

<table border="0"  width="800" cellspacing=0 cellpadding=0>
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
   $sSQL = "SELECT per_ID, per_Title, per_FirstName, per_MiddleName, per_LastName, per_Suffix, per_Gender, per_cls_ID,
    per_BirthMonth, per_BirthDay, per_BirthYear, cls.lst_OptionName AS sClassName,
    fmr.lst_OptionName AS sFamRole
    FROM person_per
    LEFT JOIN list_lst cls ON per_cls_ID = cls.lst_OptionID AND cls.lst_ID = 1
    LEFT JOIN list_lst fmr ON per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
    WHERE per_fam_ID = " . $iFamilyID . " ORDER BY fmr.lst_OptionSequence , per_BirthYear, per_BirthMonth";
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

<table border="0" width=800 cellspacing="0" cellpadding="0">
<tr>
 <td width="50%" valign="top" align="left">
  <table cellspacing="1" cellpadding="4">

    <?php
//       $numColumn3Fields = floor($numCustomFields / 3);
//       $leftOverFields = $numCustomFields - $numColumn3Fields;
//       $numColumn1Fields = ceil(($leftOverFields / 2)+4 );
//       $numColumn2Fields = $leftOverFields - $numColumn1Fields - 4;

       $numColumn3Fields = floor($numCustomFields / 2);
       $leftOverFields = $numCustomFields - $numColumn3Fields;
       $numColumn1Fields = ceil(($leftOverFields) );
       $numColumn2Fields = $leftOverFields - $numColumn1Fields;

 //      echo "    $numColumn3Fields , $numCustomFields , $leftOverFields , $numColumn1Fields , $numColumn2Fields  ";

       for($i = 1; $i <= $numColumn1Fields; $i++)
       {
        $Row = mysql_fetch_array($rsCustomFields);
        extract($Row);
        if (($aSecurityType[$custom_FieldSec] == 'bAll') or ($_SESSION[$aSecurityType[$custom_FieldSec]]))
        {
         $currentData = trim($aCustomData[$custom_Field]);
         if ($type_ID == 11) $custom_Special = $sCountry;
         echo "<tr><td class=\"LabelColumn\">" . $custom_Name . "</td><td width=\"" . $iTableSpacerWidth . "\"></td>";
         echo "<td class=\"TextColumn\">" . displayCustomField($type_ID, $currentData, $custom_Special) . "</td></tr>";
        }
       }
  ?>

  </table>
 </td>

 <td width="50%" valign="top" align="left">
  <table cellspacing="1" cellpadding="4">

  <?php
   for($i = 1; $i <= $numColumn2Fields; $i++)
   {
    $Row = mysql_fetch_array($rsCustomFields);
    extract($Row);
    $currentData = trim($aCustomData[$custom_Field]);
    if ($type_ID == 11) $custom_Special = $sCountry;

    echo "<tr><td class=\"LabelColumn\">" . $custom_Name . "</td><td width=\"" . $iTableSpacerWidth . "\"></td>";
    echo "<td class=\"TextColumn\">" . displayCustomField($type_ID, $currentData, $custom_Special) . "</td></tr>";


   }
  ?>
  <?php
     for($i = 1; $i <= $numColumn3Fields; $i++)
     {
      $Row = mysql_fetch_array($rsCustomFields);
      extract($Row);
      $currentData = trim($aCustomData[$custom_Field]);
      if ($type_ID == 11) $custom_Special = $sCountry;
      echo "<tr><td class=\"LabelColumn\">" . $custom_Name . "</td><td width=\"" . $iTableSpacerWidth . "\"></td>";
      echo "<td class=\"TextColumn\">" . displayCustomField($type_ID, $currentData, $custom_Special) . "</td></tr>";

     }
  ?>
  </table>
 </td>

 <td width="0%" valign="top" align="left">
  <table cellspacing="1" cellpadding="4">
  </table>
    </td>
</tr>
</table>
<br>

<?php if ($fam_ID) {  ?>

<b><?php echo gettext("Informasi Detail Keluarga $fam_Name:"); ?></b>
<table cellpadding=5 cellspacing=0 width=750>
 <tr class="TableHeader">
  <td><?php echo gettext("Nama"); ?></td>
  <td><?php echo gettext("Jenis Kelamin"); ?></td>
  <td><?php echo gettext("Peran"); ?></td>
  <td><?php echo gettext("Tgl.Lahir"); ?></td>
  <td><?php echo gettext("Umur"); ?></td>
  <td><?php echo gettext("Status"); ?></td>
 </tr>
<?php
 $sRowClass = "RowColorA";

 // Loop through all the family members
 while ($aRow = mysql_fetch_array($rsFamilyMembers))
 {
  $per_BirthYear = "";
  $agr_Description = "";

  extract($aRow);

  // Alternate the row style
  $sRowClass = AlternateRowStyle($sRowClass)

  // Display the family member
 ?>
  <tr class="<?php echo $sRowClass ?>">
   <td><?php echo $per_FirstName ?><br></td>
   <td><?php switch ($per_Gender) {case 1: echo gettext("Laki-laki"); break; case 2: echo gettext("Perempuan"); break; default: echo "";} ?>&nbsp;
	</td>
   <td><?php echo $sFamRole ?>&nbsp;</td>
   <td><?php echo $per_BirthDay,"/",$per_BirthMonth,"/",$per_BirthYear ?>&nbsp;</td>
   <td><?php PrintAge($per_BirthMonth,$per_BirthDay,$per_BirthYear, $per_Flags); ?>&nbsp;</td>
   <td><?php switch ($per_cls_ID) {
	case 1: echo gettext("Warga"); break; 
	case 2: echo gettext("Titipan"); break;
	case 3: echo gettext("Tamu"); break;  
	case 6: echo gettext("Pindah"); break; 
	case 7: echo gettext("Meninggal"); break; 
	default: echo "";} ?>&nbsp;</td>
  </tr>
 <?php
 }
 echo "</table>";
}
?>

<b><?php // echo gettext("Keadaan Khusus:"); ?></b>

<?php
//Initialize row shading
//$sRowClass = "RowColorA";
//$sAssignedProperties = ",";
//Was anything returned?
//if (mysql_num_rows($rsAssignedProperties) == 0)
//{
//echo "<p align\"center\">" . gettext("Tidak Ada Data.") . "</p>";
//}
//else
//{
// echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
// echo "<tr class=\"TableHeader\">";
// echo "<td width=\"25%\" valign=\"top\"><b>" . gettext("Name") . "</b>";
// echo "<td valign=\"top\"><b>" . gettext("Value") . "</td>";
// echo "</tr>";
// while ($aRow = mysql_fetch_array($rsAssignedProperties))
// {
//  $pro_Prompt = "";
//  $r2p_Value = "";
//  extract($aRow);
// Alternate the row style
//  $sRowClass = AlternateRowStyle($sRowClass);
// Display the row
//  echo "<tr class=\"" . $sRowClass . "\">";
//  echo "<td valign=\"top\">" . $pro_Name . "&nbsp;</td>";
//  echo "<td valign=\"top\">" . $r2p_Value . "&nbsp;</td>";
//  echo "</tr>";
//  $sAssignedProperties .= $pro_ID . ",";
// }
// echo "</table>";
//}
//if ($_SESSION['bNotes'])
//{
// echo "<p><b>" . gettext("Catatan:") . "</b></p>";
// Loop through all the notes
// while($aRow = mysql_fetch_array($rsNotes))
// {
//  extract($aRow);
//  echo "<p class=\"ShadedBox\")>" . $nte_Text . "</p>";
//  echo "<span class=\"SmallText\">" . gettext("Didata pada:") . FormatDate($nte_DateEntered,True) . "</span><br>";
//  if (strlen($nte_DateLastEdited))
//  {
//   echo "<span class=\"SmallText\">" . gettext("Di edit terakhir:") . FormatDate($nte_DateLastEdited,True) . ' ' . gettext("oleh") . ' ' . 
//$EditedFirstName . " " . $EditedLastName . "</span><br>";
//  }
// }
//}

//require "Include/Footer-Short.php";
?>

</td></tr>
</table>
</td></tr>
</table>
