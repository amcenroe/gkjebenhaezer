<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPermohonanPindahKeluarga.php
 *
 *  2012 Erwin Pratama for GKJ Bekasi Timur
 *  Sistem Informasi GKJ Bekasi Timur is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 ******************************************************************************/

// Include the function library
require "Include/Config.php";
require "Include/Functions.php";

// Get the person ID from the querystring
$iPindahKID = FilterInput($_GET["PindahKID"],'int');
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');

// Get the person ID from the querystring
$iPersonID = FilterInput($_GET["PersonID"],'int');
$iFamilyID = FilterInput($_GET["FamilyID"],'int');
$iPindahID = FilterInput($_GET["PindahID"],'int');

// Get Family Info
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
$sSQL1 = "SELECT * from PermohonanPindahKgkjbekti 
   WHERE fam_ID = " . $iFamilyID ." LIMIT 1";
$rsPerson1 = RunQuery($sSQL1);
extract(mysql_fetch_array($rsPerson1));

$TglPindah = $TanggalPindah;

  if ($iFamilyID)
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


// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}

// Set the page title and include HTML header
$sPageTitle = gettext("Surat Permohonan Pindah tgl $iPindahKID");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Print Surat Pengantar Permohonan Pindah";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPindahKID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center>
<table border="0"  width="605" cellspacing=0 cellpadding=0>
<tr><td valign=top align=center>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 2 -->
     <td valign=top align=left>
<?php  if (($iMode==1)||($iMode==2)){	 
     echo "<img border=\"0\" src=\"gkj_logo.jpg\" width=\"110\" >";
	 }else{
	 echo "<img border=\"0\" src=\"\Images\Spacer.gif\" width=\"1\" height=\"90\" >"; 
	}	
?>	
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php  if (($iMode==1)||($iMode==2)){echo "GEREJA KRISTEN JAWA";}else{echo"";} ;?></font></b><BR>
	 <b style="font-family: Times; color: rgb(0, 0, 102);"><font size="4"><?php if (($iMode==1)||($iMode==2)){echo strtoupper($sChurchGKJName) ;}else{echo"";}?></font></b><BR>
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)){echo "(Anggota Persekutuan Gereja-Gereja di Indonesia)";}else{echo"";}?></font></b><br>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if (($iMode==1)||($iMode==2)){echo $sChurchAddress."
	 <BR>".$sChurchCity." ". $sChurchState." ". $sChurchZip."
	 <BR>Telp : ".$sChurchPhone." , Fax : ".$sChurchFax."
	 <BR>Email: ".$sChurchEmail." , Situs Web : ".$sChurchWebsite;}else{echo"";}?></font></b>
        </td><!-- Col 3 -->

  </tr>

</table>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u><? if (($iMode==1)||($iMode==2)){ echo "<hr style=\"border: 3px outset #595955;\">";}else{echo "<br>";}; ?></u></b></font>
     <table border="0"  width="100%">

    <?php

   echo "<tr><td valign=top>Nomor Surat:</td><td><font size=\"2\">  " . $PindahKID . "e/SKPK/".$sChurchCode."/"; echo dec2roman(date (m)) ;echo "/"; echo date('Y');"</font></td>";
  
  echo "<td>".$sChurchCity.", </td><td>"; echo tanggalsekarang() ; date ('Y' ); echo " </td></tr>";
   echo "<tr><td valign=top>Hal:</td><td><font size=\"2\"> Permohonan Pindah / Attestasi </font></td></tr>";
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
   echo "<tr><td valign=top colspan=2 >Kepada YTH</td><td><font size=\"2\"></font></td></tr>";	
 if ($GerejaID<>"0"){ 
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><b> Majelis " . $NamaGereja . "</b></td><td><font size=\"2\"></font></td></tr>";
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $NamaGereja . "</font></td><td><font size=\"2\"></font></td></tr>";		   
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat1 . "</font></td><td><font size=\"1\"></font></td></tr>";	
   if ($Alamat2<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat2 . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};	
   if ($Alamat3<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat3 . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};
   if ($Telp<>""){ 
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> Telp " . $Telp . ",Fax " . $Fax . "</font></td><td><font size=\"1\"></font></td></tr>";	}else{};

   }
else {
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><b> " . $NamaGerejaNonGKJ . "</b></td><td><font size=\"2\"></font></td></tr>";
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat1NonGKJ . "</font></td><td><font size=\"1\"></font></td></tr>";	
   if ($Alamat2NonGKJ<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat2NonGKJ . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};	
   if ($Alamat3NonGKJ<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat3NonGKJ . "</font></td><td><font size=\"1\"></font></td></tr>";}else{};
    if ($TelpNonGKJ<>""){ 
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> Telp " . $TelpNonGKJ . ",Fax " . $FaxNonGKJ . "</font></td><td><font size=\"1\"></font></td></tr>";	}


  }
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";

   ?>
  </table>


  <table border="0"  width="100%">
  <?php 
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"><i>Salam Sejahtera dalam kasih Tuhan Yesus Kristus,</i></font></td>"; 
   echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"></font></td>"; 
 echo "<tr><td valign=top colspan=\"5\"><font size=\"2\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"80\" height=\"1\" >  
Majelis " .$sChurchFullName. "  dengan ini menerangkan bahwa Saudara/Keluarga yang namanya tersebut di bawah ini, 
adalah anggota " .$sChurchFullName. "  :
</font></td>";

  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ><td valign=top><font size=\"2\">  Nama </td><td>:</td><td><b> " . $fam_Name . " </b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Nomor Induk </td><td>:</td><td><b> " .$fam_ID. " </b></td></font><td></td></td>";
  
  echo "<tr><td><td valign=top><font size=\"2\">  Anggota Keluarga </td><td>:</td><td><b>";

   while ($aRow = mysql_fetch_array($rsFamilyMembers))
 {
 extract($aRow);
  echo $per_FirstName."</b>";
  
  switch ($per_fmr_ID) {
	case 1: echo gettext(" (Kep.Keluarga),<b>"); break; 
	case 2: echo gettext(" (Istri/Pasangan),<b>"); break;
	case 3: echo gettext(" (Anak),<b>"); break;
	case 4: echo gettext(" (Saudara),<b>"); break;
	default: echo "";}
	
	
  
  
  }


  echo "</b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Alamat </td><td>:</td><td><b> " . $Alamat1Baru . " </b></td></font><td></td></td>";	
  echo "<tr><td><td valign=top><font size=\"2\">   </td><td></td><td><b> " . $Alamat2Baru.", ".$Alamat3Baru . " </b></td></font><td></td></td>";	
        if ($TelpBaru<>""){
  echo "<tr><td><td valign=top><font size=\"2\">  Telp </td><td>:</td><td><b> " . $TelpBaru . " </b></td></font><td></td></td>";}	
   echo "<tr><td><td valign=top><font size=\"2\">  Catatan </td><td>:</td><td><b> " . $Keterangan . " </b></td></font><td></td></td>";  
  echo "<tr><td></td></tr>";
  echo "<tr><td colspan=5 >Selanjutnya kami menyerahkan Saudara tersebut kepada Majelis:</td></tr>";
  echo "<tr><td></td></tr>";
  
 if ($GerejaID<>"0"){   
  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ><td valign=top><font size=\"2\">  Nama Gereja </td><td>:</td><td><b> " . $NamaGereja . " </b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Alamat </td><td>:</td><td><b> " . $Alamat1 . " </b></td></font><td></td></td>";
    if ($Alamat2<>""){ 
  echo "<tr><td><td valign=top><font size=\"2\">        </td><td>:</td><td><b> " . $Alamat2 . ", " . $Alamat3 . " </b></td></font><td></td></td>";}
      if ($Telp<>""){
  echo "<tr><td><td valign=top><font size=\"2\">  Telp/Fax </td><td>:</td><td><b> " . $Telp . " / " .$Fax. " </b></td></font><td></td></td>";}
    }else { 
   echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ><td valign=top><font size=\"2\">  Nama Gereja </td><td>:</td><td><b> " . $NamaGerejaNonGKJ . " </b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Alamat </td><td>:</td><td><b> " . $Alamat1NonGKJ . " </b></td></font><td></td></td>";
   if ($Alamat2NonGKJ<>""){
  echo "<tr><td><td valign=top><font size=\"2\">         </td><td>:</td><td><b> ".$Alamat2NonGKJ.", ".$Alamat3NonGKJ." </b></td></font><td></td></td>";}
     if ($TelpNonGKJ<>""){
  echo "<tr><td><td valign=top><font size=\"2\">  Telp/Fax </td><td>:</td><td><b> " . $TelpNonGKJ . " / " .$FaxNonGKJ. " </b></td></font><td></td></td>";}
	}

  echo "<tr><td valign=top colspan=\"5\"><font size=\"2\"> Demikian kami sampaikan, kiranya Tuhan Yesus Kristus senantiasa memberkati pelayanan kita. </font></td>";
 
 ?>
   <table  border="0" width="100%">
  <tr><td align=center colspan="2"> <?php echo $sChurchCity;?>,  <?php echo tanggalsekarang();?></td><td></td></tr>
     <tr><td align=center  colspan="2">  Teriring Salam dan Doa Kami, </td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  MAJELIS <?php echo strtoupper($sChurchFullName);?> </b></td><td></td></tr>

<br>
<br>
 <?php  if (($iMode==2)||($iMode==4)){	
	echo "<tr>";
    echo "<td valign=bottom align=center ><img border=\"0\" src=\"ttd_ketua.jpg\"></td><td valign=bottom align=center ><img border=\"0\" src=\"ttd_sekre1.jpg\"></td>";
	echo "</tr>";
	}else{
	 echo "<tr>";
	 echo "<td></td><td></td>"; 
	 echo "</tr>";
	}	
?>	
 <tr>
  <td valign=bottom align=center width="50%" 
  <?php  if (($iMode==2)||($iMode==4)){	 
  echo "style=\"height:1px\""; }else{ 
  echo "style=\"height:80px\""; }
  ?>
  ><u><?php echo jabatanpengurus(61); ?></u></td><td valign=bottom align=center ><u><?php echo jabatanpengurus(65); ?></u></td>
  </tr>  
 <tr>
  <td valign=bottom align=center width="50%">Ketua Majelis</td><td align=center >Sekretaris</td>
  </tr>  
 

  <tr><td valign=bottom align=center colspan="2" style="height:50px">
  <u><?php echo jabatanpengurus(1); ?></u></td><td></td></tr>
  <tr><td align=center colspan="2">Pendeta Jemaat</td><td></td></tr>


  </table>
 
  </table>  
  </td><!-- Col 1 -->
  </tr>
  
<br>


</td></tr>
</table>
</td></tr>
</table>
