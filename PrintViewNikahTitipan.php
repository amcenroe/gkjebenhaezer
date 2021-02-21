<?php
/*******************************************************************************
 *
 *  filename    : PrintViewNikahTitipan.php
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
$iKopSurat = FilterInput($_GET["KopSurat"],'int');
$iMode = FilterInput($_GET["Mode"],'int');

	
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
IF(per_ID_L=0,IF(a.WargaGerejaL>0,n.NamaGereja,WargaGerejaNonGKJL),'$sChurchName') as KelompokL,
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
IF(per_ID_P=0,IF(a.WargaGerejaP>0,o.NamaGereja,WargaGerejaNonGKJP),'$sChurchName') as KelompokP,
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
$sPageTitle = gettext("Surat Pengantar Pelayanan Pemebrkatan Perkawinan ".$NikahID."-".$NamaLengkapL."-".$NamaLengkapP);
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Print Surat Pengantar Nikah No.".$NikahID;
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iNikahID . "','" . $logvar1 . "','" . $logvar2 . "')";
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
						$time  = strtotime($TanggalNikah);
						$day   = date('d',$time);
						$month = date('m',$time);
						$year  = date('Y',$time);
						//echo dec2roman(date (m)) ;echo "/"; echo date('Y');
						$NomorSurat2 =  $NikahID."e/SPPP/".$sChurchCode."/".dec2roman($month)."/".$year;

   echo "<tr><td valign=top>Nomor Surat:</td><td><font size=\"2\">  " . $NomorSurat2 . "</font></td>";
  
  echo "<td>".$sChurchCity.", </td><td>"; echo tanggalsekarang() ; date ('Y' ); echo " </td></tr>";
   echo "<tr><td valign=top>Hal:</td><td><font size=\"2\"> Penyerahan/Titipan Pelayanan Ibadah <br>Peneguhan Pernikahan dan Pemberkatan Perkawinan</font></td></tr>";
   echo "<tr><td><font color=#FFFFFF>.</font></td></tr>";
   echo "<tr><td valign=top colspan=2 >Kepada YTH</td><td><font size=\"2\"></font></td></tr>";	

   echo "<tr><td valign=top colspan=2 ><font size=\"2\"><b> Majelis " . $NamaGereja . "</b></td><td><font size=\"2\"></font></td></tr>";
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
 Majelis ".$sChurchFullName." dengan ini mohon kesediaan Majelis ".$NamaGereja.", berkenan melayankan Ibadah Peneguhan Pernikahan 
 dan Pemberkatan Perkawinan bagi <b>Sdr.".$NamaLengkapL." </b>( Warga ".$KelompokL." ) dan <b>Sdri.".$NamaLengkapP." </b> ( Warga ".$KelompokP." ), pada:
 </font></td>"; 
 
  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ></td>
  <td valign=top ><font size=\"2\"> Hari, Tanggal </td><td>:</td><td><font size=\"2\"><b> ".date2Ind($TanggalNikah,1);?><?php echo " </b></td></font><td></td></td>";	
 // echo "<tr><td><td valign=top><font size=\"2\">  No. Stmb </td><td>:</td><td><font size=\"2\"><b> " . $NomorIndukAyah . " </b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Waktu </td><td>:</td><td><font size=\"2\">Pukul <b> " . $WaktuNikah . "</b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Tempat </td><td>:</td><td><font size=\"2\"><b> " . $NamaGereja . "</b></td></font><td></td></td>";
 
   echo "<tr><td></td></tr>";
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> 
  Berdasarkan hasil Penelitian Majelis Gereja, Saudara yang bersangkutan tidak ada halangan apa pun untuk diteguhkan pernikahannya dan diberkati perkawinannya.
  <br>  <img border=\"0\" src=\"\Images\Spacer.gif\" width=\"80\" height=\"1\" >  
  Demikian permohonan ini kami sampaikan, atas kesediaan, kerjasama dan pelayanannya kami ucapkan terima kasih. 
  Kiranya Tuhan Yesus Kristus senantiasa memberkati pelayanan kita.</font></td>";

 ?>
   <table  border="0" width="100%">
  <tr><td align=center colspan="2"> <?php echo $sChurchCity;?> ,  <?php echo tanggalsekarang();?></td><td></td></tr>
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
