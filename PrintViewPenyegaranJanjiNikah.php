<?php 
/*******************************************************************************
 *
 *  filename    : PrintViewPenyegaranJanjiNikah.php
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

// $sSQL = "SELECT * FROM sidhigkjbekti WHERE NikahID = " . $iNikahID;



$sSQL = "SELECT NikahID,
per_ID_L, per_ID_P, 	
a.PendetaID, l.NamaPendeta as PelayanPernikahan, KetuaMajelis, SekretarisMajelis,  	
TanggalNikah, WaktuNikah, a.TempatNikah as TmpNkh,
m.NamaGereja as NamaGereja,
m.Alamat1 as Alamat1Gereja,


g.per_FirstName as NamaLengkapL,g.per_WorkEmail as TempatLahirL,
CONCAT(g.per_BirthYear,'-',g.per_BirthMonth,'-',g.per_BirthDay) as TanggalLahirL,
i.c26 as TempatBaptisAnakL, 
i.c1 as TanggalBaptisAnakL,
i.c37 as PendetaBaptisL,
i.c27 as TempatSidhiL, 
i.c2 as TanggalSidhiL,
i.c38 as PendetaSidhiL,
i.c28 as TempatBaptisDewasaL, 
i.c18 as TanggalBaptisDewasaL,
i.c39 as PendetaBaptisDewasaL,
i.c16 as NamaAyahL, 
i.c17 as NamaIbuL, 

h.per_FirstName as NamaLengkapP,h.per_WorkEmail as TempatLahirP,
CONCAT(h.per_BirthYear,'-',h.per_BirthMonth,'-',h.per_BirthDay) as TanggalLahirP,
j.c26 as TempatBaptisAnakP,
j.c1 as TanggalBaptisAnakP,
j.c37 as PendetaBaptisP,
j.c27 as TempatSidhiP,
j.c2 as TanggalSidhiP,
j.c38 as PendetaSidhiP,
j.c28 as TempatBaptisDewasaP,
j.c18 as TanggalBaptisDewasaP,
j.c39 as PendetaBaptisDewasaP,
j.c16 as NamaAyahP, 
j.c17 as NamaIbuP


FROM PermohonanPenyegaranJanjiNikah a 

LEFT JOIN person_per g ON (a.fam_id = g.per_fam_id AND g.per_fmr_id = 1 AND g.per_gender = 1)
LEFT JOIN person_custom i ON g.per_id = i.per_id

LEFT JOIN person_per h ON (a.fam_id = h.per_fam_id AND h.per_fmr_id = 2 AND h.per_gender = 2)
LEFT JOIN person_custom j ON h.per_id = j.per_id

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
$sPageTitle = gettext("Surat Tanda Pelayanan Pembaruan Penyegaran Janji Perkawinan No.$NikahID - $NamaLengkapL dan $NamaLengkapP");
$iTableSpacerWidth = 10;
//require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Print Surat Tanda Pelayanan Pembaruan Penyegaran Janji Perkawinan No." . $NikahID;
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
	 <b><u><font FACE="Old English Text MT" size="4">Surat Tanda Pelayanan Pembaruan/Penyegaran Janji Perkawinan</font></u></b>
	</td><!-- Col 3 -->
	</tr>
	<tr><td valign=top align=center >
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php if ($per_ID == 0){$TGL=$TanggalNikah;} else {$TGL=$TanggalNikah;} ?>
	Nomor: <?php echo $NikahID."/SPJP/".$sChurchCode."/".strftime( "%Y", strtotime($TGL));?>
	<?php //echo $NomorInduk."/Si/"."$NikahID";?></font>
     </td></tr>
</table>

<table border="0"  width="520" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u></u></b></font><br>
  <table border="0"  width="100%" ><font size="2" style="font-family: Arial">
  Majelis <?php echo $sChurchFullName; ?> telah melayankan Pembaruan / Penyegaran Janji Perkawinan kepada:<br>
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
  <tr><td align=center rowspan="2"><img border="1" src="\Images\Spacer.gif" width="180" height="100" > </td><td align=center colspan="2"> <?php echo "$sChurchCity" ;?>,  <?php if ($per_ID == 0){echo date2Ind($TanggalNikah,2);} else {echo date2Ind($TanggalSidhi,2);} ?></td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  Majelis Gereja Kristen Jawa <br> <?php echo $sChurchGKJName; ?> </b></td><td></td></tr>
<br>
 <tr>
 <td>.</td>
 </tr>
 <tr>
  <td valign=bottom align=center width="50%" style="height:100px" ><u><?=$KetuaMajelis?></u></td><td valign=bottom align=center ><u><?=$SekretarisMajelis?></u></td>
  </tr>  
 <tr>
  <td valign=bottom align=center width="50%">Ketua Majelis</td><td align=center >Sekretaris</td>
  </tr>  
 

  <tr><td valign=bottom align=center colspan="2" style="height:20px">
  <u><?php if ($per_ID == 0){echo $PelayanPernikahan;} else {echo $DiSidhiOleh;} ?></u></td><td></td></tr>
  <tr><td align=center colspan="2">Pendeta Yang Melayani</td><td></td></tr>
   <tr><td align=center colspan="2"><font size="1"><i>
   <b>Catatan:</b> Surat Keterangan Pembaharuan Janji Perkawinan ini TIDAK berlaku sebagai Surat Keterangan Nikah Gerejawi
   </font></i></td><td></td></tr>

   </table>
</td></tr>
</table>
</td></tr>

</table>
