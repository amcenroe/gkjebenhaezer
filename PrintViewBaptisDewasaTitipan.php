<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPermohonanPF.php
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
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');

	
$sSQL = "select a.* , z.BaptisID, 
		a.per_BirthDay as TglLahir, a.per_BirthMonth as BlnLahir,  a.per_BirthYear as ThnLahir, 
		a.per_id, 
		CONCAT(a.per_id,a.per_fam_id,a.per_gender,a.per_fmr_id) as NomorInduk,
		a.per_firstname as NamaPemohonBaptis , 
		a.per_WorkEmail as TempatLahir,
		CONCAT(a.per_BirthYear,'-',a.per_BirthMonth,'-',a.per_BirthDay) as TanggalLahir,
		a.per_Workemail as TempatLahir,
		c.per_firstname as NamaAyah, 
		c.per_WorkEmail as TempatLahirAyah,
		CONCAT(c.per_BirthYear,'-',c.per_BirthMonth,'-',c.per_BirthDay) as TanggalLahirAyah,
		CONCAT(c.per_id,c.per_fam_id,c.per_gender,c.per_fmr_id) as NomorIndukAyah,
		
		d.per_firstname as NamaIbu,	
		d.per_WorkEmail as TempatLahirIbu,
		CONCAT(d.per_BirthYear,'-',d.per_BirthMonth,'-',d.per_BirthDay) as TanggalLahirIbu,
		CONCAT(d.per_id,d.per_fam_id,d.per_gender,d.per_fmr_id) as NomorIndukIbu,
		
		CONCAT(b.fam_Address1,',',b.fam_Address2,',',b.fam_City,',',b.fam_State,',',b.fam_Zip) as AlamatKeluarga,
		
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
		x.c1 as TanggalBaptis,
		x.c26 as TempatBaptis,
		x.c37 as PendetaBaptis,
		
		f.NamaGereja as NamaGerejaGKJ,
		f.Alamat1 as Alamat1Gereja,
		f.Alamat2 as Alamat2Gereja,
		f.Alamat3 as Alamat3Gereja,
		f.Telp as TelpGereja,
		f.Fax as FaxGereja,
		
		a.per_gender as JK , a.per_fam_id
	
	
from baptisanakgkjbekti z 
left join person_per a ON z.per_id = a.per_id 
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
left join DaftarPendeta e ON z.PendetaBaptis = e.PendetaID
left join DaftarGerejaGKJ f ON z.TempatBaptis = f.GerejaID

		 WHERE d.per_cls_id < 3 AND BaptisID = " . $iBaptisID;

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
$sPageTitle = gettext("Surat Pengantar Pelayanan Baptis No.".$NomorInduk."-".$NamaPemohonBaptis."-".$NamaPemohonBaptisNW);
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Print Mail Disposisi";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPelayanFirmanID . "','" . $logvar1 . "','" . $logvar2 . "')";
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
	 <BR>".$sChurchCity.", ". $sChurchState.", Kode POS ". $sChurchZip."
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

   echo "<tr><td valign=top>Nomor Surat:</td><td><font size=\"2\">  " . $BaptisID . "e/SPBA/".$sChurchCode."/"; echo dec2roman(date (m)) ;echo "/"; echo date('Y');"</font></td>";
  
  echo "<td>".$sChurchCity.", </td><td>"; echo tanggalsekarang() ; date ('Y' ); echo " </td></tr>";
   echo "<tr><td valign=top>Hal:</td><td><font size=\"2\"> Surat Pengantar Pelayanan Baptis </font></td></tr>";
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
   echo "<tr><td valign=top colspan=2 >Kepada YTH</td><td><font size=\"2\"></font></td></tr>";	

   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><b> Majelis " . $NamaGerejaGKJ . "</b></td><td><font size=\"2\"></font></td></tr>";
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $NamaGereja . "</font></td><td><font size=\"2\"></font></td></tr>";		   
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat1Gereja . "</font></td><td><font size=\"1\"></font></td></tr>";	
  // if ($Alamat2<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat2Gereja . "</font></td><td><font size=\"1\"></font></td></tr>";
  // }else{};	
  // if ($Alamat3<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat3Gereja . "</font></td><td><font size=\"1\"></font></td></tr>";
 //  }else{};
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> Telp " . $TelpGereja . ",Fax " . $FaxGereja . "</font></td><td><font size=\"1\"></font></td></tr>";	



   ?>
  </table>
  <br>

  <table border="0"  width="100%">
  <?php 
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"><i>Salam Sejahtera dalam kasih Tuhan Yesus Kristus,</i></font></td>"; 
   echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"></font></td>"; 

 echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"80\" height=\"1\" >  
 Majelis ".$sChurchFullName." dengan ini menerangkan bahwa Saudara yang namanya tersebut di bawah ini, 
 adalah anggota ".$sChurchFullName." :</font></td>"; 
 
  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ></td>
  <td valign=top ><font size=\"2\"> Nama (Ayah) </td><td>:</td><td><font size=\"2\"><b> ";?> <?php if ($per_ID == 0){echo $NamaAyahNW;} else {echo $NamaAyah;} ?><?php echo " </b></td></font><td></td></td>";	
 // echo "<tr><td><td valign=top><font size=\"2\">  No. Stmb </td><td>:</td><td><font size=\"2\"><b> " . $NomorIndukAyah . " </b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Tempat,Tgl Lahir </td><td>:</td><td><font size=\"2\"><b> " . $TempatLahirAyah . ", " . date2Ind($TanggalLahirAyah,2) . " </b></td></font><td></td></td>";
 
  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ></td>
  <td valign=top ><font size=\"2\"> Nama (Ibu) </td><td>:</td><td><font size=\"2\"><b> ";?> <?php if ($per_ID == 0){echo $NamaIbuNW;} else {echo $NamaIbu;} ?><?php echo " </b></td></font><td></td></td>";	
//  echo "<tr><td><td valign=top><font size=\"2\">  No. Stmb </td><td>:</td><td><font size=\"2\"><b> " . $NomorIndukIbu . " </b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Tempat,Tgl Lahir </td><td>:</td><td><font size=\"2\"><b> " . $TempatLahirIbu . ", " . date2Ind($TanggalLahirIbu,2) . " </b></td></font><td></td></td>";
  
  echo "<tr><td><td valign=top><font size=\"2\">  Alamat </td><td>:</td><td><font size=\"2\"><b> " . $AlamatKeluarga . " </b></td></font><td></td></td>";	

 
 echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"1\" height=\"1\" >  
 Bermaksud Membaptiskan anaknya yang bernama di bawah ini  :</font></td>";
 ?>
  <?php 
 echo "<tr><td><td valign=center><font size=\"2\">  Nama </td><td>:</td><td valign=center><b><font size=\"2\"> ";?>
  <?php if ($per_ID == 0){echo $NamaPemohonBaptisNW;} else {echo $NamaPemohonBaptis;} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\">  Tempat,Tanggal Lahir </td><td>:</td><td valign=center><b><font size=\"2\"> ";?>
  <?php if ($per_ID == 0){echo $TempatLahirNW . ", " . date2Ind($TanggalLahirNW,2); } else 
  {echo $TempatLahir . ", " . date2Ind($TanggalLahir,2) ;} ?><?php echo " </b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\">  Nomor Induk </td><td>:</td><td valign=center><b><font size=\"2\"> ";?>
  <?php if ($per_ID == 0){echo $NomorIndukNW;echo "-NW";} else {echo $NomorInduk;} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
  
 
 echo "<tr><td><td valign=center><font size=\"2\">  Dibaptis tanggal </td><td>:</td><td valign=center><b><font size=\"2\"> ";?>
  <?php if ($per_ID == 0){echo date2Ind($TanggalBaptisNW,2);} else {echo date2Ind($TanggalBaptis,2);} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\">  Tempat </td><td>:</td><td valign=center><b><font size=\"2\"> ";?>
  <?php if ($per_ID == 0){echo $TempatBaptisNW;} else {echo $TempatBaptis;} ?><?php echo " </font></b></td></font><td></td></td></tr>";	

  
  echo "<tr><td></td></tr>";
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> 
  Dengan ini Majelis ".$sChurchName." menitipkan Pelayanan Sakramen Baptis Anak tersebut kepada Majelis " . $NamaGerejaGKJ . "
  . Demikian permohonan ini kami sampaikan, atas kesediaan dan pelayanannya kami ucapkan terima kasih. 
  Kiranya Tuhan Yesus Kristus senantiasa memberkati pelayanan kita.</font></td>";

 ?>
   <table  border="0" width="100%">
  <tr><td align=center colspan="2"> <?php echo $sChurchCity ;?>,  <?php echo tanggalsekarang();?></td><td></td></tr>
     <tr><td align=center  colspan="2">  Teriring Salam dan Doa Kami, </td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  MAJELIS <?php echo $sChurchFullName ;?> </b></td><td></td></tr>

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
