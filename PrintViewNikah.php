<?php
/*******************************************************************************
 *
 *  filename    : PrintViewNikah.php
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
$iNikahID = FilterInput($_GET["NikahID"],'int');

// Get this PAK Data
// $sSQL = "SELECT * FROM sidhigkjbekti WHERE NikahID = " . $iNikahID;
$sSQL = "SELECT NikahID,
per_ID_L, per_ID_P, 	
a.PendetaID, l.NamaPendeta as NamaPendeta, KetuaMajelis, SekretarisMajelis,  	
TanggalNikah, WaktuNikah, a.TempatNikah as TmpNkh,
IF(a.TempatNikah=0,a.NikahGerejaNonGKJ,m.NamaGereja) as NamaGereja,
IF(a.TempatNikah=0,a.AlamatNikahGerejaNonGKJ,m.Alamat1) as Alamat1Gereja,

IF(a.TempatNikah=0,a.PendetaNikahGerejaNonGKJ,l.NamaPendeta) as PelayanPernikahan,
m.Alamat2 as Alamat2Gereja, m.Alamat3 as Alamat3Gereja
,m.Telp as TelpGereja, m.Fax as FaxGereja, 

IF(per_ID_L>0,b.per_FirstName,NamaLengkapL) as NamaLengkapL,
IF(per_ID_L>0,b.per_WorkEmail,TempatLahirL) as TempatLahirL, 
IF(per_ID_L>0,b.per_WorkEmail,TempatLahirL) as TempatLahirL, 

IF(per_ID_L>0,CONCAT(b.per_BirthYear,'-',b.per_BirthMonth,'-',b.per_BirthDay),TanggalLahirL) as TanggalLahirL,
IF(per_ID_L>0,c.c26,TempatBaptisL) as TempatBaptisAnakL, 
IF(per_ID_L>0,c.c1,TanggalBaptisL) as TanggalBaptisAnakL,
IF(per_ID_L>0,c.c37,PendetaBaptisL) as PendetaBaptisL,
IF(per_ID_L>0,c.c27,TempatSidhiL) as TempatSidhiL, 
IF(per_ID_L>0,c.c2,TanggalSidhiL) as TanggalSidhiL,
IF(per_ID_L>0,c.c38,PendetaSidhiL) as PendetaSidhiL,
IF(per_ID_L>0,c.c28,TempatBaptisDL) as TempatBaptisDewasaL, 
IF(per_ID_L>0,c.c18,TanggalBaptisDL) as TanggalBaptisDewasaL,
IF(per_ID_L>0,c.c39,PendetaBaptisDL) as PendetaBaptiisDewasaL,

IF(per_ID_L>0,IF(c.c16 is NULL,g.per_FirstName,c.c16),a.NamaAyahL) as NamaAyahL, 
IF(per_ID_L>0,IF(c.c17 is NULL,h.per_FirstName,c.c17),a.NamaIbuL) as NamaIbuL, 

NoSuratTitipanL as NoSuratTitipanLNW, 
IF(per_ID_L=0,IF(a.WargaGerejaL>0,n.NamaGereja,WargaGerejaNonGKJL),'$sChurcName') as KelompokL,
WargaGerejaL as WargaGerejaLNW, WargaGerejaNonGKJL as WargaGerejaNonGKJLNW, AlamatGerejaNonGKJL as AlamatGerejaNonGKJLNW, 

IF(per_ID_P>0,d.per_FirstName,NamaLengkapP) as NamaLengkapP,
IF(per_ID_P>0,d.per_WorkEmail,TempatLahirP) as TempatLahirP, 

IF(per_ID_P>0,CONCAT(d.per_BirthYear,'-',d.per_BirthMonth,'-',d.per_BirthDay),TanggalLahirP) as TanggalLahirP,
IF(per_ID_P>0,e.c26,TempatBaptisP) as TempatBaptisAnakP, 
IF(per_ID_P>0,e.c1,TanggalBaptisP) as TanggalBaptisAnakP,
IF(per_ID_P>0,e.c37,PendetaBaptisP) as PendetaBaptisP,
IF(per_ID_P>0,e.c27,TempatSidhiP) as TempatSidhiP, 
IF(per_ID_P>0,e.c2,TanggalSidhiP) as TanggalSidhiP,
IF(per_ID_P>0,e.c38,PendetaSidhiP) as PendetaSidhiP,
IF(per_ID_P>0,e.c28,TempatBaptisDP) as TempatBaptisDewasaP, 
IF(per_ID_P>0,e.c18,TanggalBaptisDP) as TanggalBaptisDewasaP,
IF(per_ID_P>0,e.c39,PendetaBaptisDp) as PendetaBaptisDewasaP,

IF(per_ID_P>0,IF(e.c16 is NULL,j.per_FirstName,e.c16),a.NamaAyahP) as NamaAyahP, 
IF(per_ID_P>0,IF(e.c17 is NULL,k.per_FirstName,e.c17),a.NamaIbuP) as NamaIbuP, 

NoSuratTitipanP as NoSuratTitipanPNW, 
IF(per_ID_P=0,IF(a.WargaGerejaP>0,o.NamaGereja,WargaGerejaNonGKJP),'$sChurcName') as KelompokP,
WargaGerejaP as WargaGerejaPNW, WargaGerejaNonGKJP as WargaGerejaNonGKJPNW, AlamatGerejaNonGKJP as AlamatGerejaNonGKJPNW 


FROM PermohonanNikahgkjbekti a 
LEFT JOIN person_per b ON a.per_ID_L = b.per_ID 
LEFT JOIN person_custom c ON a.per_ID_L = c.per_ID 

LEFT JOIN person_per d ON a.per_ID_P = d.per_ID 
LEFT JOIN person_custom e ON a.per_ID_P = e.per_ID 

LEFT JOIN family_fam f ON b.per_fam_id = f.fam_id 
LEFT JOIN person_per g ON (f.fam_id = g.per_fam_id AND g.per_fmr_id = 1 AND g.per_gender = 1)
LEFT JOIN person_per h ON (f.fam_id = h.per_fam_id AND h.per_fmr_id = 2 AND h.per_gender = 2)

LEFT JOIN family_fam i ON d.per_fam_id = i.fam_id 
LEFT JOIN person_per j ON (i.fam_id = j.per_fam_id AND j.per_fmr_id = 1 AND j.per_gender = 1)
LEFT JOIN person_per k ON (i.fam_id = k.per_fam_id AND k.per_fmr_id = 2 AND k.per_gender = 2)

LEFT JOIN DaftarPendeta l ON a.PendetaID = l.PendetaID
LEFT JOIN DaftarGerejaGKJ m ON a.TempatNikah = m.GerejaID

LEFT JOIN DaftarGerejaGKJ n ON a.WargaGerejaL = n.GerejaID
LEFT JOIN DaftarGerejaGKJ o ON a.WargaGerejaP = o.GerejaID

		 WHERE NikahID = " . $iNikahID;

$rsPerson = RunQuery($sSQL);
extract(mysql_fetch_array($rsPerson));



// Get Field Security List Matrix
$sSQL = "SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence";
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysql_fetch_array($rsSecurityGrp))
{
 extract ($aRow);
 $aSecurityType[$lst_OptionID] = $lst_OptionName;
}



// Set the page title and include HTML header
$sPageTitle = gettext("Surat Nikah No.$NikahID - $NamaLengkapL dan $NamaLengkapP");
$iTableSpacerWidth = 10;
//require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Print Surat Nikah No." . $NikahID;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iNikahID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table  border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center style="background-image: url('/datawarga/Images/blangkobaptis.jpg');background-position: center top;background-repeat:no-repeat">
<table  border="0"  width="505" cellspacing=0 cellpadding=0 >
<tr><td valign=top align=center>

<table  border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><td valign=bottom align=center width="100%" style="height:90px" > </tr>
  <tr><!-- Row 2 -->
     <td valign=top align=center >
	 <b><font FACE="Bernard MT Condensed" size="5"><?php echo $sChurchFullName ;?></b><BR>
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 ( Anggota Persekutuan Gereja Gereja di Indonesia )<BR>
	 <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp:". $sChurchPhone . "- Fax: " .$sChurchFax." - Email:". $sChurchEmail;?></font><br>
	 <b><u><font FACE="Old English Text MT" size="4">Surat Peneguhan Pernikahan dan Pemberkatan Perkawinan</font></u></b>
	</td><!-- Col 3 -->
	</tr>
	<tr><td valign=top align=center >
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if ($per_ID == 0){$TGL=$TanggalNikah;} else {$TGL=$TanggalNikah;} ?>
	Nomor: <?php echo $NikahID."/SPPP/".$sChurchCode."/".strftime( "%Y", strtotime($TGL));?>
	<?php //echo $NomorInduk."/Si/"."$NikahID";?></font>
     </td></tr>
</table>

<table border="0"  width="520" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u></u></b></font><br>
  <table border="0"  width="100%" ><font size="2" style="font-family: Arial">
  Majelis <?php echo $sChurchFullName; ?> telah melayankan Peneguhan Pernikahan dan Pemberkatan Perkawinan :<br>
</font>
  <?php 
  
 
 echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Seorang laki-laki bernama </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ". $NamaLengkapL. " </font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Tempat dan Tanggal Lahir </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".$TempatLahirL.", ".date2Ind($TanggalLahirL,2)." </font></b></td></font><td></td></td></tr>";	
  echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Tempat dan Tanggal Baptis </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
   ".$TempatBaptisAnakL."".$TempatBaptisDewasaL.", ".date2Ind($TanggalBaptisAnakL,2)."".date2Ind($TanggalBaptisDewasaL,2)." </font></b></td></font><td></td></td></tr>";	
  echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Tempat dan Tanggal Sidi </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".$TempatSidhiL.", ".date2Ind($TanggalSidhiL,2)." </font></b></td></font><td></td></td></tr>";	
  echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Anak dari suami-istri </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".$NamaAyahL." & ".$NamaIbuL." </font></b></td></font><td></td></td></tr>";	
 
 echo " <tr><td></td><td></td><td colspan=\"3\" valign=center ><font size=\"2\" style=\"font-family: Arial\">Dengan</font></td></tr>";
 
 echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Seorang perempuan bernama </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ". $NamaLengkapP. " </font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Tempat dan Tanggal Lahir </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".$TempatLahirP.", ".date2Ind($TanggalLahirP,2)." </font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Tempat dan Tanggal Baptis </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".$TempatBaptisAnakP."".$TempatBaptisDewasaP.", ".date2Ind($TanggalBaptisAnakP,2)."".date2Ind($TempatBaptisDewasaP,2)." </font></b></td></font><td></td></td></tr>";	
  echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Tempat dan Tanggal Sidi </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".$TempatSidhiP.", ".date2Ind($TanggalSidhiP,2)." </font></b></td></font><td></td></td></tr>";	
  echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Anak dari suami-istri </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".$NamaAyahP." & ".$NamaIbuP." </font></b></td></font><td></td></td></tr>";	

  echo " <td></td><td></td><td></td>";
  
   echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Bertempat di </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".$NamaGereja." </font></b></td></font><td></td></td></tr>";	
   echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Dilayani Oleh </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".$PelayanPernikahan." </font></b></td></font><td></td></td></tr>";	
  echo "<tr><td><td valign=center><font size=\"2\" style=\"font-family: Arial\"> Hari dan Tanggal </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> 
  ".date2Ind($TanggalNikah,1)." </font></b></td></font><td></td></td></tr>";	
  ?>
  </table>  
  </td><!-- Col 1 -->
  </tr>
  
  <table  border="0" width="100%">
  <tr><td align=center rowspan="2"><img border="1" src="\Images\Spacer.gif" width="180" height="100" > </td><td align=center colspan="2"> <?php echo $sChurchCity; ?>,  <?php if ($per_ID == 0){echo date2Ind($TanggalNikah,2);} else {echo date2Ind($TanggalSidhi,2);} ?></td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  Majelis Gereja Kristen Jawa <br> <?php echo $sChurchGKJName; ?> </b></td><td></td></tr>
<br>
 
 <tr>
  <td valign=bottom align=center width="50%" style="height:100px" ><u><?=$KetuaMajelis?></u></td><td valign=bottom align=center ><u><?=$SekretarisMajelis?></u></td>
  </tr>  
 <tr>
  <td valign=bottom align=center width="50%">Ketua Majelis</td><td align=center >Sekretaris</td>
  </tr>  
 

  <tr><td valign=bottom align=center colspan="2" style="height:20px">
  <u><?php if ($per_ID == 0){echo $PelayanPernikahan;} else {echo $DiSidhiOleh;} ?></u></td><td></td></tr>
  <tr><td align=center colspan="2">Pendeta Yang Melayani</td><td></td></tr>

   </table>
</td></tr>
</table>
</td></tr>

</table>
