<?php
/*******************************************************************************
 *
 *  filename    : PrintViewBaptisDewasa.php
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
$iBaptisID = FilterInput($_GET["BaptisID"],'int');
//$iPersonID = FilterInput($_GET["PersonID"],'int');

// Get this Baptis Dewasa Data
// $sSQL = "SELECT * FROM baptisdewasagkjbekti WHERE BaptisID = " . $iBaptisID;
$sSQL = "select a.* , z.BaptisID, 
		a.per_BirthDay as TglLahir, a.per_BirthMonth as BlnLahir,  a.per_BirthYear as ThnLahir, 
		a.per_id, 
		CONCAT(a.per_id,a.per_fam_id,a.per_gender,a.per_fmr_id) as NomorInduk,
		a.per_firstname as NamaPemohonBaptis , 
		a.per_WorkEmail as TempatLahir,
		CONCAT(a.per_BirthYear,'-',a.per_BirthMonth,'-',a.per_BirthDay) as TanggalLahir,
		a.per_Workemail as TempatLahir,
		IF(a.per_fmr_id>2,c.per_firstname,x.c16) as NamaAyah,
		IF(a.per_fmr_id>2,d.per_firstname,x.c17) as NamaIbu,	

		z.KetuaMajelis as KetuaMajelis,
		z.SekretarisMajelis as SekretarisMajelis,

		z.NamaLengkap as NamaPemohonBaptisNW, 
		z.TempatLahir as TempatLahirNW,
		z.TanggalLahir 	as TanggalLahirNW,
		z.NamaAyah 	as NamaAyahNW,
		z.NamaIbu 	as NamaIbuNW,
		z.TanggalBaptis as TanggalBaptisNW,
		z.TempatBaptis 	as TempatBaptisNW,
		z.PendetaBaptis as PendetaBaptisNW,

		z.NoSuratTitipan as NoSuratTitipanNW, 
		x.c18 as TanggalBaptis,
		x.c28 as TempatBaptis,
		x.c39 as PendetaBaptis,
		
		a.per_gender as JK , a.per_fam_id
	
	
from baptisdewasagkjbekti z 
left join person_per a ON z.per_id = a.per_id 
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
left join DaftarPendeta e ON z.PendetaBaptis = e.PendetaID

		 WHERE BaptisID = " . $iBaptisID;

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
$sPageTitle = gettext("Surat Baptis No.$NomorInduk - $NamaPemohonBaptis $NamaPemohonBaptisNW");
$iTableSpacerWidth = 10;
//require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "Print Surat Baptis " . $NamaPemohonBaptis;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iBaptisID . "','" . $logvar1 . "','" . $logvar2 . "')";
	//Execute the SQL
		RunQuery($sSQL);

?>

<table  border="0"  width=100% cellspacing=0 cellpadding=0 background="/datawarga/gkj_back2.jpg">
<tr><td valign=top align=center style="background-image: url('/datawarga/Images/blangkobaptis.jpg');background-position: center top;background-repeat:no-repeat">
<table  border="0"  width="505" cellspacing=0 cellpadding=0 >
<tr><td valign=top align=center>

<table  border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><td valign=bottom align=center width="100%" style="height:150px" > </tr>
  <tr><!-- Row 2 -->
     <td valign=top align=center >
	 <b><font FACE="Bernard MT Condensed" size="6"><?php echo $sChurchFullName ;?></b><BR>
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 ( Anggota Persekutuan Gereja Gereja di Indonesia )<BR>
	 <?php echo $sChurchAddress.",".$sChurchCity.",".$sChurchState.",KodePos:".$sChurchZip;?></font>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp:".$sChurchPhone . "- Fax:".$sChurchFax." - Email:".$sChurchEmail;?></font><br><br>
	 <b><u><font FACE="Old English Text MT" size="6">Surat Keterangan Baptis</font></u></b>
	</td><!-- Col 3 -->
	</tr>
	<tr><td valign=top align=center >
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
		 <?php if ($per_ID == 0){$TGL=$TanggalBaptisNW;} else {$TGL=$TanggalBaptis;} ?>
	Nomor: <?php echo $BaptisID."/SKBD/".$sChurchCode."/".strftime( "%Y", strtotime($TGL));?></font>
     </td></tr>
</table>

<table border="0"  width="500" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u></u></b></font><br>
  <table border="0"  width="100%">
  Majelis <?php echo $sChurchFullName; ?><br>
  Menerangkan bahwa:

  <?php 
 echo "<tr><td><td valign=center><font size=\"2\">  Nama </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> ";?>
  <?php if ($per_ID == 0){echo $NamaPemohonBaptisNW;} else {echo $NamaPemohonBaptis;} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\">  Tempat,Tanggal Lahir </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> ";?>
  <?php if ($per_ID == 0){echo $TempatLahirNW . ", " . date2Ind($TanggalLahirNW,2); } else 
  {echo $TempatLahir . ", " . date2Ind($TanggalLahir,2) ;} ?><?php echo " </b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\">  Anak dari </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> ";?>
  <?php if ($per_ID == 0){echo $NamaAyahNW;} else {echo $NamaAyah;} ?><?php echo " </font></b></td></font><td><font size=\"2\">(Ayah)</font></td></td></tr>";	   
 echo "<tr><td><td valign=center><font size=\"2\">   </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> ";?>
  <?php if ($per_ID == 0){echo $NamaIbuNW;} else {echo $NamaIbu;} ?><?php echo " </font></b></td></font><td><font size=\"2\">(Ibu)</font></td></td></tr>";	
	echo "<br>";
 echo "<tr><td><td valign=center><font size=\"2\">  Dibaptis tanggal </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> ";?>
  <?php if ($per_ID == 0){echo date2Ind($TanggalBaptisNW,2);} else {echo date2Ind($TanggalBaptis,2);} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\">  Tempat </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> ";?>
  <?php if ($per_ID == 0){echo $TempatBaptisNW;} else {echo $TempatBaptis;} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\">  Oleh </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> ";?>
  <?php if ($per_ID == 0){echo $PendetaBaptisNW;} else {echo $PendetaBaptis;} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
	echo "<br>";
  echo "<tr><td><td valign=center><font size=\"2\">  Nomor Induk </td><td>:</td><td valign=center><b><font FACE=\"Monotype Corsiva\" size=\"4\"> ";?>
  <?php if ($per_ID == 0){echo $NomorIndukNW;echo "-NW";} else {echo $NomorInduk;} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
  
 
 ?>
  </table>  
  </td><!-- Col 1 -->
  </tr>
  
  <table  border="0" width="100%">
  <tr><td align=center colspan="2"> <?php echo $sChurchCity; ?>,  <?php if ($per_ID == 0){echo date2Ind($TanggalBaptisNW,2);} else {echo date2Ind($TanggalBaptis,2);} ?></td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  Majelis <?php echo $sChurchFullName; ?> </b></td><td></td></tr>
<br>
<br>
 
 <tr>
  <td valign=bottom align=center width="50%" style="height:80px" ><u><?=$KetuaMajelis?></u></td><td valign=bottom align=center ><u><?=$SekretarisMajelis?></u></td>
  </tr>  
 <tr>
  <td valign=bottom align=center width="50%">Ketua Majelis</td><td align=center >Sekretaris</td>
  </tr>  
 

  <tr><td valign=bottom align=center colspan="2" style="height:70px">
  <u><?php if ($per_ID == 0){echo $PendetaBaptisNW;} else {echo $PendetaBaptis;} ?></u></td><td></td></tr>
  <tr><td align=center colspan="2">Pendeta Yang Melayani</td><td></td></tr>

  <tr><td align=center colspan="2" style="height:10px"><font FACE="Monotype Corsiva" size="1"><br>
  Catatan : Surat Keterangan Baptis ini tidak berlaku sebagai Surat Keterangan Pindah (Attestasi)
  </font></u></td><td></td></tr>
  </table>
</td></tr>
</table>
</td></tr>

</table>
