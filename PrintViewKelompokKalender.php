<?php
/*******************************************************************************
 *
 *  filename    : PrintViewKelompokKalender.php
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
require "Include/Header-Print.php";
// Get the Gol Darah ID from the querystring
$iKelompok = FilterInput($_GET["Kelompok"]);
if ($iKelompok == '' ){ $FilterKelompok = ""; }else{ $FilterKelompok = " fam_WorkPhone ='$iKelompok' AND ";} 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Laporan - Detail Daftar Keluarga per Kelompok</title>

<STYLE TYPE="text/css">
  		<!--
  		TD{font-family: Arial; font-size: 10pt;}
		--->
        P.breakhere {page-break-before: always}
</STYLE>
</head>
<body background="gkj_back2.jpg" onload="javascript:scrollToCoordinates()"  SCROLL="auto" >

<DIV align=center >

<table
 style="width: 750; text-align: left; margin-left: auto; margin-right: auto;"
 border="0" cellpadding="2" cellspacing="2">
  <tbody>
    <tr>
      <td style="width: 125px;"><img style="width: 90px; height: 90px;" src="gkj_logo.jpg" border="0"></td>
      <td style="width: 630px;"><b style="font-family: Arial; color: rgb(0, 0, 102);"><font size="4"><?php echo $sChurchName;?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "$sChurchAddress"." $sChurchCity"." $sChurchState"." $sChurchZip ";?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);">
	<font size="2" style="font-family: Arial; color: rgb(0, 0, 102);">
	<?php echo "Telp: "." $sChurchPhone " . "- Email: "." $sChurchEmail";?></font></b>
	<br style="font-family: Arial; color: rgb(0, 0, 102);"><b style="font-family: Arial; color: rgb(0, 0, 102);">
	<hr style="width: 100%; height: 2px;">
	<font size="2"><big style="font-family: Arial;">Daftar Keluarga Kelompok <?php echo $iKelompok ?></big><br></font></b>   
	<b style="font-family: Arial;">Bulan : <?php echo date("F, Y"); ?></b></td>
    </tr>
  </tbody>
</table>
<br>

			<table border="0"  width="1100" cellspacing=0 cellpadding=0 >
			<u><b> </b></u><br>
			<tr><b><font size="2">
			<td><b><font size="2"><center>No </center></font></b></td>
			<td><b><font size="2"> | </font></b></td>
			<td><b><font size="2"><center> Kelompok </center></font></b></td>
			<td><b><font size="2"> | </font></b></td>
			<td><b><font size="2"><center> ID kelg </center></font></b></td>
			<td><b><font size="2"> | </font></b></td>
			<td><b><font size="2"><center>Nama Lengkap Kepala Keluarga</center></font></b></td>
			<td><b><font size="2"> | </font></b></td>
			<td><b><font size="2"><center>Alamat Lengkap</center></font></b></td>
			<td><b><font size="2"> | </font></b></td>
			<td><b><font size="2"><center>Telp Rumah</center></font></b></td>
			<td><b><font size="2"> | </font></b></td>
			<td><b><font size="2"><center>Handphone</center></font></b></td>
			<td><b><font size="2"> | </font></b></td>
			<td><b><font size="2"><center>Status</center></font></b></td>
			<td><b><font size="2"> | </font></b></td>			
			</font></b></tr>
			<?php
			 	$sRowClass = "RowColorA";
			//	$sSQL = "SELECT per_fam_ID as IDK, per_WorkPhone as Kelompok, per_FirstName as Nama, fam_HomePhone as TelpRumah, per_Cellphone as Handphone, fam_Name as NmKelg, 						
			//	fam_Address1 as Alamat, a2.lst_OptionName as Kewargaan,
			//		per_birthDay as TGL, per_birthMonth as BLN, per_birthYear as THN, a2.lst_OptionName as Status
			//		FROM person_per
			//			LEFT JOIN family_fam ON person_per.per_fam_ID = family_fam.fam_ID
			//			LEFT JOIN list_lst as a1 ON a1.lst_OptionID = person_per.per_fmr_ID AND a1.lst_ID = 2
			//			LEFT JOIN list_lst as a2 ON a2.lst_OptionID = person_per.per_cls_ID AND a2.lst_ID = 1
			//			WHERE per_WorkPhone like '%$iKelompok%' AND person_per.per_fmr_ID = 1 AND person_per.per_cls_id < 6
			//			ORDER BY per_WorkPhone, family_fam.fam_Name, person_per.per_fmr_ID, person_per.per_BirthYear";
				
				$sSQL = "SELECT fam_Name as Nama, fam_ID as IDK, fam_WorkPhone as Kelompok, fam_HomePhone as TelpRumah, fam_Address1 as Alamat, 
				fam_Email as Status 
				from family_fam
				where $FilterKelompok (fam_Email = 'Aktif' OR fam_Email = 'Titipan')
				order by fam_Workphone, fam_Name";


				//$sSQL = "select c.lst_OptionName as StatusKewargaan, a.per_FirstName as Nama ,
				//         per_HomePhone as TelpRumah, per_BirthYear as TahunLahir ,
				//         per_WorkPhone as Kelompok from person_per a , list_lst c
				//			WHERE a.per_cls_ID = c.lst_OptionID AND c.lst_ID = 1
				//			AND c.lst_OptionID = '$iStatus'
				//			ORDER BY a.per_WorkPhone";
				$perintah = mysql_query($sSQL);
				$i = 0;
				while ($hasilGD=mysql_fetch_array($perintah))
				{

					$i++;
					extract($hasilGD);
					//Alternate the row color
                    $sRowClass = AlternateRowStyle($sRowClass);


				?>
				<tr class="<?php echo $sRowClass; ?>">
				<td><? echo $i ?></td>
				<td> </td>
				<td><?=$hasilGD[Kelompok]?> </td>
				<td> </td>
				<td><?=$hasilGD[IDK]?> </td>
				<td> </td>
				<td><?=$hasilGD[Nama]?></td>
				<td> </td>
				<td><?=$hasilGD[Alamat]?></td>
				<td> </td>				
				<td><?=$hasilGD[TelpRumah]?></td>
				<td> </td>				
				<td><?=$hasilGD[Handphone]?></td>
				<td> </td>				
				<td><?=$hasilGD[Status]?></td>
								</tr>
				<?}?>
			</table>



<?php
require "Include/Footer-Short.php";
?>
