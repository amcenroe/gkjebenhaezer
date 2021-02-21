<?php
/*******************************************************************************
 *
 *  filename    : PrintView.php
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

// Get the Kelompok ID from the querystring
$iKelompok = FilterInput($_GET["Kelompok"]);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1"
 http-equiv="content-type">
  <title>Laporan - Detail Status Kelengkapan Dokumen Jemaat</title>

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
	<font size="2"><big style="font-family: Arial;">Laporan - Detail Status Kelengkapan Dokumen Jemaat</big><br></font></b>   
	<b style="font-family: Arial;">Bulan : <?php echo date("F, Y"); ?></b></td>
    </tr>
  </tbody>
</table>
<br>

			<table border="1"  width="750" cellspacing=0 cellpadding=0 >

			<tr>

			<td ALIGN=center><b>No</b></td>
			<td ALIGN=center><b>ID</b></td>
			<td ALIGN=center><b>ID.Klg</b></td>
			<td ALIGN=center><b>Nama Lengkap</b></td>
			<td ALIGN=center><b>Kelompok</b></td>
			<td ALIGN=center><b>Hub</b></td>
			<td ALIGN=center><b>Foto</b></td>
			<td ALIGN=center><b>F.Klg</b></td>
			<td ALIGN=center><b>S.Nikah</b></td>
			<td ALIGN=center><b>SB.Ank</b></td>
			<td ALIGN=center><b>S.Sid</b></td>
			<td ALIGN=center><b>SB.Dws</b></td>
			</tr>
			<?php
//				echo $iKelompok;

				if($iKelompok == "")
				{
				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
				         per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg,
				         CASE per_fmr_id
						 	WHEN '1' THEN 'KK'
						 	WHEN '2' THEN 'Ist'
						 	WHEN '3' THEN 'Ank'
						 	WHEN '4' THEN 'Sdr'
						END AS St_Kelg
				         from person_per
						ORDER BY per_WorkPhone, per_fam_ID, per_fmr_ID, per_birthyear, per_FirstName";
				}else
				{
				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
						per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg,
						CASE per_fmr_id
							WHEN '1' THEN 'KK'
							WHEN '2' THEN 'Ist'
							WHEN '3' THEN 'Ank'
							WHEN '4' THEN 'Sdr'
						END AS St_Kelg
						from person_per
						WHERE per_WorkPhone like '%" . $iKelompok . "'
						ORDER BY per_WorkPhone, per_fam_ID, per_fmr_ID, per_birthyear, per_FirstName";
				}


//				$sSQL = "select per_ID as ID, per_FirstName as Nama ,
//				         per_WorkPhone as Kelompok, per_fam_ID as ID_Kelg, per_fmr_ID as St_Kelg
//				         from person_per
//							ORDER BY per_WorkPhone, per_fam_ID, per_fmr_ID, per_FirstName";
//				echo $sSQL;

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
				<td><?=$hasilGD[ID]?></td>
				<td> <center><?=$hasilGD[ID_Kelg]?></center></td>
				<td><?=$hasilGD[Nama]?></td>
				<td><center><?=$hasilGD[Kelompok]?></center></td>
				<td><center><?=$hasilGD[St_Kelg]?></center></td>

				<td><b><center><?
				$fileFOT = "Images/Person/" . $hasilGD[ID] .".jpg" ;
								if(file_exists($fileFOT))
								{
								echo "<img border=\"0\" src=\"Images/Person/thumbnails/$hasilGD[ID].jpg\" width=\"20\" >" ;
								}else{ echo "<img border=\"0\" src=\"Images/NoData.gif\" width=\"25\" >" ;
								}
				?></b></center></td>

				<td><b><center><?
				$fileFAM = "Images/Family/" . $hasilGD[ID_Kelg] .".jpg" ;
								if(file_exists($fileFAM))
								{
								echo "<img border=\"0\" src=\"Images/Family/thumbnails/$hasilGD[ID_Kelg].jpg\" width=\"25\" >" ;
								}else{ echo "<img border=\"0\" src=\"Images/NoData.gif\" width=\"25\" >" ;
								}
				?></b></center></td>

				<td><b><center><? if($hasilGD[St_Kelg]=="KK" OR $hasilGD[St_Kelg]=="Ist"){
				$fileSNK = "Images/Nikah/SN" . $hasilGD[ID_Kelg] .".jpg" ;
								if(file_exists($fileSNK))
								{
								echo "<img border=\"1\" src=\"Images/Nikah/thumbnails/SN$hasilGD[ID_Kelg].jpg\" width=\"20\" >" ;
								}else{ echo "<img border=\"0\" src=\"Images/NoData.gif\" width=\"25\" >" ;
								}
				}
				?></b></center></td>

				<td><b><center><?
				$fileSBA = "Images/BaptisAnak/SBA" . $hasilGD[ID] .".jpg" ;
								if(file_exists($fileSBA))
								{
								echo "<img border=\"1\" src=\"Images/BaptisAnak/thumbnails/SBA$hasilGD[ID].jpg\" width=\"20\" >" ;
								}else{ echo "<img border=\"0\" src=\"Images/NoData.gif\" width=\"25\" >" ;
								}
				?></b></center></td>

				<td><b><center><?
				$fileSDI = "Images/Sidhi/SS" . $hasilGD[ID] .".jpg" ;
								if(file_exists($fileSDI))
								{
								echo "<img border=\"1\" src=\"Images/Sidhi/thumbnails/SS$hasilGD[ID].jpg\" width=\"20\" >" ;
								}else{ echo "<img border=\"0\" src=\"Images/NoData.gif\" width=\"25\" >" ;
								}
				?></b></center></td>

				<td><b><center><?
				$fileSBD = "Images/BaptisDewasa/SBD" . $hasilGD[ID] .".jpg" ;
								if(file_exists($fileSBD))
								{
								echo "<img border=\"1\" src=\"Images/BaptisDewasa/thumbnails/SBD$hasilGD[ID].jpg\" width=\"20\" >" ;
								}else{ echo "<img border=\"0\" src=\"Images/NoData.gif\" width=\"25\" >" ;
								}
				?></b></center></td>



				</tr>
				<?}?>
			</table>
			Keterangan: <br>
			Hub : Hub Status Keluarga (KK:Kepala Keluarga, Ist:Istri/Pasangan, Ank:Anak, Sdr:Saudara)<br>
			Foto: Pas Foto Pribadi - F.Klg: Foto Keluarga<br>
			S.Nikah: SuratNikah - SB.Ank: Surat BaptisAnak - S.Sid: SuratSidhi - SB.Dws: Surat BaptisDewasa


</div>

<?php
require "Include/Footer-Short.php";
?>
