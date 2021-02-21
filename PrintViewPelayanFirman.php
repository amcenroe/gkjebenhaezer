<?php
/*******************************************************************************
 *
 *  filename    : PrintViewPelayanFirman.php
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
$iPakID = FilterInput($_GET["PakID"],'int');

// Get this PAK Data
// $sSQL = "SELECT * FROM pakgkjbekti WHERE PakID = " . $iPakID;
$sSQL = "SELECT * FROM pakgkjbekti a 
         LEFT JOIN person_per b ON a.per_ID=b.per_ID 
		 LEFT JOIN family_fam c ON b.per_fam_ID=c.fam_ID
		 LEFT JOIN paktutor d ON a.TutorID=d.TutorID 
		 WHERE PakID = " . $iPakID;

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



function Semester()
{
          switch ($Semester)
          {
          case 1:
            $Semester="Ganjil";
            break;
          case 2:
            $Semester="Genap";
            break;
          }
		 return $Semester;
		 echo $Semester;
}

// Set the page title and include HTML header
$sPageTitle = gettext("NilaiPAK-NomorReg:$PakID-NomorInduk:$per_ID-Nama:$per_FirstName-Kelp:$fam_WorkPhone ");
$iTableSpacerWidth = 10;
require "Include/Header-Print.php";

	$logvar1 = "Print";
	$logvar2 = "View or Print Mail Disposisi";
	//Update Logger
	$sSQL = "INSERT INTO logger (date, userid, per_id, var1, var2)
		VALUES ('" . date("YmdHis") . "'," . $_SESSION['iUserID'].",'" . $iPakID . "','" . $logvar1 . "','" . $logvar2 . "')";
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
     <img border="0" src="gkj_logo.jpg" width="100" >
     </td><!-- Col 1 -->

     <td valign=top align=center >
     <b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo "$sChurchFullName" ;?></font></b><BR>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	 <br style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	  <?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b><br>
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	 <font size="3"><?php echo "Pelayanan Pendidikan Agama Kristen" ;?></font></b><BR>
	    <font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	 <b style="font-family: Arial; color: rgb(0, 0, 102);">
	  <hr style="width: 100%; height: 2px;">
	 <b><font size="2">Surat Keterangan Nilai Pendidikan Agama Kristen</font></b><br>
  <hr>
        </td><!-- Col 3 -->
  </tr>
</table>

<table border="0"  width="600" cellspacing=0 cellpadding=0>
  <tr><!-- Row 1 -->
     <td>
     <font size=2><b><u></u></b></font><br>
     <table border="0"  width="100%">

    <?php

   echo "<tr><td valign=top>Nomor Surat:</td><td><font size=\"2\">  " . $PakID . "/" . $Semester . "/PAK/".$sChurchCode."/"; echo date('Y');" </font></td>";
   echo "<td><?php echo "$sChurchCity" ;?>, </td><td>"; echo tanggalsekarang() ; date ('Y' ); echo " </td></tr>";
   
   echo "<tr><td valign=top>Sifat :</td><td><font size=\"2\">  Penting </font></td></tr>";
   echo "<tr><td valign=top>Hal:</td><td><font size=\"2\"> Nilai Pendidikan Agama Kristen </font></td></tr>";
   echo "<tr><td valign=top>  </td><td><font size=\"2\">  Semester " ;
             switch ($Semester)
          {
          case 1:
            echo gettext("Ganjil");
            break;
          case 2:
            echo gettext("Genap");
            break;
          }
   
   echo " Tahun Ajaran 20" . $TahunAjaran . "/20";echo $TahunAjaran+1 ;echo " </font></td>";
   echo "<tr><td valign=top></td><td><font size=\"2\"></font></td>";
   echo "<td>Kepada YTH </td><td> </td></tr>";
   echo "<tr><td valign=top></td><td><font size=\"2\"></font></td>";
   echo "<td></td><td>Bp/Ibu Guru Wali Kelas " . $Kelas . " " . $KetKelas . "</td></tr>";  
   echo "<tr><td valign=top></td><td><font size=\"2\"></font></td>";
   echo "<td></td><td>" . $AlamatSekolah . "</td></tr>";  
   echo "<tr><td valign=top></td><td><font size=\"2\"></font></td>";
   echo "<td></td><td>Ditempat</td></tr>"; 
	echo "<br>";
   ?>
  </table>
  <br>
  <br>
  <table border="0"  width="100%">
  <?php 
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"80\" height=\"1\" >  
  Dengan hormat ,dengan ini kami sampaikan bahwa ,setelah mengikuti kegiatan belajar</font></td>";
  echo "<tr><td valign=top colspan=\"5\"><font size=\"2\"> 
  Pendidikan  Agama  Kristen  (PAK)  dan Ulangan Akhir  Semester "; 
          switch ($Semester)
          {
          case 1:
            echo gettext("Ganjil");
            break;
          case 2:
            echo gettext("Genap");
            break;
          }
  echo " Tahun Ajaran 20" . $TahunAjaran . "/20";echo $TahunAjaran+1 ;echo " </font></td>";
  echo "<tr><td valign=top colspan=\"5\"><font size=\"2\"> yang diselenggarakan oleh $sChurchFullName .</font></td>";
  echo "<tr><td></td></tr>";
 
  echo "<tr><td><img border=\"0\" src=\"\Images\Spacer.gif\" width=\"30\" height=\"1\" ></td>
  <td valign=top ><font size=\"2\"> Nomor Registrasi </td><td>:</td><td><b> ";?>
  <?php if ($per_ID == 0){echo "NW" . $PakID;} else {echo $per_ID;} ?><?php echo " </b></td></font><td></td></td>";	
  echo "<tr><td><td valign=top><font size=\"2\">  Nama Siswa</td><td>:</td><td><b> ";?>
  <?php if ($per_ID == 0){echo $Nama;} else {echo $per_FirstName;} ?><?php echo " </b></td></font><td></td></td>";	
  echo "<tr><td><td valign=top><font size=\"2\">  Kelas </td><td>:</td><td><b> " . $Kelas . " " . $KetKelas . " </b></td></font><td></td></td>";
  echo "<tr><td><td valign=top><font size=\"2\">  Nama Sekolah </td><td>:</td><td><b> " . $AlamatSekolah . " </b></td></font><td></td></td>";	
  $NilaiAkhir=($Nilai1*0.3)+($Nilai2*0.3)+($Nilai3*0.4); 
  echo "<tr><td><td valign=top><font size=\"2\">  Nilai PAK </td><td>:</td><td><b> " . $NilaiAkhir . "  ( <i> " . Terbilang($NilaiAkhir) . " </i> )</b></td></font><td></td></td>";	
  echo "<tr><td></td></tr>";
  echo "<tr><td></td></tr>";
  echo "<tr><td valign=top colspan=\"4\"><font size=\"2\"> Demikian, untuk dipergunakan sebagaimana mestinya, terima kasih atas  perhatian  dan kerjasama  yang baik.</font></td>";
  echo "<tr><td align=center ></td></tr>";
  echo " <tr><td align=center  > </td></tr>";
  echo "<tr><td align=center ></td></td></tr>";
  echo "<tr><td><br></td><td></td></tr>";
  echo "<tr><td align=center ></td></tr>";
 
 ?>
  </table>  
  </td><!-- Col 1 -->
  </tr>
  
  <table border="0" width="100%">
  <tr><td align=center colspan="2"> <?php echo "$sChurchCity" ;?> ,<?php echo tanggalsekarang() ; date ('Y' );  ?></td><td></td></tr>
   <tr><td align=center  colspan="2">  Mengetahui </td><td></td></tr>
   <tr><td><img border="0" src="Images/Spacer.gif" height="5" ><br></td><td>.</td></tr>
  <tr>
  <td align=center >Pendeta Jemaat</td><td align=center >Guru Pengajar</td>
  </tr>
  
 
  <tr><td align=center ></td><td align=center ><img border="0" src="Images/Tutor/<?php echo $TutorID ?>.gif" height="50" ></td></tr>
  <tr><td align=center >Pdt. Johan Kristantara</td><td align=center ><?php echo $TutorName ?></td></tr>
  <tr><td><img border="0" src="Images/Spacer.gif" height="50" ><br>
  </td><td>.</td></tr>
  <tr><td>Tembusan:</td></tr>
  <tr><td><i>- Arsip Gereja</i></td></tr>
  <tr><td><i>- Orangtua Wali Murid</i></td></tr>
  </table>
  
<br>


</td></tr>
</table>
</td></tr>
</table>
