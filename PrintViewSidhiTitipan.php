<?php
/*******************************************************************************
 *
 *  filename    : PrintViewSidhiTitipan.php
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
$iSidhiID = FilterInput($_GET["SidhiID"],'int');
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');

	
$sSQL = "select a.* , z.SidhiID,
		a.per_id, CONCAT(a.per_id,a.per_fam_id,a.per_gender,a.per_fmr_id) as NomorInduk,
		a.per_firstname as NamaPemohonSidhi , 
		a.per_WorkEmail as TempatLahir,
		CONCAT(a.per_BirthYear,'-',a.per_BirthMonth,'-',a.per_BirthDay) as TanggalLahir,
		CONCAT(b.fam_Address1,',',b.fam_Address2,',',b.fam_City,',',b.fam_State,',',b.fam_Zip) as AlamatKeluarga,
		a.per_Workemail as TempatLahir,
		c.per_firstname as NamaAyah, 
		d.per_firstname as NamaIbu,	
		z.KetuaMajelis as KetuaMajelis,
		z.SekretarisMajelis as SekretarisMajelis,
		z.NamaLengkap as NamaPemohonSidhiNW, 
		z.TempatLahir as TempatLahirNW,
		z.TanggalLahir 	as TanggalLahirNW,
		z.NamaAyah 	as NamaAyahNW,
		z.NamaIbu 	as NamaIbuNW,
		z.TanggalBaptis as TanggalBaptisNW,
		z.TempatBaptis 	as TempatBaptisNW,
		z.PendetaBaptis as PendetaBaptisNW,
		z.NoSuratTitipan as NoSuratTitipanNW, 
		z.PendetaSidhi as PendetaSidhiNW,
		z.TanggalRencanaSidhi as TanggalSidhiNW,
		z.TempatSidhi as TempatLayananSidhi,
		z.TglMulaiKatekisasi as TglMulaiKatekisasi,
		z.TglSelesaiKatekisasi as TglSelesaiKatekisasi,
		z.Pembimbing as Pembimbing,
		
		z.WaktuSidhi as WaktuSidhi,	
		f.NamaGereja as TempatSidhiNW,
		f.NamaGereja as NamaGereja,
		f.Alamat1 as Alamat1Gereja,
		f.Alamat2 as Alamat2Gereja,
		f.Alamat3 as Alamat3Gereja,
		f.Telp as TelpGereja,
		f.Fax as FaxGereja,
		f.Email as EmailGereja,
		
		
		g.NamaGereja as TempatKatekisasi,
		
		x.c1 as TanggalBaptis,
		x.c26 as TempatBaptis,
		x.c37 as DiBaptisOleh,
		x.c2 as TanggalSidhi,
		x.c27 as TempatSidhi,
		x.c38 as DiSidhiOleh,		
	
		a.per_gender as JK , a.per_fam_id
	
	
from sidhigkjbekti z 
left join person_per a ON z.per_id = a.per_id 
left join person_custom x ON a.per_id = x.per_id 
left join family_fam b ON a.per_fam_id = b.fam_id 
left join person_per c ON (b.fam_id = c.per_fam_id AND c.per_fmr_id = 1 AND c.per_gender = 1)
left join person_per d ON (b.fam_id = d.per_fam_id AND d.per_fmr_id = 2 AND d.per_gender = 2)
left join DaftarPendeta e ON z.PendetaSidhi = e.PendetaID
left join DaftarGerejaGKJ f ON z.TempatSidhi = f.GerejaID
left join DaftarGerejaGKJ g ON z.TempatRetreat = g.GerejaID

Where SidhiID =" . $iSidhiID;

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
$sPageTitle = gettext("Surat Pengantar Pelayanan Sidi No.".$NomorInduk."-".$NamaPemohonSidhi."-".$NamaPemohonSidhiNW);
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
						$time  = strtotime($TanggalSidhi);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat =  $SidhiID."e/SPSD/".$sChurchCode."/".dec2roman($month)."/".$year;
						
				//		echo $NomorSurat ;

   echo "<tr><td valign=top>Nomor Surat:</td><td><font size=\"2\">  " . $NomorSurat."</font></td>";
  
  echo "<td>$sChurchCity, </td><td>"; echo tanggalsekarang() ; date ('Y' ); echo " </td></tr>";
   echo "<tr><td valign=top>Hal:</td><td><font size=\"2\"> Surat Pengantar Pelayanan Pengakuan Percaya (SIDI) </font></td></tr>";
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
   echo "<tr><td valign=top colspan=2 >Kepada YTH</td><td><font size=\"2\"></font></td></tr>";	

   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><b> Majelis " . $NamaGerejaGKJ . "</b></td><td><font size=\"2\"></font></td></tr>";
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $NamaGereja . "</font></td><td><font size=\"2\"></font></td></tr>";		   
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat1Gereja . "</font></td><td><font size=\"1\"></font></td></tr>";	
   if ($Alamat2Gereja<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat2Gereja . "</font></td><td><font size=\"1\"></font></td></tr>";
   }else{};	
   if ($Alamat3Gereja<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $Alamat3Gereja . "</font></td><td><font size=\"1\"></font></td></tr>";
  }else{};
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> Telp " . $TelpGereja . ",Fax " . $FaxGereja . "</font></td><td><font size=\"1\"></font></td></tr>";	
  if ($EmailGereja<>""){
   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><font color=#FFFFFF></font> " . $EmailGereja . "</font></td><td><font size=\"1\"></font></td></tr>";
   }else{};	


   ?>
  </table>
  <br>

  <table border="0"  width="100%">
  <?php 
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"><i>Salam Sejahtera dalam kasih Tuhan Yesus Kristus,</i></font></td>"; 
   echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"></font></td>"; 

 echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"80\" height=\"1\" >  
 Majelis " .$sChurchFullName. " dengan ini menerangkan bahwa Saudara yang namanya tersebut di bawah ini :</font></td>"; 
 ?>
 <?php 
 echo "<tr><td><td valign=center><font size=\"2\">  Nama </td><td>:</td><td valign=center><b><font size=\"2\"> ";?>
  <?php if ($per_ID == 0){echo $NamaPemohonSidhiNW;} else {echo $NamaPemohonSidhi;} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\">  Tempat,Tanggal Lahir </td><td>:</td><td valign=center><b><font size=\"2\"> ";?>
  <?php if ($per_ID == 0){echo $TempatLahirNW . ", " . date2Ind($TanggalLahirNW,2); } else 
  {echo $TempatLahir . ", " . date2Ind($TanggalLahir,2) ;} ?><?php echo " </b></td></font><td></td></td></tr>";	
 echo "<tr><td><td valign=center><font size=\"2\">  Nomor Induk </td><td>:</td><td valign=center><b><font size=\"2\"> ";?>
  <?php if ($per_ID == 0){echo $NomorIndukNW;echo "-NW";} else {echo $NomorInduk;} ?><?php echo " </font></b></td></font><td></td></td></tr>";	
  
 
 echo "<tr><td><td valign=center><font size=\"2\">  Tempat,Tanggal Baptis </td><td>:</td><td valign=center><b><font size=\"2\"> ";?>
  <?php if ($per_ID == 0){echo $TempatBaptisNW . ", " . date2Ind($TanggalBaptisNW,2); } else 
  {echo $TempatBaptis . ", " . date2Ind($TanggalBaptis,2) ;} ?><?php echo " </b></td></font><td></td></td></tr>";	

  
  echo "<tr><td></td></tr>";
  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ></td>
  <td valign=top ><font size=\"2\"> Nama (Ayah) </td><td>:</td><td><font size=\"2\"><b> ";?> <?php if ($per_ID == 0){echo $NamaAyahNW;} else {echo $NamaAyah;} ?><?php echo " </b></td></font><td></td></td>";	
 // echo "<tr><td><td valign=top><font size=\"2\">  No. Stmb </td><td>:</td><td><font size=\"2\"><b> " . $NomorIndukAyah . " </b></td></font><td></td></td>";
  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ></td>
  <td valign=top ><font size=\"2\"> Nama (Ibu) </td><td>:</td><td><font size=\"2\"><b> ";?> <?php if ($per_ID == 0){echo $NamaIbuNW;} else {echo $NamaIbu;} ?><?php echo " </b></td></font><td></td></td>";	
//  echo "<tr><td><td valign=top><font size=\"2\">  No. Stmb </td><td>:</td><td><font size=\"2\"><b> " . $NomorIndukIbu . " </b></td></font><td></td></td>";

  echo "<tr><td><td valign=top><font size=\"2\">  Alamat </td><td>:</td><td><font size=\"2\"><b> " . $AlamatKeluarga . " </b></td></font><td></td></td>";	

 
 echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"1\" height=\"1\" >  
Saudara tersebut telah  mengikuti Katekisasi di ".$TempatKatekisasi." selama ".JangkaWaktu($TglMulaiKatekisasi,$TglSelesaiKatekisasi)." dari " . date2Ind($TglMulaiKatekisasi,5)." s/d " . date2Ind($TglSelesaiKatekisasi,5)." dibimbing oleh <b>".$Pembimbing."</b> .
Karena yang bersangkutan meminta untuk menerima Pelayanan Pengakuan Percaya (SIDI) di <b>".$TempatSidhi."</b> pada <b>" . date2Ind($TanggalSidhi,1)." </b>, 
dengan ini kami mempercayakan saudara tersebut kepada Majelis ".$TempatSidhi.", untuk menerima <b>Pelayanan Pengakuan Percaya (SIDI)</b>.</font></td>";
 ?>
<?
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> 
  Demikian permohonan ini kami sampaikan, atas kesediaan dan pelayanannya kami ucapkan terima kasih. 
  Kiranya Tuhan Yesus Kristus senantiasa memberkati pelayanan kita.</font></td>";

 ?>
   <table  border="0" width="100%">
  <tr><td align=center colspan="2"> <?php echo $sChurchCity;?>,  <?php echo tanggalsekarang();?></td><td></td></tr>
     <tr><td align=center  colspan="2">  Teriring Salam dan Doa Kami, </td><td></td></tr>
   <tr><td align=center  colspan="2"><b>  Majelis <?php echo $sChurchFullName;?></b></td><td></td></tr>

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
