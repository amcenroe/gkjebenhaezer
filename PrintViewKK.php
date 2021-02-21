<?php
/*******************************************************************************
 *
 *  filename    : PrintViewKK.php
 *  last change : 2003-01-29
 *
 *  2008 Erwin Pratama for GKJ Bekasi WIl Timur
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Get the person ID from the querystring
$iPersonID = FilterInput($_GET["PersonID"],'int');
$iFamilyID = FilterInput($_GET["FamilyID"],'int');
$iPindahID = FilterInput($_GET["PindahID"],'int');
$iPindahKID = FilterInput($_GET["PindahKID"],'int');

// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}



if ($iFamilyID == ""){
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
// Get Tanggal Pindah
$sSQL1 = "SELECT TanggalPindah,Keterangan from PermohonanPindahgkjbekti 
   WHERE per_ID = " . $iPersonID." LIMIT 1";
$rsPerson1 = RunQuery($sSQL1);
extract(mysql_fetch_array($rsPerson1));

$TglPindah = $TanggalPindah;
//echo $sSQL1;
//echo $TglPindah;


}else{
$sSQL = "SELECT a.*, family_fam.*, cls.lst_OptionName AS sClassName, fmr.lst_OptionName AS sFamRole, b.per_FirstName AS EnteredFirstName,
    b.Per_LastName AS EnteredLastName, c.per_FirstName AS EditedFirstName, c.per_LastName AS EditedLastName
   FROM person_per a
   LEFT JOIN family_fam ON a.per_fam_ID = family_fam.fam_ID
   LEFT JOIN list_lst cls ON a.per_cls_ID = cls.lst_OptionID AND cls.lst_ID = 1
   LEFT JOIN list_lst fmr ON a.per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
   LEFT JOIN person_per b ON a.per_EnteredBy = b.per_ID
   LEFT JOIN person_per c ON a.per_EditedBy = c.per_ID
   WHERE family_fam.fam_ID = " . $iFamilyID ." AND a.per_fmr_id = 1 ";
$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));
// Get Tanggal Pindah
$sSQL1 = "SELECT TanggalPindah,Keterangan from PermohonanPindahKgkjbekti 
   WHERE fam_ID = " . $iFamilyID ." LIMIT 1";
$rsPerson1 = RunQuery($sSQL1);
extract(mysql_fetch_array($rsPerson1));

$TglPindah = $TanggalPindah;
//echo $sSQL1;
//echo $TglPindah;


$iPersonID=$per_ID;
}







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
$sPageTitle = gettext("Informasi KeluargaJemaat : ID : $per_fam_ID , $fam_Name, $fam_WorkPhone ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Family View or Print";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPersonID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>
<style type="text/css">  
      td.thickBorder{ border: solid #000 1px;}  
    </style>  
<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>
<table border="0"  width="1000" cellspacing=0 cellpadding=0>
<tr><td valign=top align=center>
<BR>
<table border="0"  width="1000" cellspacing=5 cellpadding=5>
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
	 <b><font size="2">Data Keluarga</font></b><br>
  <hr>
  <b>Nomor Induk Keluarga : <?php echo $per_fam_ID ?>
    -- Kode Kelompok : <?php echo $fam_WorkPhone ?>
   </td><!-- Col 2 -->
     <td class="thickBorder" valign=top align=left >
	 <?php
	   echo "Keluarga : <br>. <b>".$fam_Name."</b><br>
  Alamat :<br>. <font size=\"2\">";
	 	 	 	
  if ($fam_Address1 != "") { echo $fam_Address1 . "<br>. "; }
  if ($fam_City != "") { echo $fam_City . ","; }
  if ($fam_State != "") { echo $fam_State . ", "; }
  if ($fam_Zip != "") { echo $fam_Zip; }
  if ($Zip != "") { echo " " . $Zip; }
  if ($fam_Country != "") {echo "," . $fam_Country; }
  echo "<br>. Telp :";
  if ($fam_HomePhone != "") {echo "" . $fam_HomePhone; }
  echo "</font>";
  ?>
	 
      </td><!-- Col 3 -->
  </tr>
</table>


  
<?

  $iFamilyID = $fam_ID;

  if ($fam_ID)
  {
   //Get the family members for this family
   $sSQL = "SELECT a.per_ID, per_Title, per_FirstName, per_MiddleName, per_LastName, per_Suffix, per_Gender, per_cls_ID,
    per_BirthMonth, per_BirthDay, per_BirthYear, cls.lst_OptionName AS sClassName,per_WorkEmail,
	CONCAT(per_BirthYear,'-',per_BirthMonth,'-',per_BirthDay) as TanggalLahir, a.per_fmr_ID as per_fmr_ID,
    fmr.lst_OptionName AS sFamRole, b.*
    FROM person_per a
	LEFT JOIN person_custom b ON a.per_ID = b.per_ID 
    LEFT JOIN list_lst cls ON per_cls_ID = cls.lst_OptionID AND cls.lst_ID = 1
    LEFT JOIN list_lst fmr ON per_fmr_ID = fmr.lst_OptionID AND fmr.lst_ID = 2
    WHERE per_fam_ID = " . $iFamilyID . " AND c10 = '" . $TglPindah . "'  ORDER BY fmr.lst_OptionSequence , per_BirthYear, per_BirthMonth";
   $rsFamilyMembers = RunQuery($sSQL);
  }
  ?>
 
  </td><!-- Col 1 -->
  </tr>
  <td></td>

</table>



<?php if ($fam_ID) {  ?>

    <?php


  ?>
  
<table cellpadding=5 cellspacing=0 width=1000>
 <tr class="TableHeader">
  <td><?php echo gettext("No"); ?></td>
  <td><?php echo gettext("Kode<br>Warga"); ?></td>
  <td><?php echo gettext("Nama Lengkap"); ?></td>
  <td><?php echo gettext("Nama OrangTua"); ?></td>
  <td><?php echo gettext("Jenis Kelamin"); ?></td>
  <td><?php echo gettext("Peran"); ?></td>
  <td><?php echo gettext("Status<br>Kawin"); ?></td>
  <td><?php echo gettext("Tempat/<br>Tgl.Nikah"); ?></td>
  <td><?php echo gettext("Tempat/<br>Tgl.Lahir"); ?></td>
  <td><?php echo gettext("Tempat/<br>Tgl.Baptis"); ?></td>
  <td><?php echo gettext("Tempat/<br>Tgl.Sidhi"); ?></td>
  <td><?php echo gettext("Tempat/<br>Tgl.BaptisDws"); ?></td>
   <td><?php echo gettext("Status"); ?></td>
 </tr>
<?php
 $sRowClass = "RowColorA";

 // Loop through all the family members
  $i=0;
 while ($aRow = mysql_fetch_array($rsFamilyMembers))
 {
 $i++;
  $per_BirthYear = "";
  $agr_Description = "";

  extract($aRow);

  // Alternate the row style
  $sRowClass = AlternateRowStyle($sRowClass)

  // Display the family member
 ?>
  <tr class="<?php echo $sRowClass ?>">
   <td><?php echo $i ?><br></td>
   <td><?php echo $per_ID."<br>".$per_fam_ID,$per_Gender,$per_fmr_ID ?><br></td>
   <td><b><?php echo $per_FirstName ?></b></td>
   <td><?php if($c16 != ""){echo $c16." <i>(Ayah)</i>";}else{echo" - ";};echo "<br><hr>";if($c17 != ""){echo $c17." <i>(Ibu)</i>";}else{echo" - ";}; ?>&nbsp;</td>
   <td align="center"><?php switch ($per_Gender) {case 1: echo gettext("Lk"); break; case 2: echo gettext("Pr"); break; default: echo "";} ?>&nbsp;
	</td> 
   <td><?php echo $sFamRole ?>&nbsp;</td>
    <td><?php switch ($c15) {
	case 1: echo gettext("Belum Kawin"); break; 
	case 2: echo gettext("Kawin"); break;
	default: echo "";} ?>&nbsp;</td>
   <td><?php 
   switch ($c15) {
   case 1:echo gettext(" "); break; 
   case 2:	if($fam_WeddingDate != ""){echo date2Ind($fam_WeddingDate,3);}; break;
	default: echo "";} ?>&nbsp;</td>
   <td><?php echo $per_WorkEmail."<br>".date2Ind($TanggalLahir,3); ?>&nbsp;</td>
   <td><?php if($c26 != ""){echo $c26."<br>".date2Ind($c1,3);} ?>&nbsp;</td>
   <td><?php if($c27 != ""){echo $c27."<br>".date2Ind($c2,3);} ?>&nbsp;</td>
   <td><?php if($c28 != ""){echo $c28."<br>".date2Ind($c18,3);} ?>&nbsp;</td>
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
 echo "</table>
 Catatan:
 <br> ".$Keterangan."<hr>";
}
?>

<b><?php // echo gettext("Keadaan Khusus:"); ?></b>

<?php

?>

</td></tr>
</table>

